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
class QuipuModelBankAccounts extends IWModelList {

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
        $query->select('*');
        // From the hello table
        $query->from('#__quipu_bank_account');

        // Aplicar filtro de búsqueda
        if ($this->getState('filter.search') !== '') {
            //Escapar caracteres extraños:
            $token = $db->Quote('%' . $db->getEscaped($this->getState('filter.search')) . '%');
            //Construir condicion:
            $searches = array();
            $searches[] = 'name LIKE ' . $token;

            $query->where('(' . implode(' OR ', $searches) . ')');
        }
        $query->order('name');
        
        //Fin filtro
        return $query;
    }
}