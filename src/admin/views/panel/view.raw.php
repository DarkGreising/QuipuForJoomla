<?php

/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

class QuipuViewPanel extends IWItemEditView {

    /**
     *
     * @param type $tpl
     * @return type 
     */
    function display($tpl = null) {

        $this->token = IWRequest::getString('w');

        $this->loadWidget();

        if (array_key_exists('chart-type', $this->widget) && $this->widget['chart-type'] == 'pie') {
            $w = 380;
            $h = 180;
        } else {
            $w = 400;
            $h = 200;
        }

        switch ($this->widget['width']) {
            case '2x':
                $this->widget['width'] = 2 * $w;
                break;
            default:
                $this->widget['width'] = $w;
        }
        switch ($this->widget['height']) {
            case '2x':
                $this->widget['height'] = 2 * $h;
                break;
            default:
                $this->widget['height'] = $h;
        }
        //print_r($this->widget);
        // Comprobación de errores.
        if (count($errors = $this->get('Errors'))) {
            var_dump($errors);
            return false;
        }

        // Muestra la plantilla
        if($this->widget['type'] != 'view'){
            parent::display($this->widget['type']);
        }
        else{
            echo $this->loadTemplate('widget_' . $this->widget['tmpl']);
        }
        
    }

    /**
     * 
     */
    private function loadWidget() {
        $debug = false;
        if ($debug) {
            $this->widget = QuipuWidgets::getWidget($this->token, true);
            $queries = array();
            if ($this->widget['query']) {
                $queries[] = $this->widget['query'];
            }
            if ($this->widget['series']) {
                foreach ($this->widget['series'] as $s) {
                    $queries[] = $s['query'];
                }
            }
            foreach ($queries as $q) {
                $this->widget['info'] .= '<pre>' . trim($q) . '</pre><hr />';
            }
        } else {
            $this->widget = QuipuWidgets::getWidget($this->token, false);
        }
    }

}

