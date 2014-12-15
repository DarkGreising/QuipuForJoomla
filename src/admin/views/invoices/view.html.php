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
class QuipuViewInvoices extends IWItemListView {

    /**
     * Quipu view display method
     * @return void
     */
    function display($tpl = null) {
        // Get data from the model
        $items = $this->get('Items');
        $pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        $this->params = $this->state->get('params');

        $this->customers = $this->get('Customers');
        $this->states = $this->get('States');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        // Assign data to the view
        $this->items = $items;
        $this->pagination = $pagination;

        // Set the toolbar
        $this->addToolBar();

        // Display the template
        parent::display($tpl);
    }

    /**
     * Setting the toolbar
     */
    protected function addToolBar() {
        JToolBarHelper::title(JText::_('COM_QUIPU_INVOICES'));
        JToolBarHelper::custom('invoices.printItems', $icon = 'print', $iconOver = '', $alt = JText::_('COM_QUIPU_PRINT'), $listSelect = true);

        if (QUIPU_IS_J3) {

            QuipuHelper::addSubmenu(IWRequest::getCmd('view', 'orders'));

            //IWRequest::setVar('hidemainmenu', true);
            JHtmlSidebar::setAction('index.php?option=com_quipu&view=orders');

            JHtmlSidebar::addFilter(
                    JText::_('COM_QUIPU_FILTER_BY_STATUS'), 'filter_status', JHtml::_('select.options', $this->getStateOptions(), 'value', 'text', $this->state->get('filter.status'))
            );
            JHtmlSidebar::addFilter(
                    JText::_('COM_QUIPU_FILTER_BY_CUSTOMER'), 'filter_customer', JHtml::_('select.options', $this->getCustomerOptions(), 'value', 'text', $this->state->get('filter.customer'))
            );
            $this->sidebar = JHtmlSidebar::render();
        }
    }

    /**
     * 
     */
    private function getStateOptions() {
        $options = array();
        foreach ($this->states as $status) {
            $options[] = JHtml::_('select.option', $status, JText::_('COM_QUIPU_INVOICE_STATUS_' . $status));
        }
        return $options;
    }

    /**
     * 
     */
    private function getCustomerOptions() {
        $options = array();
        foreach ($this->customers as $customer) {
            $options[] = JHtml::_('select.option', $customer->id, $customer->name);
        }
        return $options;
    }

}
