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
class QuipuViewCustomer extends IWItemEditView {

    /**
     * display method of Quipu view
     * @return void
     */
    public function display($tpl = null) {
        // get the Data
        $form = $this->get('Form');
        $item = $this->get('Item');
        $this->form = $form;
        $this->item = $item;
        $isNew = ($this->item->id == 0);

        if (!$isNew) {
            $profitability = $this->get('Profitability');
            $this->profitability = $profitability;
        }

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        // Set the toolbar
        $this->addToolBar();

        // Display the template
        parent::display($tpl);
    }

    /**
     * Setting the toolbar
     */
    protected function addToolBar() {
        IWRequest::setVar('hidemainmenu', true);
        $isNew = ($this->item->id == 0);
        JToolBarHelper::title($isNew ? JText::_('COM_QUIPU_NEW_CUSTOMER') : JText::_('COM_QUIPU_EDIT'));
        JToolBarHelper::save('customer.save');
        JToolBarHelper::save2new('customer.save2new');
        if (!$isNew) {
            JToolBarHelper::save2copy('customer.save2copy');
        }
        JToolBarHelper::apply('customer.apply');
        JToolBarHelper::cancel('customer.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
    }

}