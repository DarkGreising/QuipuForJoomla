<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
?>
<form action="<?php echo JRoute::_('index.php?option=com_quipu'); ?>" method="post" name="adminForm" id="adminForm">
    <?php if (!empty($this->sidebar)): ?>
        <div id="j-sidebar-container" class="span2">
            <?php echo $this->sidebar; ?>
        </div>
        <div id="j-main-container" class="span10">
        <?php else : ?>
            <div id="j-main-container">
            <?php endif; ?>
            <div id="filter-bar">
                <div class="btn-group pull-right hidden-phone">
                    <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
                    <?php echo $this->pagination->getLimitBox(); ?>
                </div>
            </div>
            <div class="clr"> </div>                
            <table class="adminlist itemcategories table table-stripped">
                <thead><?php echo $this->loadTemplate('head'); ?></thead>
                <tfoot><?php echo $this->loadTemplate('foot'); ?></tfoot>
                <tbody><?php echo $this->loadTemplate('body'); ?></tbody>
            </table>
            <?php echo $this->loadTemplate('export'); ?>
            <?php echo $this->loadTemplate('import'); ?>

            <input type="hidden" name="task" value="" />
            <input type="hidden" name="boxchecked" value="0" />
            <input type="hidden" name="view" value="<?php echo  IWRequest::getCmd('view') ?>" />
            <?php echo JHtml::_('form.token'); ?>
        </div>

</form>
