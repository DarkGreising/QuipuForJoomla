<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
?>
<form class="item" action="<?php echo JRoute::_('index.php?option=com_quipu&layout=edit&id=' . (int) $this->item->id); ?>"
      method="post" name="adminForm" id="customer-form">

    <div class="width-30 fltlft">
        <fieldset class="adminform customer-data">
            <legend><?php echo JText::_('COM_QUIPU_SUPPLIER_DATA'); ?></legend>
            <ul class="adminformlist">
                <?php foreach ($this->form->getFieldset('general') as $field): ?>
                    <?php

                    if ($field->type == 'SUPPLIERACTIONS'):
                        $customer_actions_field = $field;
                    elseif ($field->id == 'jform_memo'):
                        $customer_memo_field = $field;
                    else:
                        ?>
                        <li>
                            <?php
                            echo $field->label;
                            echo $field->input;
                            ?>
                        </li>
    <?php endif; ?>
<?php endforeach; ?>
            </ul>
        </fieldset>
    </div>

    <div class="width-30 fltlft">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_QUIPU_SUPPLIER_ACTIONS'); ?></legend>
            <ul class="adminformlist">                    
                <li>
<?php echo $customer_actions_field->input; ?>
                </li>
            </ul>
        </fieldset>
       
    </div>
    <div class="width-30 fltlft">
        <fieldset class="adminform customer-memo">
            <legend><?php echo JText::_('COM_QUIPU_MEMO'); ?></legend>
            <ul class="adminformlist">                    
                    <li>
                    <?php echo $customer_memo_field->input; ?>
                    </li>
            </ul>
        </fieldset>        
    </div>    

    <div>
        <input type="hidden" name="task" value="supplier.edit" />
<?php echo JHtml::_('form.token'); ?>
    </div>
</form>
<div class="clr"></div>
<div class="width-70 fltlft">
    <fieldset class="adminform">
        <legend><?php echo  JText::_('COM_QUIPU_PURCHASE_ORDERS') ?></legend>
<?php if ($this->item->id) echo $this->loadTemplate('orders'); ?>
    </fieldset>
</div>

