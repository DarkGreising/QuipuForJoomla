<?php
/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Quipu Form Field class for the Quipu component
 */
class JFormFieldMovimiento extends JFormFieldList
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'Movimiento';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array		An array of JHtml options.
	 */
	protected function getOptions()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__quipu_bank_activity');
		$db->setQuery((string)$query);
		$items = $db->loadObjectList();
		$options = array();
		if ($items)
		{
			foreach($items as $item)
			{
				$options[] = JHtml::_('select.option', $item->id, $item->descripcion);
			}
		}
		$options = array_merge(parent::getOptions(), $options);
		return $options;
	}
}

?>

