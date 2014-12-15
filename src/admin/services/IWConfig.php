<?php
defined('_JEXEC') or die('Restricted access');

/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */

class IWConfig {

    private static $_instances = array();

    /**
     * 
     */
    public static function getInstance($id = false) {
        $token = "item_$id";
        if (!isset(self::$_instances[$token])) {
            $db = JFactory::getDbo();
            $query = 'SELECT * FROM #__quipu_config';
            if ($id) {
                $query.=" WHERE id=$id";
            }
            $db->setQuery($query);
            $res = $db->loadObject();
            
            $token = "item_$res->id";
            self::$_instances[$token] = $res;
        }
        return self::$_instances[$token];
    }
    
    /**
     * 
     * @param type $key
     */
    public static function getJConfigValue($key){
        $config = JFactory::getConfig();
        if(QUIPU_IS_J3){
            return $config->get($key);
        }
        else{
            return $config->getValue('config.' . $key);
        }
    }

}
