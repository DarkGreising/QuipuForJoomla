<?php

/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access');

require_once 'iwtable.php';

/**
 * Quipu Table class
 */
class QuipuTableDetailItem extends IWTable {

    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__quipu_detail_item', 'id', $db);
    }

    /**
     * 
     * @param type $updateNulls
     */
    public function store($updateNulls = false) {

        $this->base = ($this->unit_price * (1 - ($this->discount / 100))) * $this->units;

        /*
          If there's an item_id, we read tax factor from it, and perform calculations on profit and other
          data.
         */
        if ((int) $this->item_id) {
            $is_purchase_order = $this->purchaseorder_id > 0;

            $i = JTable::getInstance('item', 'QuipuTable');
            $i->load($this->item_id);
            $this->description = $i->name;
            if ((float) $this->unit_price) {
                $t = JTable::getInstance('tax', 'QuipuTable');
                $t->load($i->tax_id);

                $this->tax = 100 * $t->factor;

                if (!$is_purchase_order) {

                    //Is a sell order
                    $this->cost = $this->units * $i->cost_price_wotax;
                    $this->profit_wotax = $this->base - $this->cost;
                }

                if ($this->unit_price > 0) {
                    if ($is_purchase_order) {
                        $i->cost_price_wotax = $this->unit_price;
                    } else {
                        $i->last_sell_price_wotax = $this->unit_price;
                    }
                    $i->store();
                }
            }
        }

        //If there was'nt an Item_id, there could be a tax factor value in this row anyway.
        $this->total = $this->base * (1 + ($this->tax / 100));
        return parent::store($updateNulls);
    }

}