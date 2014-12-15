<?php
/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access');

require_once 'iwtable.php';
/**
 * Quipu Table class
 */
class QuipuTableItemCategory extends IWTable {

    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__quipu_item_category', 'id', $db);
    }

    /**
     * 
     * @param type $name
     */
    public function getCategoryIdByName($name) {
        $db = $this->getDbo();
        $db->setQuery('SELECT id FROM #__quipu_item_category WHERE name="' . $name . '"');
        $id = $db->loadResult();
        return $id;
    }

}
