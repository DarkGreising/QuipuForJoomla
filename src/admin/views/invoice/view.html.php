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
class QuipuViewInvoice extends IWItemEditView {

    /**
     * display method of Quipu view
     * @return void
     */
    public function display($tpl = null) {
        // get the Data
        $form = $this->get('Form');
        $item = $this->get('Item');
        
        if(!$item || !$item->id){
            JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_quipu&view=invoices', false));
        }
        
        $doc = JFactory::getDocument();
        if(QUIPU_IS_J3){
            JHtml::_('bootstrap.framework');
            $doc->addScript(JURI::root() . 'administrator/components/com_quipu/assets/js/invoice_j3.js');
        }
        else{
            $doc->addScript(JURI::root() . 'administrator/components/com_quipu/assets/js/invoice.js');
        }
        
        $doc->addStylesheet(JURI::root() . 'administrator/components/com_quipu/assets/css/dyntable.css');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        // Assign the Data
        $this->form = $form;
        $this->item = $item;

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
        JToolBarHelper::title($isNew ? JText::_('COM_QUIPU_INVOICE') : JText::sprintf('COM_QUIPU_EDIT_INVOICE', $this->item->invoice_number, JText::_('COM_QUIPU_INVOICE_STATUS_' . $this->item->status)));
      
        JToolBarHelper::save('invoice.save');
        JToolBarHelper::apply('invoice.apply');        
        JToolBarHelper::cancel('invoice.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
    }

}