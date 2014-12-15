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
class QuipuModelInvoices extends IWModelList {

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
     */
    public function getStates() {
        $db = $this->getDbo();
        $query = 'SELECT distinct status FROM #__quipu_invoice order by status';
        $db->setQuery($query);
        $l = $db->loadColumn();
        return $l;
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
        $app = JFactory::getApplication();

        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $cliente = $this->getUserStateFromRequest($this->context . '.filter.customer', 'filter_customer', null, 'int');
        $this->setState('filter.customer', $cliente);

        $status = $this->getUserStateFromRequest($this->context . '.filter.status', 'filter_status', null, 'int');
        $this->setState('filter.status', $status);

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

        //Anotar facturas vencidas
        $db->setQuery('UPDATE #__quipu_invoice SET status=' . IW_ERP_ESTADO_FACTURA_VENCIDA . ' WHERE status='.IW_ERP_ESTADO_FACTURA_PENDIENTE.' AND due_date < NOW()');
        $db->query();

        $query = $db->getQuery(true);
        // Select some fields
        $query->select('f.id,f.invoice_number,f.due_date,f.invoice_date,f.total,f.status,c.id as customer_id,c.name as customer_name');
        // From the table
        $query->from('#__quipu_invoice f');
        $query->innerJoin('#__quipu_customer c on f.customer_id=c.id');

        // Aplicar filtro de búsqueda
        if ($this->getState('filter.search') !== '') {
            //Escapar caracteres extraños:
            $token = $db->Quote('%' . $db->getEscaped($this->getState('filter.search')) . '%');
            //Construir condicion:
            $searches = array();
            $searches[] = 'c.name LIKE ' . $token;
            $searches[] = 'f.invoice_number LIKE ' . $token;
            $searches[] = 'f.status LIKE ' . $token;

            $query->where('(' . implode(' OR ', $searches) . ')');
        }
        $customer_id = $this->getState('filter.customer');
        if (is_numeric($customer_id) && $customer_id) {
            $query->where('c.id=' . $customer_id);
        }

        $status = $this->getState('filter.status');
        if (is_numeric($status) && $status) {
            $query->where('f.status=' . $status);
        }

        $query->order('f.id desc');

        //Fin filtro
        return $query;
    }
    
    /**
     * 
     */
    public function getInvoices($ids = array()){
        if(count($ids)>0){
            $db =JFactory::getDbo();
            $db->setQuery('SELECT * FROM #__quipu_invoice WHERE id IN (' . implode(',',$ids) . ')');
            return $db->loadObjectList();
        }
        return array();
    }
}