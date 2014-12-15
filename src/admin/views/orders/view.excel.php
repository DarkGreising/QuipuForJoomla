<?php
/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');
require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'views' . DS . 'ExcelView.php');
/**
 * Quipu View
 */
class QuipuViewOrders extends ExcelView
{
    /**
     *
     * @return type 
     */
    public function getRowObjects(){
        $this->getModel()->ignorePagination(true);
        $items = $this->get('Items');
        return $items;
    }
}

