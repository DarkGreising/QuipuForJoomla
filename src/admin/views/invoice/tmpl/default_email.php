<?php

defined('_JEXEC') or die('Restricted access');
$logo = IWConfig::getInstance()->logo;
?><p>
    <?php echo  JText::sprintf('COM_QUIPU_EMAIL_INVOICE_P1', $this->invoice->customer); ?>
</p>
<p>
    <?php echo  JText::sprintf('COM_QUIPU_EMAIL_INVOICE_P2', $this->invoice->invoice_number, IWDate::_($this->invoice->invoice_date)); ?>
</p>
<p>
    <?php echo  JText::_('COM_QUIPU_EMAIL_INVOICE_P3'); ?>
</p>
<p></p>
<p>
    <?php echo  JText::_('COM_QUIPU_EMAIL_INVOICE_P4'); ?>
</p>

<?php if ($logo): ?>
    <img width="200" src="<?php echo  JURI::base() . '/components/' . IWRequest::getCmd('option') . '/' . $logo . '?' . time() ?>" class="logo" />
<?php endif; ?>
