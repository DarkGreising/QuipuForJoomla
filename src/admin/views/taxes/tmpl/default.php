<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');
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
            <table class="adminlist taxes table table-stripped">
                <thead><?php echo $this->loadTemplate('head'); ?></thead>
                <tfoot><?php echo $this->loadTemplate('foot'); ?></tfoot>
                <tbody><?php echo $this->loadTemplate('body'); ?></tbody>
            </table>
            <?php echo $this->loadTemplate('export'); ?>

            <div>
                <input type="hidden" name="task" value="" />
                <input type="hidden" name="boxchecked" value="0" />
                <input type="hidden" name="view" value="<?php echo  IWRequest::getCmd('view') ?>" />
                <?php echo JHtml::_('form.token'); ?>
            </div>
            </form>
