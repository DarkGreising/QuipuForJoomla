<?php

defined('_JEXEC') or die('Restricted access');
$logo = IWConfig::getInstance()->logo;
?><p>
    <?php echo  JText::sprintf('COM_QUIPU_EMAIL_ORDER_P1', $this->customer->contact ? $this->customer->contact : $this->customer->name); ?>
</p>
<p>
    <?php echo  JText::sprintf('COM_QUIPU_EMAIL_ORDER_P2', $this->order->order_number, IWDate::_($this->order->order_date)); ?>
</p>
<p>
    <?php echo  JText::_('COM_QUIPU_EMAIL_ORDER_P3'); ?>
</p>
<p></p>
<p>
    <?php echo  JText::_('COM_QUIPU_EMAIL_ORDER_P4'); ?>
</p>

<?php if ($logo): ?>
    <img width="200" src="<?php echo  JURI::base() . '/components/' . IWRequest::getCmd('option') . '/' . $logo . '?' . time() ?>" class="logo" />
<?php endif; ?>
