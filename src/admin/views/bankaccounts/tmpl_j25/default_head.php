<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<tr>
	<th width="1%">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
	</th>
	<th>
		<?php echo JText::_('COM_QUIPU_NAME'); ?>
	</th>
        <th>
		<?php echo JText::_('COM_QUIPU_BANK_ACCOUNT_NUMBER'); ?>
	</th>
        <th>
		<?php echo JText::_('COM_QUIPU_BALANCE'); ?>
	</th>
        <th>
		<?php echo JText::_('COM_QUIPU_BANK_ACTIONS'); ?>
	</th>
</tr>

