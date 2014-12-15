<?php
//-- No direct access
defined('_JEXEC') or die('=;)');

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
$formAction = 'index.php?option=' . IWRequest::getCmd('option') . '&task=excel.import';

$fieldnames = explode(',', JText::_('COM_QUIPU_IMPORT_' . strtoupper($this->table)));
?>
<div class="import-excel">    
    <h1><?php echo  JText::_('COM_QUIPU_IMPORT_CSV') ?></h1>                

    <?php if ($this->result): ?>
        <?php if (is_numeric($this->result)): ?>  
            <div class="success">
                <p><?php echo  JText::sprintf('COM_QUIPU_IMPORT_OK', $this->result) ?></p>       
                <p><?php echo  JText::_('COM_QUIPU_PLEASEWAIT') ?></p>       
            </div>
            <script type="text/javascript">
                window.setTimeout("window.top.location.reload(true)",2500);
            </script>
        <?php else: ?>

            <div class="invalid">
                <p><?php echo  JText::_('COM_QUIPU_IMPORT_ERROR') ?></p>
                <?php if (is_array($this->result)): ?>
                    <ul>
                        <?php foreach ($this->result as $e): ?>
                            <li><?php echo  $e ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p><?php echo  $this->result ?></p>
                <?php endif; ?>
            </div>
        <?php

        endif;
    endif;
    ?>
    <form id="import-excel-form" action="<?php echo JRoute::_($formAction); ?>" method="post" class="" enctype="multipart/form-data">
        <input type="file" class="inputbox" name="file" />
        <div class="buttons">
            <button type="submit" ><?php echo JText::_('COM_QUIPU_IMPORT_SUBMIT'); ?></button>
            <input type="hidden" name="option" value="<?php echo  IWRequest::getCmd('option') ?>" />
            <input type="hidden" name="task" value="excel.import" />                
            <input type="hidden" name="t" value="<?php echo  $this->table ?>" />                        

            <?php echo JHtml::_('form.token'); ?>
        </div>

    </form>
    <p><?php echo  JText::_('COM_QUIPU_IMPORT') ?></p>
    <p>
    <ul>
        <?php foreach ($fieldnames as $field): ?>
            <li><?php echo  $field ?></li>
        <?php endforeach; ?>
    </ul>  
</p>
<p><?php echo  JText::_('COM_QUIPU_IMPORT_EXCEL') ?></p>
<p><?php echo  JText::sprintf('COM_QUIPU_IMPORT_DATES',JText::_('DATE_FORMAT_LC4')) ?></p>
</div>


