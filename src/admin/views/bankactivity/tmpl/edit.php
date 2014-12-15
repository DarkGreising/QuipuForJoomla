<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
?>
<form action="<?php echo JRoute::_('index.php?option=com_quipu&layout=edit&id='.(int) $this->item->id); ?>"
      method="post" name="adminForm" id="adminForm">
        <?php echo  $this->loadTemplate('fieldset_tabs'); ?>
	<div>
		<input type="hidden" name="task" value="bankactivity.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
