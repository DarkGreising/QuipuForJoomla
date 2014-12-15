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
class JFormFieldPurchaseorderactions extends IWActionsList {

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'PURCHASEORDERACTIONS';

    protected function getActions() {
        $id = $this->form->getValue('id');
        $estado = $this->form->getValue('status');
        $bank_id = $this->form->getValue('bankaccount_id');
        $payment_date = $this->form->getValue('payment_date');
        $option = IWRequest::getCmd('option');
        $acciones = array();

        $a = new JObject();
        $a->texto = JText::_('COM_QUIPU_PRINT');
        $a->css = 'quipu-print';
        $a->url = JRoute::_("index.php?option=$option&view=purchaseorder&format=pdf&id=$id", false);
        $a->target = '_blank';
        $acciones[] = $a;
        

        switch ($estado) {
            case(IW_ERP_ESTADO_PEDIDO_CANCELADO):
                break;
            case(IW_ERP_ESTADO_PEDIDO_FACTURADO):
                $a = new JObject();
                $a->texto = JText::_('COM_QUIPU_VIEW_INVOICE');
                $a->css = 'quipu-print';
                $a->url = JRoute::_("index.php?option=$option&view=purchaseorder&format=invoice&id=$id", false);
                $a->target = '_blank';
                $acciones[] = $a;

                if ($bank_id && !($payment_date && $payment_date != '0000-00-00')) {
                    $a = new JObject();
                    $a->texto = JText::_('COM_QUIPU_INVOICE_PAYMENT');
                    $a->css = 'quipu-payment';
                    $a->target = 'modal';
                    $a->url = JRoute::_("index.php?option=$option&view=purchaseorder&format=payment&tmpl=component&id=$id", false);
                    $acciones[] = $a;
                }
                break;
            default:

                $a = new JObject();
                $a->texto = JText::_('COM_QUIPU_CANCEL_PURCHASE_ORDER');
                $a->css = 'quipu-cancel';
                $a->url = JRoute::_("index.php?option=$option&task=purchaseorder.cancelOrder&id=$id", false);
                $acciones[] = $a;

                break;
        }
        return $acciones;
    }

}