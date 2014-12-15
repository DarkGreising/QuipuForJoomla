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
class JFormFieldOrderactions extends IWActionsList {

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'ORDERACTIONS';

    protected function getActions() {
        $id = $this->form->getValue('id');
        $estado = $this->form->getValue('status');
        $option = IWRequest::getCmd('option');
        $acciones = array();

        $a = new JObject();
        $a->texto = JText::_('COM_QUIPU_PRINT');
        $a->css = 'quipu-print';
        $a->url = JRoute::_("index.php?option=$option&view=order&format=pdf&id=$id", false);
        $a->target = '_blank';
        $acciones[] = $a;

        $a = new JObject();
        $a->texto = JText::_('COM_QUIPU_PRINT_QUOTATION');
        $a->css = 'quipu-print';
        $a->url = JRoute::_("index.php?option=$option&view=order&format=pdf&quotation=true&id=$id", false);
        $a->target = '_blank';
        $acciones[] = $a;

        switch ($estado) {
            case(IW_ERP_ESTADO_PEDIDO_CANCELADO):
                break;
            case(IW_ERP_ESTADO_PEDIDO_FACTURADO):
                break;
            default:
                $a = new JObject();
                $a->texto = JText::_('COM_QUIPU_GENERATE_INVOICE');
                $a->css = 'quipu-invoice';
                $a->url = JRoute::_("index.php?option=$option&task=order.invoice&id=$id", false);
                $acciones[] = $a;

                $a = new JObject();
                $a->texto = JText::_('COM_QUIPU_SEND_TO_CUSTOMER');
                $a->css = 'quipu-email';
                $a->url = JRoute::_("index.php?option=$option&view=order&format=email&id=$id", false);
                $a->ajax = true;
                $acciones[] = $a;

                $a = new JObject();
                $a->texto = JText::_('COM_QUIPU_CANCEL_ORDER');
                $a->css = 'quipu-cancel';
                $a->url = JRoute::_("index.php?option=$option&task=order.cancelOrder&id=$id", false);
                $acciones[] = $a;

                break;
        }
        return $acciones;
    }

}