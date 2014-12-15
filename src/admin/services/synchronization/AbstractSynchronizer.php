<?php
defined('_JEXEC') or die('Restricted access');

/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */
abstract class AbstractSynchronizer {
    
    private static $instance = false;
    

    /**
     * 
     * @param type $is_orders_syncrhonizer
     * @param type $is_customers_syncrhonizer
     * @param type $is_items_syncrhonizer
     */
    protected function __construct() {

    }

    /**
     * @return AbstractSynchronizer
     */
    public static function getInstance(){
        if(!self::$instance){
            $class = get_called_class();
            self::$instance = new $class();
        }
        return self::$instance;
    }
    
    /**
     * @return int the number of orders in the external system need to be imported.
     */
    public abstract function getNewOrdersCount();
    /**
     * @return int the number of customers in the external system need to be imported.
     */
    public abstract function getNewCustomersCount();
    /**
     * @return int the number of items in the external system need to be imported.
     */
    public abstract function getNewItemsCount();
    
    /**
     * @return int the orders imported.
     */
    public abstract function importNewOrders();
    /**
     * @return int the customers imported.
     */
    public abstract function importNewCustomers();
    /**
     * @return intof items imported.
     */
    public abstract function importNewItems();
    
    /**
     * 
     * @return boolean whether this importer is for orders
     */
    public abstract function isOrdersSynchronizer();
    /**
     * 
     * @return boolean whether this importer is for customers
     */
    public abstract function isCustomersSynchronizer();
    /**
     * 
     * @return boolean whether this importer is for items
     */
    public abstract function isItemsSynchronizer();
}
