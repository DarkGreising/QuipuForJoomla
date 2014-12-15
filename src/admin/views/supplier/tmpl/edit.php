<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
?>
<div class="span9">
    <form class="item form-validate form-horizontal" action="<?php echo JRoute::_('index.php?option=com_quipu&layout=edit&id=' . (int) $this->item->id); ?>"
          method="post" name="adminForm" id="adminForm">
        <?php echo  $this->loadTemplate('fieldset_tabs_supplier'); ?>
        <div>
            <input type="hidden" name="task" value="supplier.edit" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </form>    
</div>
