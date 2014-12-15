<?php
defined('_JEXEC') or die('Restricted access');


/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */
class IWSequences {

    /**
     *
     * @param type $secuencia 
     */
    public static function nextValue($seqName = 'invoice') {
        $num = self::getCurrentValue($seqName);

        $year = (int) date('Y');
        if (!is_numeric($num)) {
            $num = $year . '00001';
        }

        $next = 1 + $num;
        self::updateSequence($seqName, $next);

        return $num;
    }
    
    /**
     * 
     * @param type $seqName
     */
    public static function getCurrentValue($seqName){
        $db = JFactory::getDbo();
        $db->setQuery("SELECT next_value FROM #__quipu_sequence WHERE name='$seqName' ORDER BY id desc LIMIT 0,1");
        $num = $db->loadResult();
        
        return $num;
    }

    /**
     * 
     * @param type $seqName
     * @param type $seqValue
     */
    public static function updateSequence($seqName, $seqValue) {
        if (is_string($seqName) && is_numeric($seqValue)) {                        
            $db = JFactory::getDbo();
            $num = self::getCurrentValue($seqName);
            if($num){
                $db->setQuery("UPDATE #__quipu_sequence SET next_value='$seqValue' WHERE name='$seqName';");
            }
            else{
                $db->setQuery("INSERT INTO #__quipu_sequence (name,next_value) VALUES ('$seqName','$seqValue');");
            }            
            return $db->query();
        }
        return false;
    }

}

