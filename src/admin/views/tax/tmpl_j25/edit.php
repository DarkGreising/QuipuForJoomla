<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
?>
<form class="item" action="<?php echo JRoute::_('index.php?option=com_quipu&layout=edit&id='.(int) $this->item->id); ?>"
      method="post" name="adminForm" id="iva-form">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'DETAILS' ); ?></legend>
		<ul class="adminformlist">
<?php foreach($this->form->getFieldset() as $field): ?>
			<li><?php echo $field->label;echo $field->input;?></li>
<?php endforeach; ?>
		</ul>
	</fieldset>
	<div>
		<input type="hidden" name="task" value="tax.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
