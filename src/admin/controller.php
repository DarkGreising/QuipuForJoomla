<?php
/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * General Controller of iw_erp component
 */
class QuipuController extends JControllerLegacy
{
	/**
	 * display task
	 *
	 * @return void
	 */
	function display($cachable = false, $urlparams = array())
	{
		// set default view if not set
		IWRequest::setVar('view', IWRequest::getCmd('view', 'Panel'));

		// call parent behavior
		parent::display($cachable);


                // Load the submenu.
		QuipuHelper::addSubmenu(IWRequest::getCmd('view', 'customers'));

		
	}
}
