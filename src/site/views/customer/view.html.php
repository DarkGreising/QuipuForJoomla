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
 * Quipu View
 */
class QuipuViewCustomer extends JViewLegacy {

    /**
     * display method of Quipu view
     * @return void
     */
    public function display($tpl = null) {
        
        // get the Data
        $this->model = $this->getModel();
        $this->customer = $this->model->getCustomerForCurrentUser();
        
        
        if(!$this->customer){
            JFactory::getApplication()->redirect(JRoute::_('/index.php'));
        }
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        // Display the template
        parent::display($tpl);
    }

}