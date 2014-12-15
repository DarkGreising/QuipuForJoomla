<?php

/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'views' . DS . 'JSONView.php');

/**
 * Quipu View
 */
class QuipuViewOrders extends JSONView {

    /**
     * 
     * @param type $items
     */
    public function processItems($items) {
        foreach($items as $item){
            $item->status = JText::_('COM_QUIPU_ORDER_STATUS_' . $item->status);
            $item->order_date = IWDate::_($item->order_date);
            $item->total = IWUtils::fmtEuro($item->total);
        }
        
        return $items;
    }

}