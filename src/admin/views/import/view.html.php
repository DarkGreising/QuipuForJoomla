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
 * 
 */
class QuipuViewImport extends JViewLegacy{
    
    /**
     *
     * @param type $tpl 
     */
    public function display($tpl = null) {
        $app = JFactory::getApplication();
        $this->table = IWRequest::getWord('t',false);

        if(!$this->table){
            JError::raiseError(500, JTExt::_('COM_QUIPU_PARAMETERS_MISSING'));
        }        
        
        $this->result = JFactory::getApplication()->getUserState('import.csv.result');
        JFactory::getApplication()->setUserState('import.csv.result', null);
        
        $this->templateDir = JURI::base() . 'templates/' . $app->getTemplate();
        
        parent::display($tpl);
    }

}