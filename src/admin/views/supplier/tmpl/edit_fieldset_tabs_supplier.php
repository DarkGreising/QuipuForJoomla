<?php

if (QUIPU_IS_J3) {
    JFactory::getApplication()->input->set('hidemainmenu', false);
}
$fieldsets = $this->form->getFieldsets();
$active_shown = false;
?>
<ul class="nav nav-tabs">
    <?php

    foreach ($fieldsets as $fieldset) {
        if (!$active_shown) {
            $class = 'class="active"';
            $active_shown = true;
        } else {
            $class = '';
        }
        echo '<li ' . $class, '><a href="#' . $fieldset->name . '" data-toggle="tab">' . JText::_($fieldset->label) . '</a></li>';
    }
    ?>    
    <li><a href="#customer-orders" data-toggle="tab"><?php echo  JText::_('COM_QUIPU_PURCHASE_ORDERS') ?></a>
</ul>

<div class="tab-content">
    <?php

    $active_shown = false;
    foreach ($fieldsets as $fieldset):
        if (!$active_shown) {
            $class = 'active';
            $active_shown = true;
        } else {
            $class = '';
        }
        ?>
        <div class="tab-pane <?php echo  $class ?>" id="<?php echo  $fieldset->name ?>">
            <fieldset class="adminform">
                <?php foreach ($this->form->getFieldset($fieldset->name) as $field): ?>                            
                    <div class="control-group">
                        <div class="control-label"><?php echo  $field->label; ?></div>
                        <div class="controls"><?php echo  $field->input; ?></div>
                    </div>
                <?php endforeach; ?>
            </fieldset>
        </div>
    <?php endforeach; ?>
    <div class="tab-pane" id="customer-orders">
        <div class="span12">
            <h4><?php echo  JText::_('COM_QUIPU_ORDERS') ?></h4>
            <?php if ($this->item->id) echo $this->loadTemplate('orders'); ?>
        </div>
    </div>
</div>
</fieldset>