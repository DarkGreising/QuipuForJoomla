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
     * 
     * @param type $pk
     */
    public function getItem($pk = null){
        $item = parent::getItem($pk);
        //check that this user can see this item:
        $customer = JModelLegacy::getInstance('Customer', 'QuipuModel')->getCustomerForCurrentUser(false);
        
        if(is_object($item) && $item->customer_id == $customer->id){
            return $item;
        }
        else{
            JError::raiseError(401, JText::_('COM_QUIPU_ORDER_NOT_FOUND'));
        }
    }

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
