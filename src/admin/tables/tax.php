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
class QuipuTableTax extends IWTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db)
	{
		parent::__construct('#__quipu_tax', 'id', $db);
	}
        
        /**
         * 
         * @param type $factor
         */
        public function getTaxIdByFactor($factor){            
            $db = $this->getDbo();
            $db->setQuery('SELECT id FROM #__quipu_tax WHERE factor=' . $factor);            
            $id = $db->loadResult();
            return $id;
        }
        
}