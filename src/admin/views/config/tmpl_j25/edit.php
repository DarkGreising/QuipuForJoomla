<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
?>
<form class="item" action="<?php echo JRoute::_('index.php?option=com_quipu&layout=edit&id=' . (int) $this->item->id); ?>" enctype="multipart/form-data" method="post" name="adminForm" id="config-form">
    <div class="width-40 fltlft">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_QUIPU_COMPANY_INFO'); ?></legend>
            <?php if ($this->item->logo): ?>
                <img src="<?php echo  JURI::base() . '/components/' . IWRequest::getCmd('option') . '/' . $this->item->logo . '?' . time() ?>" class="logo" />
            <?php endif; ?>

            <ul class="adminformlist">
                <?php foreach ($this->form->getFieldset('general') as $field): ?>
                    <li>
                        <?php
                        echo $field->label;
                        echo $field->input;
                        ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </fieldset>
    </div>  
    <div class="width-40 fltlft">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_QUIPU_CONFIG_PARAMETERS'); ?></legend>
            <ul class="adminformlist">
                <?php foreach ($this->form->getFieldset('parameters') as $field): ?>
                    <li>
                        <?php
                        echo $field->label;
                        echo $field->input;
                        ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </fieldset>
        <?/*
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_QUIPU_CONFIG_SYNC'); ?></legend>
            <p><?php echo JText::_('COM_QUIPU_CONFIG_SYNC_INFO')?></p>
            <ul class="adminformlist">
                <?php foreach ($this->form->getFieldset('synchronization') as $field): ?>
                    <li>
                        <?php
                        echo $field->label;
                        echo $field->input;
                        ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </fieldset>       */?>
    </div>    
    <div class="width-80 clrlft">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_QUIPU_COMPANY_INFO'); ?></legend>
            <ul class="adminformlist">
                <?php foreach ($this->form->getFieldset('info') as $field): ?>
                    <li>
                        <?php
                        echo $field->label;
                        echo $field->input;
                        ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </fieldset>        
    </div>

    <div>
        <input type="hidden" name="task" value="config.edit" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>
