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
class QuipuTableBankActivity extends IWTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db)
	{
		parent::__construct('#__quipu_bank_activity', 'id', $db);
	}
        /**
         * 
         * @param type $propertyArray
         */
        public function activityIsRegistered($propertyArray){
            $pk = $this->findPK($propertyArray);
            $is_registered = is_numeric($pk) && $pk > 0;
            
            return $is_registered;
        }
}
