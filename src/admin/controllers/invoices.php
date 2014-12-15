<?php

/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');

/**
 * Quipu Controller
 */
class QuipuControllerInvoices extends JControllerAdmin {

    /**
     * Proxy for getModel.
     * @since	1.7
     */
    public function getModel($name = 'Factura', $prefix = 'QuipuModel') {
        $model = parent::getModel($name, $prefix, array('ignore_request' => true));
        return $model;
    }

    /**
     * 
     */
    public function printItems() {
        $cid = IWRequest::getVar('cid', array(), '', 'array');

        if (!is_array($cid) || count($cid) < 1) {
            JError::raiseWarning(500, JText::_($this->text_prefix . '_NO_ITEM_SELECTED'));
        } else {
            // Make sure the item ids are integers
            jimport('joomla.utilities.arrayhelper');
            JArrayHelper::toInteger($cid);
            
            $this->setRedirect('index.php?option=' . IWRequest::getCmd('option') . '&view=invoices&format=pdf&id=' . implode(',',$cid));
        }
    }

}
