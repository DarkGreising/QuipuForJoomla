<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
?>
<div class="span9">
    <form class="item form-validate form-horizontal" action="<?php echo JRoute::_('index.php?option=com_quipu&layout=edit&id=' . (int) $this->item->id); ?>"
          method="post" name="adminForm" id="adminForm">
        <?php echo  $this->loadTemplate('fieldset_tabs_customer'); ?>
        <div>
            <input type="hidden" name="task" value="customer.edit" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </form>    
</div>
<div class="span3">  
    <?php if(isset($profitability)):?>
    <h4><?php echo JText::_('COM_QUIPU_CUSTOMER_PROFITABILITY'); ?></h4>
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
    <?php endif;?>
</div>

