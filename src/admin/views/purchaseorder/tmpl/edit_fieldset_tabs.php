<?php

$fieldsets = $this->form->getFieldsets();
if(QUIPU_IS_J3){
    JFactory::getApplication()->input->set('hidemainmenu', false);
}
?>
<ul class="nav nav-tabs">
    <?php foreach ($fieldsets as $fieldset): ?>
        <li class="active"><a href="#<?php echo  $fieldset->name ?>" data-toggle="tab"><?php echo JText::_($fieldset->label); ?></a></li>
    <?php endforeach ?>
</ul>

<div class="tab-content">
    <?php foreach ($fieldsets as $fieldset): ?>
        <div class="tab-pane active" id="<?php echo  $fieldset->name ?>">
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
</div>
</fieldset>