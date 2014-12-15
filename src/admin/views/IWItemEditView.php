<?php

/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * Master Item Edit View
 */
abstract class IWItemEditView extends JViewLegacy {

    /**
     * 
     * @param type $config
     */
    public function __construct($config = array()) {
        parent::__construct($config);
        $this->_addPath('template', JPATH_COMPONENT_ADMINISTRATOR . DS . 'views' . DS . 'common');

        /**
         * If we are in Joomla! 2.5, load templates from a sspecific folder.
         */
        if (QUIPU_IS_J2) {
            $def = realpath($this->_basePath . DS . 'views' . DS . $this->getName() . DS . 'tmpl');
            foreach ($this->_path['template'] as $k => $path) {
                $rp = realpath($path);
                if ($rp == $def ) {
                    $this->_path['template'][$k] = $def . '_j25' . DS;
                    break;
                }
            }
        }
    }

}