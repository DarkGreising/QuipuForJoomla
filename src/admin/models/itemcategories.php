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
class QuipuModelItemCategories extends IWModelList {

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
        $query->from('#__quipu_item_category');
        $query->order('name');
        return $query;
    }

}