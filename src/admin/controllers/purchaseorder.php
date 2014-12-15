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
class QuipuControllerPurchaseorder extends IWController
{

    /**
     * 
     */
    public function cancelOrder()
    {
        $id = IWRequest::getInt('id');
        $option = IWRequest::getCmd('option');
        if ($id)
        {
            $model = $this->getModel();
            $data = array();
            $data['id'] = $id;
            $data['status'] = IW_ERP_ESTADO_PEDIDO_CANCELADO;
            if ($model->save($data))
            {
                JFactory::getApplication()->enqueueMessage(JText::_('COM_QUIPU_ORDER_CANCELLED'));
                $this->setRedirect(JRoute::_("index.php?option=$option&view=purchaseorders", false));
            } else
            {
                JFactory::getApplication()->enqueueMessage($model->getError());
                $this->setRedirect(JRoute::_("index.php?option=$option&task=purchaseorder.edit&id=$id", false));
            }
        }
    }

    /**
     * 
     * @param type $key
     * @param type $urlVar
     * @return type
     */
    public function save($key = null, $urlVar = null)
    {
        $file = IWRequest::getVar('jform', null, 'files', 'array');
        $data = IWRequest::getVar('jform', array(), 'post', 'array');

        if (array_key_exists('invoice_file', $file))
        {
            $invoice = $file['invoice_file'];
            $app = JFactory::getApplication();
            $filename = IWFile::makeSafe($invoice['name']);
            $src = $invoice['tmp_name'];
            $ext = strtolower(IWFile::getExt($filename));
            $id = $data['id'];
            $path = 'data/PO/' . IWUtils::validFolderName(md5("$id")) . "/INVOICE.$ext";

            $dest = JPATH_COMPONENT . DS . $path;

            if (IWFile::upload($src, $dest))
            {
                if ($data['status'] == IW_ERP_ESTADO_PEDIDO_PENDIENTE)
                {
                    $data['status'] = IW_ERP_ESTADO_PEDIDO_FACTURADO;
                }
                $data['invoice_file'] = $path;
                if ($this->input)
                {
                    $this->input->post->set('jform', $data);
                } else
                {
                    IWRequest::setVar('jform', $data, 'post', true);
                }
                $result = parent::save($key, $urlVar);
            } else
            {
                $return = false;
                $app->enqueueMessage(JText::_('COM_QUIPU_SUPPLIER_INVOICE_UPLOAD_ERROR'), 'error');
            }
        } else
        {
            $result = parent::save($key, $urlVar);
        }

        return $result;
    }

    /**
     *
     * @return boolean 
     */
    public function add()
    {
        // Initialise variables.
        $app = JFactory::getApplication();
        $context = "$this->option.edit.$this->context";

        // Access check.
        if (!$this->allowAdd())
        {
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
        $data['order_number'] = IWSequences::nextValue('purchaseorder');
        $data['order_date'] = JDate::getInstance()->toSql();
        $data['supplier_id'] = IWRequest::getInt('s', 0);

        if ($model->save($data))
        {
            $id = $model->getState($model->getName() . '.id');
            $url = 'index.php?option=' . $this->option . '&task=purchaseorder.edit&id=' . $id;
            $this->setRedirect(JRoute::_($url . $this->getRedirectToItemAppend(), false));
        } else
        {
            JFactory::getApplication()->enqueueMessage($model->getError());
            $url = 'index.php?option=' . $this->option . '&view=' . $this->view_list;
            $this->setRedirect(JRoute::_($url . $this->getRedirectToListAppend(), false));
        }


        return true;
    }

}
