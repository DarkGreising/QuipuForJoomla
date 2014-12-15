<?php

/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

/**
 * Quipu Model
 */
class QuipuModelBankActivity extends JModelAdmin {

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     * @since	1.7
     */
    public function getTable($type = 'BankActivity', $prefix = 'QuipuTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form.
     *
     * @param	array	$data		Data for the form.
     * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
     * @return	mixed	A JForm object on success, false on failure
     * @since	1.7
     */
    public function getForm($data = array(), $loadData = true) {
        // Get the form.
        $form = $this->loadForm('com_quipu.bankactivity', 'bankactivity', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }
        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return	mixed	The data for the form.
     * @since	1.7
     */
    protected function loadFormData() {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_quipu.edit.bankactivity.data', array());
        if (empty($data)) {
            $data = $this->getItem();
        }
        return $data;
    }
        
    /**
     * 
     * @param type $activity_ids
     * @param type $purchaseorder_id
     */
    public function assignPurchaseorderReference($activity_ids=false, $purchaseorder_id=false){
        if(is_array($activity_ids) && $purchaseorder_id){
            $db = JFactory::getDbo();
            foreach($activity_ids as $aid){
                $sql = "INSERT INTO #__quipu_purchaseorder_bank_activity_xref (purchaseorder_id, activity_id) VALUES ($purchaseorder_id,$aid)";
                $db->setQuery($sql);
                $db->query();
            }
        }
    }
    /**
     * 
     * @param type $activity_ids
     * @param type $invoice_id
     */
    public function assignInvoiceReference($activity_ids=false, $invoice_id=false){
        if(is_array($activity_ids) && $invoice_id){
            $db = JFactory::getDbo();
            foreach($activity_ids as $aid){
                $sql = "INSERT INTO #__quipu_invoice_bank_activity_xref (invoice_id, activity_id) VALUES ($invoice_id,$aid)";
                $db->setQuery($sql);
                $db->query();
            }
        }
    }

    /**
     * 
     * @param type $invoice_id
     */
    public function createPaymentForInvoice($invoice_id = false){
        if($invoice_id){
            $invoice = JModelLegacy::getInstance('Invoice','QuipuModel')->getItem($invoice_id);
            $account = JModelLegacy::getInstance('BankAccount','QuipuModel')->getItem($invoice->bankaccount_id);
            $t = $this->getTable();
            $t->reset();
            
            $t->bank_account_id = $account->id;
            $t->activity_date = IWDate::mySQLDate();
            $t->value_date = IWDate::mySQLDate();
            $t->description = JText::sprintf('COM_QUIPU_PAYMENT_FOR_INVOICE',$invoice->invoice_number);
            $t->amount = $invoice->total;
            $t->balance = $account->balance + $invoice->total;
            $t->ref = $invoice->invoice_number;
            
            if($t->store()){
                $this->assignInvoiceReference(array($t->id),$invoice_id);
                return true;
            }
            else{
                return false;
            }
            
        }
    }
    /**
     * 
     * @param type $invoice_id
     */
    public function createPaymentForPurchaseorder($purchaseorder_id = false){
        if($purchaseorder_id){
            $purchase = JModelLegacy::getInstance('Purchaseorder','QuipuModel')->getItem($purchaseorder_id);
            $account = JModelLegacy::getInstance('BankAccount','QuipuModel')->getItem($purchase->bankaccount_id);
            $t = $this->getTable();
            $t->reset();
            
            $t->bank_account_id = $account->id;
            $t->activity_date = IWDate::mySQLDate();
            $t->value_date = IWDate::mySQLDate();
            $t->description = JText::sprintf('COM_QUIPU_PAYMENT_FOR_PURCHASEORDER',$purchase->order_number);
            $t->amount = $purchase->total;
            $t->balance = $account->balance + $purchase->total;
            $t->ref = $purchase->order_number;
            
            if($t->store()){
                $this->assignPurchaseorderReference(array($t->id),$purchaseorder_id);
                return true;
            }
            else{
                return false;
            }            
        }
    }
}
