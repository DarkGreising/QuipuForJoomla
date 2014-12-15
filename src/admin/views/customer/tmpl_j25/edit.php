<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
?>
<form class="item" action="<?php echo JRoute::_('index.php?option=com_quipu&layout=edit&id=' . (int) $this->item->id); ?>"
      method="post" name="adminForm" id="customer-form">

    <div class="width-30 fltlft">
        <fieldset class="adminform customer-data">
            <legend><?php echo JText::_('COM_QUIPU_CUSTOMER_DATA'); ?></legend>
            <ul class="adminformlist">
                <?php foreach ($this->form->getFieldset('general') as $field): ?>
                    <?php

                    if ($field->type == 'CUSTOMERACTIONS'):
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
            <legend><?php echo JText::_('COM_QUIPU_CUSTOMER_ACTIONS'); ?></legend>
            <ul class="adminformlist">                    
                <li>
<?php echo $customer_actions_field->input; ?>
                </li>
            </ul>
        </fieldset>
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_QUIPU_CUSTOMER_PROFITABILITY'); ?></legend>
            <ul class="">
                <li>
                    <?php echo  JText::_('COM_QUIPU_CUSTOMER_ORDER_INVOICED') . ': ' ?><span class="number"><?php echo  $this->profitability->orders ?></span>
                </li>
                <li>
                    <?php echo  JText::_('COM_QUIPU_CUSTOMER_TOTAL_PROFITABILITY') . ': ' ?><span class="number"><?php echo  IWUtils::fmtEuro($this->profitability->total_profit) ?></span>
                </li>
                <li>
                    <?php echo  JText::_('COM_QUIPU_CUSTOMER_MAX_PROFITABILITY') . ': ' ?><span class="number"><?php echo  IWUtils::fmtEuro($this->profitability->max_profit) ?></span>
                </li>
                <li>
                    <?php echo  JText::_('COM_QUIPU_CUSTOMER_MIN_PROFITABILITY') . ': ' ?><span class="number"><?php echo  IWUtils::fmtEuro($this->profitability->min_profit) ?></span>
                </li>
                <li>
<?php echo  JText::_('COM_QUIPU_CUSTOMER_AVG_PROFITABILITY') . ': ' ?><span class="number"><?php echo  IWUtils::fmtEuro($this->profitability->avg_profit) ?></span>
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
        <input type="hidden" name="task" value="customer.edit" />
<?php echo JHtml::_('form.token'); ?>
    </div>
</form>
<div class="clr"></div>
<div class="width-70 fltlft">
    <fieldset class="adminform">
        <legend><?php echo  JText::_('COM_QUIPU_ORDERS') ?></legend>
<?php if ($this->item->id) echo $this->loadTemplate('orders'); ?>
    </fieldset>
</div>

