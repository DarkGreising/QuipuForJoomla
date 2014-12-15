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
class QuipuModelInvoice extends JModelAdmin {

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     * @since	1.7
     */
    public function getTable($type = 'Invoice', $prefix = 'QuipuTable', $config = array()) {
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
        $form = $this->loadForm('com_quipu.invoice', 'invoice', array('control' => 'jform', 'load_data' => $loadData));
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
        $data = JFactory::getApplication()->getUserState('com_quipu.edit.invoice.data', array());
        if (empty($data)) {
            $data = $this->getItem();
        }
        return $data;
    }

    /**
     * 
     * @param type $pk
     */
    public function getDetail($invoice_id = null) {
        $item = $this->getItem($invoice_id);
        $table = $this->getTable();
        $detail = $table->getDetail($item->id);

        return $detail;
    }

    /**
     * 
     * @param type $invoice_id
     * @param type $state
     */
    public function setInvoiceState($invoice_id = false, $state = false) {
        if ($invoice_id && $state) {
            $db = $this->getDbo();
            $db->setQuery('UPDATE #__quipu_invoice SET status="' . $state . '" WHERE id=' . $invoice_id);
            return $db->query();
        }
        return false;
    }

    /**
     * 
     * @param type $invoice_id
     */
    public function generateRefund($invoice_id = null) {
        $invoice = $this->getItem($invoice_id);
        if ($invoice) {
            $this->getDbo()->transactionStart();
            $props = $invoice->getProperties();
            $table = $this->getTable();
            $itemTable = $this->getTable('DetailItem');

            unset($props[$table->getKeyName()]);
            $table->setProperties($props);            
            $table->invoice_number = 'R-' . IWSequences::nextValue('refund');
            $table->rectification_of_number = $invoice->invoice_number;
            $table->status = IW_ERP_ESTADO_FACTURA_REEMBOLSO;
            
            if ($table->store()) {
                $refund_id = $table->id;
                $items = $this->getDetail($invoice_id);
                $k = $itemTable->getKeyName();
                $ignore = array($k,'item');
                foreach ($items as $theItem) {
                    $itemTable->reset();
                    $itemTable->$k = 0;
                    $props = $theItem->getProperties();                    
                    $props['units'] = -1 * (int)$props['units'];
                    $props['invoice_id'] = $refund_id;
                    $itemTable->bind($props,$ignore);                    
                    if(!$itemTable->store()){
                        $this->getDbo()->transactionRollback();
                        JFactory::getApplication()->enqueueMessage($itemTable->getError(), 'error');
                        return false;
                    }
                }
                $table->store();
                $this->setInvoiceState($invoice_id, IW_ERP_ESTADO_FACTURA_REEMBOLSADA);
                $this->getDbo()->transactionCommit();
                JFactory::getApplication()->enqueueMessage(JText::_('COM_QUIPU_INVOICE_REFUNDED_OK'));
                return $refund_id;
            } else {
                JFactory::getApplication()->enqueueMessage($table->getError(), 'error');
                return false;
            }
        } else {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_QUIPU_INVOICE_NOT_FOUND'), 'error');
            return false;
        }
    }

}
