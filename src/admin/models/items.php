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
class QuipuModelItems extends IWModelList {

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

        $cliente = $this->getUserStateFromRequest($this->context . '.filter.itemcategory', 'filter_itemcategory', null, 'int');
        $this->setState('filter.itemcategory', $cliente);

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
        $query->select('i.*, c.name as category');
        // From the hello table
        $query->from('#__quipu_item i');
        $query->innerJoin('#__quipu_item_category c on i.category_id=c.id');

        $filter = $this->getState('filter.search');
        if (is_string($filter) && $filter !== '') {
            //Escapar caracteres extraños:
            $token = $db->Quote('%' . $db->getEscaped($this->getState('filter.search')) . '%');
            //Construir condicion:
            $searches = array();
            $searches[] = 'i.name LIKE ' . $token;

            $query->where('(' . implode(' OR ', $searches) . ')');
        }
        $customer_id = $this->getState('filter.itemcategory');
        if (is_numeric($customer_id) && $customer_id) {
            $query->where('c.id=' . $customer_id);
        }
        
        $query->order('i.name');
        
        return $query;
    }

    /**
     * 
     * @return type
     */
    public function getCategories() {
        $db = $this->getDbo();
        $query = 'SELECT id,name FROM #__quipu_item_category ORDER BY name';
        $db->setQuery($query);
        return $db->loadObjectList();
    }

}