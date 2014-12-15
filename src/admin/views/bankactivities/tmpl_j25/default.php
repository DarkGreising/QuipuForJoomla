<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
?>
<form action="<?php echo JRoute::_('index.php?option=com_quipu&view=bankactivities'); ?>" method="post" name="adminForm" id="adminForm">
    <fieldset id="filter-bar">
        <div class="filter-search fltlft">
            <input class="inputbox" type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_QUIPU_FILTER'); ?>" />
            <button type="submit"><?php echo JText::_('COM_QUIPU_FILTER'); ?></button>
            <button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('COM_QUIPU_RESET_FILTER'); ?></button>
        </div>
        <div class="filter-select fltrt">
            <select name="filter_bankaccount" class="inputbox <?php echo $this->state->get('filter.bankaccount','*')!='*'?'filter-on':''?>" onchange="this.form.submit()">
                <option value="*"><?php echo JText::_('COM_QUIPU_BANK');?></option>
                <?php foreach($this->banks as $id=>$bank):?>
                <option value="<?php echo $bank->id?>" <?php echo ($bank->id==$this->state->get('filter.bankaccount'))?'selected="true"':''?>><?php echo $bank->name?></option>
                <?php endforeach;?>
            </select>
        </div>
    </fieldset>
    <div class="clr"> </div>
    <?php echo $this->loadTemplate('finance');?>
    <table class="adminlist iw-with-sidecol">
        <thead><?php echo $this->loadTemplate('head');?></thead>
        <tfoot><?php echo $this->loadTemplate('foot');?></tfoot>
        <tbody><?php echo $this->loadTemplate('body');?></tbody>
    </table>
    <?php echo $this->loadTemplate('export');?>
    <?php echo $this->loadTemplate('import');?>
    <div>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="view" value="<?php echo IWRequest::getCmd('view')?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>
