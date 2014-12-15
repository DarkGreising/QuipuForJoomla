<?php
/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.database.table');

/**
 * Description of IWTable
 *
 * @author nacho
 */
class IWTable extends JTable{
    
    
    /**
     * 
     * @param type $props
     */
    public function findPK($props = array()){
        if(count($props)){
            $db = $this->getDbo();
            $where = array();
            foreach($props as $k=>$v){    
                if(is_numeric($v)){
                    $where[] = "$k=$v";
                }
                else{
                    $where[] = "$k='$v'";
                }
                
            }
            $query = $db->getQuery(true);
            $query->select($this->_tbl_key);
            $query->from($this->_tbl);
            $query->where($where);
            
            $query = "$query";
            
            $db->setQuery($query);
            $res = $db->loadResult();
            
            return $res;
        }
        return false;
    }

}