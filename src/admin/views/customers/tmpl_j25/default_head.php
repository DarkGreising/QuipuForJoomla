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
		<?php echo JText::_('COM_QUIPU_VAT_NO'); ?>
	</th>
        <th>
		<?php echo JText::_('COM_QUIPU_PHONE'); ?>
	</th>
        <th>
		<?php echo JText::_('COM_QUIPU_EMAIL'); ?>
	</th>
        <th>
		<?php echo JText::_('COM_QUIPU_CONTACT_PERSON'); ?>
	</th>
        <th>
		<?php echo JText::_('COM_QUIPU_CUSTOMER_ACTIONS'); ?>
	</th>
        
</tr>

