<?php

/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * Quipu component helper.
 *
 * @package		Quipu
 * @subpackage	com_quipu
 * 
 */
abstract class QuipuHelper {

    /**
     * @var    JObject  A cache for the available actions.
     * @since  1.6
     */
    protected static $actions;

    /**
     * Gets a list of the actions that can be performed.
     *
     * @return  JObject
     *
     * @since   1.6
     * @todo    Refactor to work with notes
     */
    public static function getActions() {
        if (empty(self::$actions)) {
            $user = JFactory::getUser();
            self::$actions = new JObject;

            $actions = JAccess::getActions('com_quipu');

            foreach ($actions as $action) {
                self::$actions->set($action->name, $user->authorise($action->name, 'com_quipu'));
            }
        }

        return self::$actions;
    }

    /**
     * Configure the Linkbar.
     *
     * @param	string	The name of the active view.
     *
     * @return	void
     * @since	1.6
     */
    public static function addSubmenu($submenu) {
        if (QUIPU_IS_J3) {
            self::addSubmenu_J30($submenu);
        } else {
            self::addSubmenu_J25($submenu);
        }
    }

    /**
     * 
     * @param type $submenu
     */
    public static function addSubmenu_J25($submenu) {

        $submenu = strtolower($submenu);
        JSubMenuHelper::addEntry(
                JText::_('COM_QUIPU_SUBMENU_PANEL'), 'index.php?option=com_quipu', $submenu == 'panel'
        );

        JSubMenuHelper::addEntry(
                JText::_('COM_QUIPU_SUBMENU_PANEL_CUSTOMERS'), 'index.php?option=com_quipu&view=customers', $submenu == 'customers'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_QUIPU_SUBMENU_PANEL_SUPPLIERS'), 'index.php?option=com_quipu&view=suppliers', $submenu == 'suppliers'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_QUIPU_SUBMENU_PANEL_ITEM_CATEGORIES'), 'index.php?option=com_quipu&view=itemcategories', $submenu == 'itemcategories'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_QUIPU_SUBMENU_PANEL_ITEMS'), 'index.php?option=com_quipu&view=items', $submenu == 'items'
        );

        JSubMenuHelper::addEntry(
                JText::_('COM_QUIPU_SUBMENU_PANEL_PURCHASEORDERS'), 'index.php?option=com_quipu&view=purchaseorders', $submenu == 'purchaseorders'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_QUIPU_SUBMENU_PANEL_ORDERS'), 'index.php?option=com_quipu&view=orders', $submenu == 'orders'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_QUIPU_SUBMENU_PANEL_INVOICES'), 'index.php?option=com_quipu&view=invoices', $submenu == 'invoices'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_QUIPU_SUBMENU_PANEL_BANK_ACCOUNTS'), 'index.php?option=com_quipu&view=bankaccounts', $submenu == 'bankaccounts'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_QUIPU_SUBMENU_PANEL_BANK_ACTIVITIES'), 'index.php?option=com_quipu&view=bankactivities', $submenu == 'bankactivities'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_QUIPU_SUBMENU_PANEL_TAXES'), 'index.php?option=com_quipu&view=taxes', $submenu == 'taxes'
        );

        JSubMenuHelper::addEntry(
                JText::_('COM_QUIPU_SUBMENU_PANEL_CONFIG'), 'index.php?option=com_quipu&task=config.edit&id=' . IWConfig::getInstance()->id, $submenu == 'config'
        );
    }

    public static function addSubmenu_J30($submenu) {

        $submenu = strtolower($submenu);
        JHtmlSidebar::addEntry(
                JText::_('COM_QUIPU_SUBMENU_PANEL'), 'index.php?option=com_quipu', $submenu == 'panel'
        );
        JHtmlSidebar::addEntry(
                JText::_('COM_QUIPU_SUBMENU_PANEL_CUSTOMERS'), 'index.php?option=com_quipu&view=customers', $submenu == 'customers'
        );
        JHtmlSidebar::addEntry(
                JText::_('COM_QUIPU_SUBMENU_PANEL_ORDERS'), 'index.php?option=com_quipu&view=orders', $submenu == 'orders'
        );
        JHtmlSidebar::addEntry(
                JText::_('COM_QUIPU_SUBMENU_PANEL_INVOICES'), 'index.php?option=com_quipu&view=invoices', $submenu == 'invoices'
        );
        JHtmlSidebar::addEntry(
                JText::_('COM_QUIPU_SUBMENU_PANEL_SUPPLIERS'), 'index.php?option=com_quipu&view=suppliers', $submenu == 'suppliers'
        );

        JHtmlSidebar::addEntry(
                JText::_('COM_QUIPU_SUBMENU_PANEL_PURCHASEORDERS'), 'index.php?option=com_quipu&view=purchaseorders', $submenu == 'purchaseorders'
        );
        JHtmlSidebar::addEntry(
                JText::_('COM_QUIPU_SUBMENU_PANEL_BANK_ACCOUNTS'), 'index.php?option=com_quipu&view=bankaccounts', $submenu == 'bankaccounts'
        );
        JHtmlSidebar::addEntry(
                JText::_('COM_QUIPU_SUBMENU_PANEL_BANK_ACTIVITIES'), 'index.php?option=com_quipu&view=bankactivities', $submenu == 'bankactivities'
        );
        JHtmlSidebar::addEntry(
                JText::_('COM_QUIPU_SUBMENU_PANEL_ITEM_CATEGORIES'), 'index.php?option=com_quipu&view=itemcategories', $submenu == 'itemcategories'
        );
        JHtmlSidebar::addEntry(
                JText::_('COM_QUIPU_SUBMENU_PANEL_ITEMS'), 'index.php?option=com_quipu&view=items', $submenu == 'items'
        );        
        JHtmlSidebar::addEntry(
                JText::_('COM_QUIPU_SUBMENU_PANEL_TAXES'), 'index.php?option=com_quipu&view=taxes', $submenu == 'taxes'
        );

        JHtmlSidebar::addEntry(
                JText::_('COM_QUIPU_SUBMENU_PANEL_CONFIG'), 'index.php?option=com_quipu&task=config.edit&id=' . IWConfig::getInstance()->id, $submenu == 'config'
        );
    }

    private static $_catalog = false;

    /**
     *
     */
    public static function getItemsSelect($name, $value = false) {
        if (!self::$_catalog) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('i.id, i.name, c.name as category');
            $query->from('#__quipu_item i');
            $query->innerJoin('#__quipu_item_category c ON i.category_id = c.id');
            $query->order(' c.name, i.name');

            $db->setQuery($query);
            $items = $db->loadObjectList();

            $catalog = array();
            foreach ($items as $item) {
                $catalog[$item->category][$item->id] = $item->name;
            }
            self::$_catalog = $catalog;
        }
        $html = array();
        $html[] = '<select class="inputbox" name="' . $name . '">';
        $html[] = '<option value=""></option>';
        foreach (self::$_catalog as $category => $items) {
            $html[] = '<optgroup label="' . $category . '">';
            foreach ($items as $id => $item) {
                $html[] = '<option value="' . $id . '" ' . (($value == $id) ? ' selected="selected"' : '') . '>' . $item . '</option>';
            }
            $html[] = '</optgroup>';
        }
        $html[] = '</select>';
        return implode('', $html);
    }

}
