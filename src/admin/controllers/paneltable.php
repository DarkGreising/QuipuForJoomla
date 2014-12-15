<?php

/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 *
 * @package    com_quipu
 * 
 * 
 */
class QuipuControllerPanelTable extends JControllerLegacy {

    private $_totalItems = 0;

    /**
     * 
     */
    public function data() {
        $token = IWRequest::getString('w');
        $this->widget = QuipuWidgets::getWidget($token, true);

        $this->loadData();
        $this->translateObjects();

        switch ($this->widget['type']) {
            case 'table':
                if (QUIPU_IS_J3) {
                    $ret = array("page" => $this->page, "total" => $this->total, "rows" => $this->objects);
                } else {
                    $ret = array("page" => $this->page, "total" => $this->total, "data" => $this->objects);
                }
                break;
            case 'chart':
                $ret = $this->getChartDef();
                if ($this->_totalItems === 0) {
                    $ret['title'] = array('text' => JText::_('COM_QUIPU_NO_DATA'));
                }
                break;
            default:
                $ret = 'TIPO DESCONOCIDO';
        }


        //print_r($ret);
        $json = json_encode($ret);
        echo $json;
        exit();
    }

    /**
     *      
     */
    private function translateObjects() {
        if (isset($this->objects) && is_array($this->objects)) {
            foreach ($this->objects as $o) {
                foreach (get_object_vars($o) as $k => $v) {
                    if (preg_match('/^[0-9]+\.[0-9]{2}$/', $v)) {
                        $o->$k = '<div class="' . ($v < 0 ? 'negative ' : '') . 'number">' . IWUtils::fmtEuro($v, false) . '</div>';
                    }
                    if (strpos($v, 'COM_QUIPU_') === 0) {
                        $o->$k = JText::_($v);
                    }
                }
            }
        }
    }

    /**
     * 
     */
    private function postProcessData() {
        if ($this->objects) {
            $num = count($this->objects);
            foreach ($this->objects as $o) {
                //1. Formato de fechas y sustitución de cadenas de idioma:
                $this->prepareObject($o);
                //2. aplicar el procesador de filas.                
                if ($rowFactory) {
                    $this->$f($o, $num);
                }
            }
            /*
             * Es posible que el postprocesador de filas haya ido creando una nueva colección
             * de objetos, por ej. para meter filas con sub totales.
             */
            if ($this->newObjects) {
                $this->objects = $this->newObjects;
                unset($this->newObjects);
            }
        } else if ($this->series) {
            foreach ($this->series as $s) {
                $num = count($s['objects']);
                foreach ($s['objects'] as $o) {
                    $this->prepareObject($o);
                }
                if ($this->newObjects) {
                    $this->objects = $this->newObjects;
                    unset($this->newObjects);
                }
            }
        }
    }

    private function prepareObject(& $o) {
        foreach ($o as $k => $v) {
            if (preg_match('/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/', $o->$k)) {
                $o->$k = IWDate::_($o->$k);
            }
            if ($o->label && substr($o->label, 0, 1) == '_') {
                $o->label = JText::_(substr($o->label, 1));
            }
        }
    }

