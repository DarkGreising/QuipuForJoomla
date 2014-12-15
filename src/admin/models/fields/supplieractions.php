<?php

/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
require_once 'actionslist.php';

/**
 * Quipu Form Field class for the Quipu component
 */
class JFormFieldSupplieractions extends IWActionsList {

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'SUPPLIERACTIONS';

    protected function getActions() {
        $id = $this->form->getValue('id');
        $option = IWRequest::getCmd('option');
        $acciones = array();
        $a = new JObject();
        $a->texto = JText::_('COM_QUIPU_REGISTER_PURCHASE_ORDER');
        $a->css = 'order';
        $a->url = JRoute::_("index.php?option=$option&task=purchaseorder.add&s=$id", false);
        $acciones[] = $a;

        return $acciones;
    }

}