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
class JFormFieldInvoiceActions extends IWActionsList {

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'INVOICEACTIONS';

    protected function getActions() {
        $id = $this->form->getValue('id');
        $estado = $this->form->getValue('status');
        $bankid = (int) $this->form->getValue('bankaccount_id');
        $bank = JModelLegacy::getInstance('BankAccount', 'QuipuModel')->getItem($bankid);
        $option = IWRequest::getCmd('option');
        $acciones = array();

        $a = new JObject();
        $a->texto = JText::_('COM_QUIPU_SEND_TO_CUSTOMER');
        $a->css = 'quipu-email';
        $a->url = JRoute::_("index.php?option=$option&view=invoice&format=email&id=$id", false);
        $a->ajax = true;
        $acciones[] = $a;

        $a = new JObject();
        $a->texto = JText::_('COM_QUIPU_PRINT');
        $a->css = 'quipu-print';
        $a->url = JRoute::_("index.php?option=$option&view=invoice&format=pdf&id=$id", false);
        $a->target = '_blank';
        $acciones[] = $a;

        switch ($estado) {
            case(IW_ERP_ESTADO_FACTURA_REEMBOLSADA):                
            case(IW_ERP_ESTADO_FACTURA_REEMBOLSO):
                break;
            case(IW_ERP_ESTADO_FACTURA_COBRADA):
                $a = new JObject();
                $a->texto = JText::_('COM_QUIPU_INVOICE_REFUND');
                $a->css = 'quipu-refund';
                $a->url = JRoute::_("index.php?option=$option&task=invoice.refund&id=$id", false);
                $acciones[] = $a;
                break;
            default:
                $a = new JObject();
                $a->texto = JText::_('COM_QUIPU_INVOICE_REFUND');
                $a->css = 'quipu-refund';
                $a->url = JRoute::_("index.php?option=$option&task=invoice.refund&id=$id", false);
                $acciones[] = $a;
                if ($bankid) {
                    $a = new JObject();
                    $a->texto = JText::_('COM_QUIPU_INVOICE_PAYMENT');
                    $a->css = 'quipu-payment';
                    $a->target = 'modal';
                    $a->url = JRoute::_("index.php?option=$option&view=invoice&format=payment&tmpl=component&id=$id", false);
                    $acciones[] = $a;
                }
                break;
        }
        return $acciones;
    }

}