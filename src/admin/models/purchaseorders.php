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
class QuipuModelPurchaseorders extends IWModelList {

    /**
     *
     * @return <type>
     */
    public function getSuppliers() {

        $db = $this->getDbo();
        $query = 'SELECT id,name FROM #__quipu_supplier ORDER BY name';
        $db->setQuery($query);
        return $db->loadObjectList();

    }

    /**
     *
     * @return <type>
     */
    public function getStatuses() {

        $db = $this->getDbo();
        $query = 'SELECT DISTINCT status FROM #__quipu_purchase_order ORDER BY status';
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

        $cliente = $this->getUserStateFromRequest($this->context . '.filter.supplier', 'filter_supplier', null, 'int');
        $this->setState('filter.supplier', $cliente);

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
        $query->select('p.id as id,p.order_number,p.order_date,p.status,c.name as supplier_name, p.supplier_id,p.total,p.supplier_reference, p.total');
        // From the table
        $query->from('#__quipu_purchase_order p');
        $query->innerJoin('#__quipu_supplier c on p.supplier_id=c.id');
        
        $filter = $this->getState('filter.search');
        if (is_string($filter) && $filter !== '') {
            //Escapar caracteres extraños:
            $token = $db->Quote('%' . $db->getEscaped($this->getState('filter.search')) . '%');
            //Construir condicion:
            $searches = array();
            $searches[] = 'c.name LIKE ' . $token;
            $searches[] = 'p.order_number LIKE ' . $token;
            $searches[] = 'p.supplier_reference LIKE ' . $token;

            $query->where('(' . implode(' OR ', $searches) . ')');
        }
        $customer_id = $this->getState('filter.supplier');
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
    public function getPurchaseorders($ids = array()){
        if(count($ids)>0){
            $db =JFactory::getDbo();
            $db->setQuery('SELECT * FROM #__quipu_purchase_order WHERE id IN (' . implode(',',$ids) . ')');
            return $db->loadObjectList();
        }
        return array();
    }    
}