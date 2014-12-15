<?php

/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');
require_once 'iwcontroller.php';

/**
 * Quipu Controller
 */
class QuipuControllerOrder extends IWController {

    /**
     * 
     */
    public function cancelOrder() {
        $id = IWRequest::getInt('id');
        $option = IWRequest::getCmd('option');
        if ($id) {
            $model = $this->getModel();
            $data = array();
            $data['id'] = $id;
            $data['status'] = IW_ERP_ESTADO_PEDIDO_CANCELADO;
            if ($model->save($data)) {
                JFactory::getApplication()->enqueueMessage(JText::_('COM_QUIPU_ORDER_CANCELLED'));
                $this->setRedirect(JRoute::_("index.php?option=$option&view=orders", false));
            } else {
                JFactory::getApplication()->enqueueMessage($model->getError());
                $this->setRedirect(JRoute::_("index.php?option=$option&task=order.edit&id=$id", false));
            }
        }
    }

    /**
     * 
     */
    public function invoice() {
        $id = IWRequest::getInt('id');
        $option = IWRequest::getCmd('option');
        if ($id) {
            $model = $this->getModel();

            $invoce_id = $model->invoice($id);
            if ($invoce_id) {
                $data = array();
                $data['id'] = $id;
                $data['status'] = IW_ERP_ESTADO_PEDIDO_FACTURADO;
                $data['invoice_id'] = $invoce_id;

                if ($model->save($data)) {
                    JFactory::getApplication()->enqueueMessage(JText::_('COM_QUIPU_ORDER_INVOICE_GENERATED'));
                    $this->setRedirect(JRoute::_("index.php?option=$option&task=invoice.edit&id=$invoce_id", false));
                } else {
                    JFactory::getApplication()->enqueueMessage($model->getError(), 'error');
                    $this->setRedirect(JRoute::_("index.php?option=$option&task=order.edit&id=$id", false));
                }
            } else {
                JFactory::getApplication()->enqueueMessage($model->getError(), 'error');
                $this->setRedirect(JRoute::_("index.php?option=$option&task=order.edit&id=$id", false));
            }
        }
    }

    /**
     *
     * @return boolean 
     */
    public function add() {
        // Initialise variables.
        $app = JFactory::getApplication();
        $context = "$this->option.edit.$this->context";

        // Access check.
        if (!$this->allowAdd()) {
            // Set the internal error and also the redirect error.
            $this->setError(JText::_('COM_QUIPU_JLIB_APPLICATION_ERROR_CREATE_RECORD_NOT_PERMITTED'));
            $this->setMessage($this->getError(), 'error');

            $this->setRedirect(
                    JRoute::_(
                            'index.php?option=' . $this->option . '&view=' . $this->view_list
                            . $this->getRedirectToListAppend(), false
                    )
            );

            return false;
        }

        // Clear the record edit information from the session.
        $app->setUserState($context . '.data', null);

        $model = $this->getModel();
        $data = array();
        $data['order_number'] = IWSequences::nextValue('order');
        $data['order_date'] = JDate::getInstance()->toSql();
        $data['customer_id'] = IWRequest::getInt('c',0);
        
        if ($model->save($data)) {            
            $id = $model->getState($model->getName().'.id');
            $url = 'index.php?option=' . $this->option . '&task=order.edit&id=' . $id;
            $this->setRedirect(JRoute::_($url . $this->getRedirectToItemAppend(), false));
        } else {
            JFactory::getApplication()->enqueueMessage($model->getError());            
            $url = 'index.php?option=' . $this->option . '&view=' . $this->view_list;
            $this->setRedirect(JRoute::_($url. $this->getRedirectToListAppend(), false));
        }


        return true;
    }

}
