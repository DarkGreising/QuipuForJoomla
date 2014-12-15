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
class QuipuTableInvoice extends IWTable {

    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__quipu_invoice', 'id', $db);
    }

    /**
     * 
     */
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
            $query->where('d.invoice_id=' . $id);
            $db->setQuery($query);
            $res = $db->loadObjectList('id','JObject');

            return $res;
        }
        return false;
    }

    /**
     *
     * @param type $updateNulls 
     */
    public function store($updateNulls = false) {
          $detail = $this->getDetail($this->id);
          $this->total=0;
          $this->base=0;
          $this->total_tax=0;
          $this->total_retentions = 0;

          foreach($detail as $item){
              $tax = $item->base * ($item->tax/100);
              $ret = $item->base * ($this->retentions/100);

              $this->total_tax += $tax;
              $this->total_retentions += $ret;

              $this->base += $item->base;

              $this->total += ($item->base + $tax - $ret);
          }


          return parent::store($updateNulls);
    }

}