<?php

/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
require_once 'dyntable.php';

/**
 *
 * @package		iwERP
 * 
 * 
 */
class QuipuControllerDetailitem extends QuipuControllerDyntable {

    /**
     * 
     * @param type $field
     * @param type $value
     */
    protected function getFormatted($field, $value) {
        switch ($field) {
            case 'item_id':
                $t = JTable::getInstance('item', 'QuipuTable');
                return $t->load($value) ? $t->name : '?????';
            default:
                return parent::getFormatted($field, $value);
        }
    }

    /**
     * Row sums calculation.
     */
    protected function afterBind() {

        if ($this->row->discount > 100) {
            $this->row->discount = 0.0;
            $this->json['message'] = JText::_('COM_QUIPU_ORDER_ROW_DISCOUNT_TOOBIG_WARNING');
            $this->json['error'] = true;
        }
    }

    /**
     * 
     */
    protected function afterStore() {
        if ($this->row->order_id) {
            if ($this->row->profit_wotax < 0) {
                $this->json['message'] = JText::sprintf('COM_QUIPU_ORDER_ROW_PROFITWARNING', IWUtils::fmtEuro($this->row->cost), IWUtils::fmtEuro(-1 * $this->row->profit_wotax));
                $this->json['error'] = true;
            }
        }
    }

}