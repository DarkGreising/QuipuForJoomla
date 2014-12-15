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
class QuipuViewItemCategory extends IWItemEditView {

    /**
     * display method of Quipu view
     * @return void
     */
    public function display($tpl = null) {
        // get the Data
        $form = $this->get('Form');
        $item = $this->get('Item');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        // Assign the Data
        $this->form = $form;
        $this->item = $item;

        // Display the template
        parent::display($tpl);
        // Set the toolbar
        $this->addToolBar();
    }

    /**
     * Setting the toolbar
     */
    protected function addToolBar() {
        IWRequest::setVar('hidemainmenu', true);
        $isNew = ($this->item->id == 0);
        JToolBarHelper::title($isNew ? JText::_('COM_QUIPU_ITEM_CATEGORY_NEW') : JText::_('COM_QUIPU_ITEM_CATEGORY_EDIT'));
        JToolBarHelper::save('itemcategory.save');
        JToolBarHelper::save2new('itemcategory.save2new');
        if (!$isNew) {
            JToolBarHelper::save2copy('itemcategory.save2copy');
        }

        JToolBarHelper::apply('itemcategory.apply');
        JToolBarHelper::cancel('itemcategory.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
    }

}