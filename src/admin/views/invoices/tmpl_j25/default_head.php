<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<tr>
	<th width="1%">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
	</th>
	<th>
		<?php echo JText::_('COM_QUIPU_NUMBER'); ?>
	</th>
        <th>
		<?php echo JText::_('COM_QUIPU_CUSTOMER'); ?>
	</th>
        <th>
		<?php echo JText::_('COM_QUIPU_INVOICE_DATE'); ?>
	</th>
        <th>
		<?php echo JText::_('COM_QUIPU_INVOICE_DUE_DATE'); ?>
	</th>
        <th>
		<?php echo JText::_('COM_QUIPU_TOTAL'); ?>
	</th>
        <th>
		<?php echo JText::_('COM_QUIPU_STATUS'); ?>
	</th>
</tr>

