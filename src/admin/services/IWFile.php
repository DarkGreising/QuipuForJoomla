<?php

/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file');
/**
 * 
 */
class IWFile extends JFile {

    /**
     * 
     * @param type $src
     * @param type $dest
     * @param type $use_streams
     * @return type
     */
    public static function upload($src, $dest, $use_streams = false) {
        $baseDir = dirname($dest);

        if (!file_exists($baseDir)) {
            jimport('joomla.filesystem.folder');
            JFolder::create($baseDir);            
        }
        self::checkSecurity($baseDir);
        
        return parent::upload($src, $dest, $use_streams);
    }
    
    /**
     * 
     * @param type $dir
     */
    public static function checkSecurity($dir){
        $index = $dir . DS . 'index.html';
        if(!file_exists($index)){
            $this_index = dirname(__FILE__) . DS . 'index.html';
            return self::copy($this_index, $index);
        }
        return true;
    }

}
