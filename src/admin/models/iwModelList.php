<?php
/**
 * @copyright   Nacho Brito
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
require_once 'iwModelList.php';
/**
 * QuipuList Model
 */
class IWModelList extends JModelList {
    private $ignorePagination = false;



    /**
     *
     * @param type $value
     */
    public function ignorePagination($value=false) {
        $this->ignorePagination = $value;
    }

    /**
     *
     */
    public function getItems(){
        /*
        When ignorePagination is set to true, we erase pagination
        related info from this model object, only for this query.
        */
        if ($this->ignorePagination) {
            $start = $this->getState('list.start');
            $limit = $this->getState('list.limit');

            $this->setState('list.start', 0);
            $this->setState('list.limit', 0);
        }

        $items = parent::getItems();

        if ($this->ignorePagination) {
            $this->setState('list.start', $start);
            $this->setState('list.limit', $limit);
        }

        return $items;
    }
}