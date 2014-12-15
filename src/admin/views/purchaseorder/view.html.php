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
class QuipuViewPurchaseorder extends IWItemEditView {

    /**
     * display method of Quipu view
     * @return void
     */
    public function display($tpl = null) {
        // get the Data
        $form = $this->get('Form');
        $item = $this->get('Item');
        $details = $this->get('Details');
        $customer = $this->get('Supplier');

        $doc = JFactory::getDocument();        
        $doc->addStylesheet('/administrator/components/com_quipu/assets/css/dyntable.css');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        if(!$item->id){
            JError::raiseError(404, JText::_('COM_QUIPU_ORDER_NOT_FOUND'));
            return false;            
        }        
        // Assign the Data
        $this->form = $form;
        $this->item = $item;
        $this->details = $details;
        $this->customer = $customer;                
        
        if (!$this->item->order_date) {
            $this->form->setValue('order_date', null, JDate::getInstance());
        }
        if (!$this->item->status) {
            $this->form->setValue('status', null, IW_ERP_ESTADO_PEDIDO_PENDIENTE);
            $this->is_cancelled = false;
        } else {
            $this->is_cancelled = $this->item->status == IW_ERP_ESTADO_PEDIDO_CANCELADO;
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
        JToolBarHelper::title($isNew ? JText::_('COM_QUIPU_REGISTER_PURCHASE_ORDER') : JText::sprintf('COM_QUIPU_EDIT_PURCHASE_ORDER', $this->item->order_number, JText::_('COM_QUIPU_ORDER_STATUS_' . $this->item->status)));

        if (!$this->is_cancelled) {
            JToolBarHelper::apply('purchaseorder.apply');
            JToolBarHelper::save('purchaseorder.save');
        }
        JToolBarHelper::cancel('purchaseorder.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
    }

}