    /**
     * 
     */
    private function loadData() {

        $db = JFactory::getDbo();
        if (array_key_exists('query', $this->widget) && $this->widget['query']) {
            $query = $this->widget['query'];
            //Los widgets de tabla tienen paginación.
            if ($this->widget['type'] == 'table') {
                /*
                 * Quiero cambiar el primer SELECT por un SELECT SQL_CALC_FOUND_ROWS, pero
                 * no puedo hacer un str_replace xq puede haber varias SELECT si existen
                 * subconsultas. Así que quito directamente los primeros 6 caracteres,
                 * asegurándome primero de que noy hay espacios antes con un ltrim
                 */
                $query = substr(ltrim($query), 6);
                $query = "SELECT SQL_CALC_FOUND_ROWS $query";

                $this->page = IWRequest::getInt('page', 1);
                $perpage = IWRequest::getInt('perpage', 10);
                $sorton = IWRequest::getString('sorton', null);
                $sortby = IWRequest::getString('sortby', null);
                $start = ($this->page - 1) * $perpage;

                if ($sorton) {
                    $query .= ' ORDER BY ' . $sorton;
                    if ($sortby) {
                        $query .= " $sortby";
                    }
                }
                $query .= " LIMIT $start,$perpage";
            }
            $db->setQuery($query);
            $this->objects = $db->loadObjectList();
            $this->_totalItems = count($this->objects);

            if ($this->widget['type'] == 'table') {
                $this->total = $this->getNumRows($query);
            }
        } elseif ($this->widget['series']) {
            $series = array();
            $keys = array();

            foreach ($this->widget['series'] as $serie) {
                $query = $serie['query'];
                $db->setQuery($query);
                $objects = $db->loadObjectList('label');
                $itemsCount = count($objects);

                $this->_totalItems += $itemsCount;

                $keys = array_merge($keys, array_keys($objects));

                $series[] = array(
                    'title' => JText::_($serie['title']),
                    'color' => $serie['color'],
                    'objects' => $objects
                );
            }
            /*
             * Puede que las series tengan tamaños diferentes, si no hay datos
             * para todos los valores de x en todas las consultas. En ese caso
             * tenemos que asegurarnos de que todas las series lleguen a ofc 
             * con los mismos elementos, rellenando los valores ausentes con ceros.
             */
            $keys = $this->prepareKeys($keys);

            $this->series = array();

            foreach ($series as $serie) {
                $items = $serie['objects'];
                $newItems = array();
                foreach ($keys as $key) {
                    $item = $items[$key];
                    if (!$item) {
                        $item = new JObject(array('label' => $key, 'value' => '0'));
                    }
                    $newItems[] = $item;
                }
                $serie['objects'] = $newItems;
                $this->series[] = $serie;
            }
            //print_r($this->series);
        }
        if ($db->getErrorNum()) {
            $this->widget['error'] = $db->getErrorMsg();
        }
    }

    /**
     *
     * @param type $original 
     */
    private function prepareKeys($original) {

        if (is_array($original) && count($original)) {
            $keys = array_unique($original);
            $first = $keys[0];
            //Si el primer elemento es una fecha ordeno todas las claves.
            if (preg_match('/^(\d\d?)\/(\d\d?)\/(\d\d\d\d)$/', $first)) {
                usort($keys, 'iw_6c_widget_date_cmp');
            }

            return $keys;
        }
        return $original;
    }

    /**
     *
     * @param type $label 
     */
    private function findObjectInSerie($items, $label) {
        foreach ($items as $item) {
            if ($item->label == $label) {
                return $item;
            }
        }
        return false;
    }

    /**
     * @param type $query 
     */
    private function getNumRows($query) {
        $db = JFactory::getDbo();
        $db->setQuery('SELECT FOUND_ROWS()');
        return $db->loadResult();
    }

    /**
     * +info: http://teethgrinder.co.uk/open-flash-chart-2/json-format.php
     * @param type $res
     * @return type 
     */
    private function getChartDef() {
        switch ($this->widget['chart-type']) {
            case 'bar':
                return $this->getBarChartDef();
            case 'line':
                return $this->getLineChartDef();
            case 'pie':
                return $this->getPieChartDef();
            default:
                return $data('ERROR');
        }
    }

    /**
     * Crea la estructura de arrays para generar el JSON para open flash chart.
     * @param type $type 
     */
    private function getPieChartDef() {
        $data = $this->getCommonChartDef();

        $this->max = 0;
        $this->labels = array();
        $max = 1000;
        $granularity = 10;
        foreach ($this->objects as $o) {
            $token = $this->stringToInt($o->label, $max, $granularity);
            $colors[] = IWUtils::random_color($token, $max, .2, .3);
        }
        $data['elements'][] = $this->loadSerie($this->objects, $this->widget['title'], $colors, 'pie');
        unset($data['x_axis']);

        return $data;
    }

    /**
     *
     * @param type $input
     * @param type $max 
     */
    private function stringToInt($input, $max = 100, $granularity = 1000) {
        //1. obtengo el md5 del nombre de la serie
        //2. lo convierto a un entero con base_convert
        //3. lo normalizo según el valor de $max.
        //Hay varios estados que empiezan por "Pend". Le damos la vuelta a la cadena
        //para aumentar la diferencia entre las cadenas (comprobado por prueba y error)
        $token = md5(strrev($input));
        $token = base_convert($token, 16, 10);
        do {
            $v = str_split("$token");
            $token = array_sum($v);
        } while ($token >= $granularity);

        $val = $max * ($token / $granularity);
        return round($val);
    }

