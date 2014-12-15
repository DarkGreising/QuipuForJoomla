<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');
?>
<form action="<?php echo JRoute::_('index.php?option=com_quipu&view=orders'); ?>" method="post" name="adminForm" id="adminForm">
    <fieldset id="filter-bar">
        <div class="filter-search fltlft">
            <input class="inputbox" type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_QUIPU_FILTER'); ?>" />
            <button type="submit"><?php echo JText::_('COM_QUIPU_FILTER'); ?></button>
            <button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('COM_QUIPU_RESET_FILTER'); ?></button>
        </div>
        <div class="filter-select fltrt">
            <select name="filter_cliente" class="inputbox <?php echo $this->state->get('filter.customer','*')!='*'?'filter-on':''?>" onchange="this.form.submit()">
                <option value="*"><?php echo JText::_('COM_QUIPU_FILTER_BY_CUSTOMER');?></option>
                <?php foreach($this->customers as $id=>$customer):?>
                <option value="<?php echo $customer->id?>" <?php echo ($customer->id==$this->state->get('filter.customer'))?'selected="true"':''?>><?php echo $customer->name?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="filter-select fltrt">
            <select name="filter_estado" class="inputbox <?php echo $this->state->get('filter.status','*')!='*'?'filter-on':''?>" onchange="this.form.submit()">
                <option value="*"><?php echo JText::_('COM_QUIPU_FILTER_BY_STATUS');?></option>
                <?php foreach($this->statuses as $status):?>
                <option value="<?php echo $status?>" <?php echo ($status==$this->state->get('filter.status'))?'selected="true"':''?>><?php echo JText::_('COM_QUIPU_ORDER_STATUS_'.$status)?></option>
                <?php endforeach;?>
            </select>
        </div>
    </fieldset>
    <div class="clr"> </div>
    <table class="adminlist orders">
        <thead><?php echo $this->loadTemplate('head');?></thead>
        <tfoot><?php echo $this->loadTemplate('foot');?></tfoot>
        <tbody><?php echo $this->loadTemplate('body');?></tbody>
    </table>
    <?php echo $this->loadTemplate('export');?>
    <div>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="view" value="<?php echo IWRequest::getCmd('view')?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>
