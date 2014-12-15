<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
?>
<?php if ($this->item->logo): ?>
    <img src="<?php echo  JURI::base() . '/components/' . IWRequest::getCmd('option') . '/' . $this->item->logo . '?' . time() ?>" class="logo pull-right" />
<?php endif; ?>

<form class="item form-validate form-horizontal" action="<?php echo JRoute::_('index.php?option=com_quipu&layout=edit&id=' . (int) $this->item->id); ?>" enctype="multipart/form-data" method="post" name="adminForm" id="adminForm">
    <?php echo  $this->loadTemplate('fieldset_tabs'); ?>

    <div>
        <input type="hidden" name="task" value="config.edit" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>
