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
class QuipuModelOrder extends JModelAdmin {

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     * @since	1.7
     */
    public function getTable($type = 'Order', $prefix = 'QuipuTable', $config = array()) {
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
        $form = $this->loadForm('com_quipu.order', 'order', array('control' => 'jform', 'load_data' => $loadData));
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
        $data = JFactory::getApplication()->getUserState('com_quipu.edit.order.data', array());
        if (empty($data)) {
            $data = $this->getItem();
        }
        return $data;
    }

    /**
     *
     * @param type $id 
     */
    public function invoice($id) {
        $order = $this->getTable();
        $invoice = $this->getTable('invoice');
        $customer = $this->getTable('customer');

        if ($order->load($id)) {

            $rows = $this->getDetail($id);
            if (count($rows)) {
                $customer->load($order->customer_id);

                $invoice->invoice_number = IWSequences::nextValue('invoice');
                $fecha = new IWDate();
                $invoice->invoice_date = $fecha->toSql();
                $default_due_days = $customer->default_due_days;
                $fecha->modify("+ $default_due_days day");
                $invoice->due_date = $fecha->toSql();

                $invoice->order_id = $id;
                $invoice->customer_id = $order->customer_id;

                $invoice->delivery = $order->delivery;

                $invoice->customer = $customer->name;
                $invoice->vatno = $customer->vatno;
                $invoice->address = $customer->address;
                $invoice->phone = $customer->phone;
                $invoice->email = $customer->email;
                $invoice->payment_method = $customer->payment_method;

                $invoice->customer_reference = $order->customer_reference;
                $invoice->memo = $order->memo;
                $invoice->status = IW_ERP_ESTADO_FACTURA_PENDIENTE;
                $invoice->delivery = $order->delivery;

                if ($invoice->store()) {
                    $invoice_id = $invoice->id;
                    $db = JFactory::getDbo();
                    $db->setQuery("UPDATE #__quipu_detail_item SET invoice_id=$invoice_id WHERE order_id=$id");
                    if ($db->query()) {
                        $order->invoice_id = $invoice_id;
                        $order->store();
                        //save it again to calculate totals:
                        $invoice->store();
                        return $invoice_id;
                    } else {
                        $this->setError($db->getErrorMsg());
                        return false;
                    }
                } else {
                    $this->setError($invoice->getError());
                    return false;
                }
            } else {
                $this->setError(JText::_('COM_QUIPU_ERROR_INVOICE_NOROWS'));
                return false;
            }
        } else {
            $this->setError($order->getError());
            return false;
        }
    }

    /**
     * 
     * @param type $pk
     */
    public function getDetail($order_id = null) {
        $item = $this->getItem($order_id);
        $table = $this->getTable();
        $detail = $table->getDetail($item->id);

        return $detail;
    }

    /**
     * 
     * @param type $order_id
     */
    public function getCustomer($order_id = null) {
        $item = $this->getItem($order_id);
        $customer = JTable::getInstance('customer', 'QuipuTable');
        $customer->load($item->customer_id);

        return $customer;
    }

}
