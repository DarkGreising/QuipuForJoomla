<?php

/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');

/**
 * Quipu Controller
 */
class QuipuControllerSuppliers extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 * @since	1.7
	 */
	public function getModel($name = 'Supplier', $prefix = 'QuipuModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}
