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
class QuipuTableBankAccount extends IWTable {

    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__quipu_bank_account', 'id', $db);
    }

    /**
     * 
     * @param type $name
     * @return type
     */
    public function getBankIdByName($name) {
        $db = $this->getDbo();
        $db->setQuery('SELECT id FROM #__quipu_bank_account WHERE name="' . $name . '"');
        $id = $db->loadResult();
        return $id;
    }
    
    
    /**
     * Each time the bank account object is retrieved, we reload the balance from
     * the activities registered.
     * 
     * @param type $keys
     * @param type $reset
     */
    public function load($keys = null, $reset = true){
        if(parent::load($keys, $reset)){
            //Calculate balance:
            $pk = $this->getKeyName();
            $sql = 'SELECT balance FROM #__quipu_bank_activity WHERE bank_account_id=' . $this->$pk . ' ORDER BY id DESC LIMIT 0,1';
            $db = $this->getDbo();
            $db->setQuery($sql);
            $this->balance = $db->loadResult();
            $this->store();
            return true;
        }
        return false;
    }

}