    /**
     * Crea la estructura de arrays para generar el JSON para open flash chart.
     * @param type $type 
     */
    private function getLineChartDef() {
        $data = $this->getCommonChartDef();

        $this->max = 0;
        $this->labels = array();

        if ($this->objects) {
            $data['elements'][] = $this->loadSerie($this->objects, $this->widget['title'], $serie['color'], 'line');
        } elseif ($this->series) {
            foreach ($this->series as $serie) {
                $data['elements'][] = $this->loadSerie($serie['objects'], $serie['title'], $serie['color'], 'line');
            }
        }
        $n = count($this->labels);
        if ($n > 5) {
            //Mostramos siempre 5 etiquetas en el eje x.
            $steps = round($n / 5);
            $data['x_axis']->labels = array('labels' => $this->labels, 'steps' => $steps);
            //$data['x_axis']->steps = 5;
        } else {
            $data['x_axis']->labels = array('labels' => $this->labels);
        }

        $data['y_axis']->max = $this->max * 1.3; //sumo un 10% xa ver el titulo
        $data['y_axis']->steps = (int) ($this->max / 5);

        return $data;
    }

    /**
     * Crea la estructura de arrays para generar el JSON para open flash chart.
     * @param type $type 
     */
    private function getBarChartDef() {
        $data = $this->getCommonChartDef();

        $this->max = 0;
        $this->labels = array();

        if (isset($this->objects)) {
            $data['elements'][] = $this->loadSerie($this->objects, $this->widget['title']);
        } elseif ($this->series) {
            foreach ($this->series as $serie) {
                $data['elements'][] = $this->loadSerie($serie['objects'], $serie['title'], $serie['color']);
            }
        }

        $data['x_axis']->labels = array('labels' => $this->labels);
        $data['y_axis']->max = $this->max * 1.3; //sumo un 10% xa ver el titulo
        $data['y_axis']->steps = (int) ($this->max / 5);

        return $data;
    }

    /**
     *
     * @param type $objects 
     */
    private function loadSerie($objects, $title, $color = '#DDD', $type = 'bar') {
        $values = array();
        foreach ($objects as $o) {
            if ($type != 'pie') {
                $values[] = (int) $o->value;
            } else {
                $values[] = new JObject(array(
                    'label' => "$o->label ($o->value%)",
                    'value' => (int) $o->value
                ));
            }
            $l = Jtext::_($o->label);
            if (!in_array($l, $this->labels)) {
                $this->labels[] = $l;
            }
            if ($o->value > $this->max)
                $this->max = $o->value;
        }
        $s = array(
            'type' => $type,
            'text' => JText::_($title),
            'alpha' => .95,
            'values' => $values
        );

        if (is_array($color)) {
            $s['colours'] = $color;
        } else {
            $s['colour'] = $color;
        }
        if ($type == 'line') {
            $s['dot-style'] = new JObject(array('type' => 'solid-dot', 'dot-size' => 3, 'halo-size' => 1, 'colour' => $color));
            $s['on-show'] = new JObject(array('type' => 'pop-up', 'cascade' => 1, 'delay' => 0));
        } else if ($type == 'pie') {
            $s['animate'] = array(
                new JObject(array('type' => 'bounce', 'distance' => '4'))
            );
            $s['tip'] = '#percent# (#val# de #total#)';
        }
        return $s;
    }

    /**
     *
     * @return array 
     */
    public function getCommonChartDef() {
        $data = array(
            'bg_colour' => '#ffffff',
            'grid_colour' => '#cccccc',
            /* 'title' => array(
              'text' => $this->widget['title'],
              'style' => '{colour:#32485D}'
              ), */
            'x_legend' => array(
                'text' => JText::_($this->widget['x_legend']),
                'style' => new JObject(array('colour' => '#cccccc'))
            ),
            'y_legend' => array(
                'text' => JText::_($this->widget['y_legend']),
                'style' => new JObject(array('colour' => '#cccccc'))
            ),
            'elements' => array(),
            'x_axis' => new JObject(array(
                'colour' => '#cccccc',
                'grid_colour' => '#cccccc',
                    )),
            'y_axis' => new JObject(array(
                'colour' => '#cccccc',
                'grid_colour' => '#cccccc',
                    ))
        );
        return $data;
    }

}

/**
 *
 * @param type $a
 * @param type $b
 * @return int 
 */
function iw_6c_widget_date_cmp($a, $b) {
    $a = str_replace('/', '-', $a);
    $a = strtotime($a);
    $b = str_replace('/', '-', $b);
    $b = strtotime($b);

    if ($a == $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;
}