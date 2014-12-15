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
class QuipuModelCustomer extends JModelAdmin {

    
    /**
     * 
     * @param type $form
     * @param type $data
     * @param type $group
     * @return type
     */
    public function validate($form, $data, $group = null)
    {
        $ok = parent::validate($form, $data, $group);
        if ($ok)
        {
            $uid = $data['user_id'];
            if (is_numeric($uid))
            {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('id');
                $query->from('#__quipu_customer');
                $query->where('user_id=' . $uid);
                if (is_numeric($data['id']))
                {
                    $query->where('id != ' . $data['id']);
                } 
                $db->setQuery($query);
                $res = $db->loadResult();
                if($res){
                    JFactory::getApplication()->enqueueMessage(JText::_('COM_QUIPU_USER_ALREADY_ASSIGNED'),'error');
                    $ok = false;
                }
            }
        }
        return $ok;
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
    public function getTable($type = 'Customer', $prefix = 'QuipuTable', $config = array()) {
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
        $form = $this->loadForm('com_quipu.customer', 'customer', array('control' => 'jform', 'load_data' => $loadData));
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
        $data = JFactory::getApplication()->getUserState('com_quipu.edit.customer.data', array());
        if (empty($data)) {
            $data = $this->getItem();
        }
        return $data;
    }
    
    /**
     * 
     */
    public function getProfitability($id = false){
        if(!$id){
            $id = $this->getItem()->id;
        }
        $sql = "
            select count(t.id) as orders, max(profit) as max_profit, min(profit) as min_profit, avg(profit) as avg_profit, sum(profit) as total_profit
            from(
            select o.id as id, o.total as total, sum(i.cost) as cost, (o.total - sum(i.cost)) as profit
            from #__quipu_detail_item i
            inner join #__quipu_order o on i.order_id=o.id
            where 
            o.status=9001 and
            o.customer_id=$id
            group by i.order_id) as t
            ";
        $db = $this->getDbo();
        $db->setQuery($sql);
        return $db->loadObject();
    }
}
