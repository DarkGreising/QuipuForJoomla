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
class QuipuViewPurchaseorders extends IWItemListView {

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

        $this->suppliers = $this->get('Suppliers');
        $this->statuses = $this->get('Statuses');

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
        JToolBarHelper::title(JText::_('COM_QUIPU_PURCHASE_ORDERS'));
        JToolBarHelper::custom('purchaseorders.printItems', $icon = 'print', $iconOver = '', $alt = JText::_('COM_QUIPU_PRINT'), $listSelect = true);

        if (QUIPU_IS_J3) {

            QuipuHelper::addSubmenu(IWRequest::getCmd('view', 'purchaseorders'));

            //IWRequest::setVar('hidemainmenu', true);
            JHtmlSidebar::setAction('index.php?option=com_quipu&view=purchaseorders');

            JHtmlSidebar::addFilter(
                    JText::_('COM_QUIPU_FILTER_BY_STATUS'), 'filter_estado', JHtml::_('select.options', $this->getStateOptions(), 'value', 'text', $this->state->get('filter.status'))
            );
            JHtmlSidebar::addFilter(
                    JText::_('COM_QUIPU_FILTER_BY_SUPPLIER'), 'filter_supplier', JHtml::_('select.options', $this->getSupplierOptions(), 'value', 'text', $this->state->get('filter.supplier'))
            );
            $this->sidebar = JHtmlSidebar::render();
        }
    }

    /**
     * 
     */
    private function getStateOptions() {
        $options = array();
        foreach ($this->statuses as $status) {
            $options[] = JHtml::_('select.option', $status, JText::_('COM_QUIPU_ORDER_STATUS_' . $status));
        }
        return $options;
    }
    /**
     * 
     */
    private function getSupplierOptions() {
        $options = array();
        foreach ($this->suppliers as $item) {
            $options[] = JHtml::_('select.option', $item->id, $item->name);
        }
        return $options;
    }

}