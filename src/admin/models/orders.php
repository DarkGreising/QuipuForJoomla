<?php
/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
require_once 'iwModelList.php';
/**
 * QuipuList Model
 */
class QuipuModelOrders extends IWModelList {

    /**
     *
     * @return <type>
     */
    public function getCustomers() {

        $db = $this->getDbo();
        $query = 'SELECT id,name FROM #__quipu_customer ORDER BY name';
        $db->setQuery($query);
        return $db->loadObjectList();

    }

    /**
     *
     * @return <type>
     */
    public function getStatuses() {

        $db = $this->getDbo();
        $query = 'SELECT DISTINCT status FROM #__quipu_order ORDER BY status';
        $db->setQuery($query);
        return $db->loadColumn();

    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @return	void
     * @since	0.12
     */
    protected function populateState($ordering = null, $direction = null) {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $cliente = $this->getUserStateFromRequest($this->context . '.filter.customer', 'filter_cliente', null, 'int');
        $this->setState('filter.customer', $cliente);

        $estado = $this->getUserStateFromRequest($this->context . '.filter.status', 'filter_estado', null, 'int');
        $this->setState('filter.status', $estado);



        parent::populateState();
    }
    /**
     * Method to build an SQL query to load the list data.
     *
     * @return	string	An SQL query
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        // Select some fields
        $query->select('p.id as id,p.order_date,p.order_number,p.customer_reference,p.status,c.name as customer_name, p.customer_id,p.total,p.customer_reference, p.total,GROUP_CONCAT(inv.id SEPARATOR " ") as invoice_id');
        // From the table
        $query->from('#__quipu_order p');
        $query->innerJoin('#__quipu_customer c on p.customer_id=c.id');
        $query->leftJoin(('#__quipu_invoice inv on inv.order_id = p.id'));
        $filter = $this->getState('filter.search');
        if (is_string($filter) && $filter !== '') {
            //Escapar caracteres extraños:
            $token = $db->Quote('%' . $db->getEscaped($this->getState('filter.search')) . '%');
            //Construir condicion:
            $searches = array();
            $searches[] = 'c.name LIKE ' . $token;
            $searches[] = 'p.order_number LIKE ' . $token;
            $searches[] = 'p.customer_reference LIKE ' . $token;

            $query->where('(' . implode(' OR ', $searches) . ')');
        }
        $customer_id = $this->getState('filter.customer');
        if (is_numeric($customer_id) && $customer_id) {
            $query->where('c.id=' . $customer_id);
        }
        $tipo_estado = $this->getState('filter.status');
        if ($tipo_estado) {
            $query->where('p.status=' . $tipo_estado);
        }
        
        $query->group('p.id');
        $query->order('id desc');
        
        //JFActory::getApplication()->enqueueMessage("$query");

        //Fin filtro
        return $query;
    }
    
    /**
     * 
     */
    public function getOrders($ids = array()){
        if(count($ids)>0){
            $db =JFactory::getDbo();
            $db->setQuery('SELECT * FROM #__quipu_order WHERE id IN (' . implode(',',$ids) . ')');
            return $db->loadObjectList();
        }
        return array();
    }    
}