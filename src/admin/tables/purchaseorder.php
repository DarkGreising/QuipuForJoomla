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
class QuipuTablePurchaseorder extends IWTable {

    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__quipu_purchase_order', 'id', $db);
    }

    /**
     * 
     */
    public function getDetail($id = null) {
        if(!$id){
            $id = $this->id;
        }
        if ($id) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('d.*, d.description as item');
            $query->from('#__quipu_detail_item d');
            //$query->leftJoin('#__quipu_item i on d.item_id=i.id');
            $query->where('d.purchaseorder_id=' . $id);
            $db->setQuery($query);
            $res = $db->loadObjectList();

            return $res;
        }
        return false;
    }

    /**
     *
     * @param type $updateNulls 
     */
    public function store($updateNulls = false) {      
      $detail = $this->getDetail();
      $this->total=0;
      $this->base=0;
      $this->total_tax=0;
      foreach($detail as $item){
          $this->total_tax += $item->base * ($item->tax/100);
          $this->base += $item->base;
          $this->total += $item->total;      
      }
      
      return parent::store($updateNulls);
    }

}
