<?php
defined('_JEXEC') or die('Restricted access');

/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */
class IWVirtueMartImporter extends AbstractSynchronizer{
    
    
    
    public function getNewCustomersCount() {
        
    }

    public function getNewItemsCount() {
        
    }

    public function getNewOrdersCount() {
        
    }

    public function importNewCustomers() {
        
    }

    public function importNewItems() {
        
    }

    public function importNewOrders() {
        
    }

    public function isCustomersSynchronizer() {
        return false;
    }

    public function isItemsSynchronizer() {
        return false;
    }

    public function isOrdersSynchronizer() {
        return true;
    }
}
