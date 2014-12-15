<?php

/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');
require_once 'rest.php';

/**
 * 
 */
class QuipuControllerApi extends RestController {

    /**
     * 
     */
    public function invoice() {
        $this->table = 'invoice';
        $this->table_prefix = 'QuipuTable';

        $this->processRequest();
    }

    /**
     * 
     */
    public function purchaseorder() {
        $this->table = 'purchaseorder';
        $this->table_prefix = 'QuipuTable';

        $this->processRequest();
    }

    /**
     * 
     */
    protected function beforePurchaseorderPOST() {
        $params = IWRequest::getVar($this->table);
        if (array_key_exists('id', $params)) {
            if (array_key_exists('paid', $params) && (boolean) $params['paid']) {
                $model = JModelLegacy::getInstance('PurchaseOrder', 'QuipuModel');
                if ($model->isPaid($params['id'])) {
                    unset($params['paid']);
                } else {
                    $params['payment_date'] = IWDate::mySQLDate(); 
                }
                IWRequest::setVar($this->table, $params);
            }
        }
    }

    /**
     * 
     */
    protected function beforeInvoicePOST() {
        $params = IWRequest::getVar($this->table);
        if (array_key_exists('id', $params)) {
            if (array_key_exists('paid', $params) && (boolean) $params['paid']) {
                //Only an unpaid invoice can be paid...
                $invoice = JModelLegacy::getInstance('Invoice', 'QuipuModel')->getItem($params['id']);
                if ($invoice->status != IW_ERP_ESTADO_FACTURA_COBRADA) {
                    $params['payment_date'] = IWDate::mySQLDate();
                    $params['status'] = IW_ERP_ESTADO_FACTURA_COBRADA;
                } else {
                    unset($params['paid']);
                }
                IWRequest::setVar($this->table, $params);
            }
        }
    }

    /**
     * 
     */
    protected function afterInvoicePOST() {
        $params = IWRequest::getVar($this->table);
        if (array_key_exists('paid', $params) && (boolean) $params['paid'] && $this->response_object->ok) {
            if (array_key_exists('payment-movements',$params) && is_array($params['payment-movements'])) {
                JModelLegacy::getInstance('BankActivity', 'QuipuModel')->assignInvoiceReference($params['payment-movements'], $params['id']);
            } elseif (array_key_exists ('create-movement', $params) && $params['create-movement']) {
                JModelLegacy::getInstance('BankActivity', 'QuipuModel')->createPaymentForInvoice($params['id']);
            }
        }
    }

    /**
     * 
     */
    protected function afterPurchaseorderPOST() {
        $params = IWRequest::getVar($this->table);
        if (array_key_exists('paid', $params) && (boolean) $params['paid'] && $this->response_object->ok) {
            if (is_array($params['payment-movements'])) {
                JModelLegacy::getInstance('BankActivity', 'QuipuModel')->assignPurchaseorderReference($params['payment-movements'], $params['id']);
            } elseif ($params['create-movement']) {
                JModelLegacy::getInstance('BankActivity', 'QuipuModel')->createPaymentForPurchaseorder($params['id']);
            }
        }
    }

}
