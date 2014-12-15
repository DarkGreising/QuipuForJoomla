<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');
?>
<form action="<?php echo JRoute::_('index.php?option=com_quipu&view=invoices'); ?>" method="post" name="adminForm" id="adminForm">
    <?php if (!empty($this->sidebar)): ?>
        <div id="j-sidebar-container" class="span2">
            <?php echo $this->sidebar; ?>
        </div>
        <div id="j-main-container" class="span10">
        <?php else : ?>
            <div id="j-main-container">
            <?php endif; ?>
            <div id="filter-bar" class="btn-toolbar">
                <div class="filter-search btn-group pull-left">
                    <label for="filter_search" class="element-invisible"><?php echo JText::_('COM_QUIPU_FILTER'); ?></label>
                    <input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_QUIPU_FILTER'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_QUIPU_FILTER'); ?>" />
                </div>
                <div class="btn-group pull-left">
                    <button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
                    <button class="btn hasTooltip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
                </div>
                <div class="btn-group pull-right hidden-phone">
                    <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
                    <?php echo $this->pagination->getLimitBox(); ?>
                </div>

            </div>
            <div class="clearfix"> </div>
            <table class="adminlist invoices table table-stripped">
                <thead><?php echo $this->loadTemplate('head'); ?></thead>
                <tfoot><?php echo $this->loadTemplate('foot'); ?></tfoot>
                <tbody><?php echo $this->loadTemplate('body'); ?></tbody>
            </table>
            <?php echo $this->loadTemplate('export'); ?>

            <input type="hidden" name="task" value="" />
            <input type="hidden" name="boxchecked" value="0" />
            <input type="hidden" name="view" value="<?php echo  IWRequest::getCmd('view') ?>" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
</form>
