<?php
/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

/**
 * Quipu Controller
 */
class QuipuControllerInvoice extends JControllerForm
{
    /**
     * 
     */
    public function refund(){
        $id = IWRequest::getInt('id');
        $refund_id = $this->getModel()->generateRefund($id);
        
        if($refund_id){
            $this->setRedirect('index.php?option=com_quipu&task=invoice.edit&id=' . $refund_id);            
        }
        elseif($id){
            $this->setRedirect('index.php?option=com_quipu&task=invoice.edit&id=' . $id);            
        }
        else{
            $this->setRedirect('index.php?option=com_quipu&view=invoices');            
        }
        
    }
}
