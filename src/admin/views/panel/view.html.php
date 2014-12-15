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

    protected $widgets;
    protected $maxCols = 2;

    function display($tpl = null) {

        $this->loadWidgets();

        // Comprobación de errores.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        
        if (QUIPU_IS_J3) {
            JToolBarHelper::title(JText::_('COM_QUIPU'));
            QuipuHelper::addSubmenu(IWRequest::getCmd('view', 'customers'));
            $this->sidebar = JHtmlSidebar::render();
        }


        // Muestra la plantilla
        parent::display($tpl);
    }

    /**
     */
    private function loadWidgets() {

        $w = array(
            'panel' => array('monthly_invoicing', 'last_customers', 'order_statuses', 'pending_orders', 'pending_invoices'),
        );

        $this->mode = IWRequest::getString('m', 'panel');
        $this->widgets = array();

        /**
         * @todo: extract this to config entity
         */
        $userWidgets = array('monthly_invoicing', 'pending_invoices', 'last_customers', 'order_statuses', 'pending_orders');

        foreach ($userWidgets as $wName) {
            if (in_array($wName, $w[$this->mode])) {
                $wdg = QuipuWidgets::getWidget($wName);
                $this->widgets[] = $wdg;
            }
        }
        $this->sortWidgets();
    }

    /**
     * 1. No puede haber un widget 2x salvo en la 1ª columna.
     * 
     */
    protected function sortWidgets() {
        $col = 0;
        //Ancho del ultimo widget mostrado
        $lastWidth = 0;
        //Alto del último widget mostrado.
        $lastHeight = 0;
        //Lo que ocupa un widget de la fila superior que tenía alto > 1x
        $heightRemainder = 0;
        $newList = array();

        while ($w = array_shift($this->widgets)) {
            //si toca, lo coloco en la lista
            $width = (int) substr($w['width'], 0, 1);
            $height = (int) substr($w['height'], 0, 1);
            if ($height == 1) {
                if (($width + $col + $heightRemainder) <= $this->maxCols) {
                    $newList[] = $w;
                    $lastWidth = $width;
                    $lastHeight = $height;
                } else {
                    //si no toca, lo vuelvo a poner al final
                    //array_push($this->widgets,$w);
                    if (count($this->widgets) > 1) {
                        array_splice($this->widgets, 1, 0, array($w));
                    } else {
                        array_push($this->widgets, $w);
                    }
                }
            } else {
                //De momento, los widgets con alto doble los ponemos siempre en la 1ª col.
                if ($col == 0) {
                    $newList[] = $w;
                    $lastWidth = $width;
                    $lastHeight = $height;
                    $heightRemainder = $height - 1;
                } else {
                    //si no toca, lo vuelvo a poner al final
                    //array_push($this->widgets,$w);
                    if (count($this->widgets) > 1) {
                        array_splice($this->widgets, 1, 0, array($w));
                    } else {
                        array_push($this->widgets, $w);
                    }
                }
            }


            if ($col < $this->maxCols) {
                $col++;
            } else {
                $col = 0;
            }
            if ($heightRemainder > 0) {
                $heightRemainder--;
            }
        }

        $this->widgets = $newList;
    }

}

