<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
?>
<div id="form-result"></div>
<form class="item" action="<?php echo JRoute::_('index.php?option=com_quipu&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="pedido-form">
    <div class="width-25 fltlft">
    <fieldset class="adminform">
        <legend><?php echo JText::_('COM_QUIPU_ORDER_DATA'); ?></legend>
        <ul class="adminformlist">
            <?php foreach ($this->form->getFieldset('general') as $field): ?>
                <li>
                    <?php 
                        echo $field->label;
                        echo $field->input; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </fieldset>
    </div>
    <div class="width-30 fltlft">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_QUIPU_INVOICE_ACTIONS'); ?></legend>
            <ul class="adminformlist">
                <?php foreach ($this->form->getFieldset('actions') as $field): ?>
                    <li>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </fieldset>
    </div>
    <div class="width-30 fltlft">
        <fieldset class="adminform">    
            <legend><?php echo JText::_('COM_QUIPU_MEMO'); ?></legend>
            <ul class="adminformlist">
                <?php foreach ($this->form->getFieldset('memo') as $field): ?>
                    <li>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </fieldset>        
    </div>
    <div class="clrlft">
    <fieldset class="adminform">
        <legend><?php echo JText::_('COM_QUIPU_DETAILS'); ?></legend>
        <ul class="adminformlist">
            <?php foreach ($this->form->getFieldset('detail') as $field): ?>
                <li>
                    <?php 
                        echo $field->label;
                        echo $field->input; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </fieldset> 
    </div>
    <div>
        <input type="hidden" name="task" value="invoice.save" />
<?php echo JHtml::_('form.token'); ?>
    </div>
</form>
<?php echo $this->loadTemplate('newrow');?>
