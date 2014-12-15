<?php
defined('_JEXEC') or die('Restricted access');

/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */

jimport('joomla.environment.browser');

class IWBrowser extends JBrowser {

    
    /**
     * 
     */
    public static function isIE7orIE8() {
        $browser = &JBrowser::getInstance();
        $browserType = $browser->getBrowser();
        $browserVersion = $browser->getMajor();
        if($browserType == 'msie'){
            return $browserVersion == 7 || $browserVersion == 8;
        }
        return false;
    }

}

