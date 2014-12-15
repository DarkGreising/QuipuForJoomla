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
class QuipuViewTaxes extends IWItemListView {

    /**
     * Quipu view display method
     * @return void
     */
    function display($tpl = null) {
        // Get data from the model
        $items = $this->get('Items');
        $pagination = $this->get('Pagination');

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
        JToolBarHelper::title(JText::_('COM_QUIPU_TAXES'));
        JToolBarHelper::addNew('tax.add');
        JToolBarHelper::deleteList('', 'taxes.delete');
        if (QUIPU_IS_J3) {

            QuipuHelper::addSubmenu(IWRequest::getCmd('view', 'taxes'));

            //IWRequest::setVar('hidemainmenu', true);
            JHtmlSidebar::setAction('index.php?option=com_quipu&view=taxes');

            $this->sidebar = JHtmlSidebar::render();
        }
    }

}
