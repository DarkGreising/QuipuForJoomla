<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
?>
<form class="item" action="<?php echo JRoute::_('index.php?option=com_quipu&view=order&layout=edit&id=' . $this->item->id); ?>" method="post" name="adminForm" id="pedido-form">
    <div class="width-25 fltlft">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_QUIPU_ORDER_DATA'); ?></legend>
            <ul class="adminformlist">
                <?php foreach ($this->form->getFieldset('general') as $field): ?>
                    <li>
                        <?php
                        echo $field->label;
                        echo $field->input;
                        ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </fieldset>
    </div>
    <div class="width-30 fltlft">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_QUIPU_ORDER_ACTIONS'); ?></legend>
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

    <div>
        <input type="hidden" name="task" value="" />        
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>
<div class="clrlft">
    <form class="item" action="" method="post" name="detailsForm" id="pedido-form-details">        
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_QUIPU_DETAILS'); ?></legend>
            <ul class="adminformlist">
                <?php foreach ($this->form->getFieldset('detail') as $field): ?>
                    <li>
                        <?php
                        echo $field->label;
                        echo $field->input;
                        ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </fieldset>
    </form>
</div>
<?php echo  $this->loadTemplate('newrow'); ?>
