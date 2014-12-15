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
        $this->item = $this->get('Item');
        $bankid = (int) $this->item->bankaccount_id;
        $this->bank = JModelLegacy::getInstance('BankAccount', 'QuipuModel')->getItem($bankid);
        $this->activities = JModelLegacy::getInstance('BankActivities','QuipuModel')->getItemsByDateRange($bankid,false,false,200);

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        // Display the template
        parent::display('payment');
    }

}