<?php
defined('_JEXEC') or die('Restricted access');
$props = $this->customer->getProperties();
?>
<h1><?php echo  JText::_('COM_QUIPU_YOUR_CUSTOMER_RECORD') ?></h1>
<p><?php echo  JText::_('COM_QUIPU_YOUR_CUSTOMER_RECORD_INFO') ?></p>
<div id="quipu-customer-data">
    <div class="quipu-customer-data-item">
        <div class="quipu-customer-data-item-name"><?php echo  JText::_('COM_QUIPU_NAME') ?></div>
        <div class="quipu-customer-data-item-value"><?php echo  @$this->customer->name ?></div>
    </div>

    <div class="quipu-customer-data-item">
        <div class="quipu-customer-data-item-name"><?php echo  JText::_('COM_QUIPU_COMPANY_NAME') ?></div>
        <div class="quipu-customer-data-item-value"><?php echo  @$this->customer->company_name ?></div>
    </div>

    <div class="quipu-customer-data-item">
        <div class="quipu-customer-data-item-name"><?php echo  JText::_('COM_QUIPU_VAT_NO') ?></div>
        <div class="quipu-customer-data-item-value"><?php echo  @$this->customer->vatno ?></div>
    </div>

    <div class="quipu-customer-data-item">
        <div class="quipu-customer-data-item-name"><?php echo  JText::_('COM_QUIPU_ADDRESS') ?></div>
        <div class="quipu-customer-data-item-value"><?php echo  @$this->customer->address ?></div>
    </div>

    <div class="quipu-customer-data-item">
        <div class="quipu-customer-data-item-name"><?php echo  JText::_('COM_QUIPU_ZIP_CODE') ?></div>
        <div class="quipu-customer-data-item-value"><?php echo  @$this->customer->zip ?></div>
    </div>

    <div class="quipu-customer-data-item">
        <div class="quipu-customer-data-item-name"><?php echo  JText::_('COM_QUIPU_PHONE') ?></div>
        <div class="quipu-customer-data-item-value"><?php echo  @$this->customer->phone ?></div>
    </div>

    <div class="quipu-customer-data-item">
        <div class="quipu-customer-data-item-name"><?php echo  JText::_('COM_QUIPU_EMAIL') ?></div>
        <div class="quipu-customer-data-item-value"><?php echo  @$this->customer->email ?></div>
    </div>

    <div class="quipu-customer-data-item">
        <div class="quipu-customer-data-item-name"><?php echo  JText::_('COM_QUIPU_CONTACT_PERSON') ?></div>
        <div class="quipu-customer-data-item-value"><?php echo  @$this->customer->contact_person ?></div>
    </div>


</div>
<h2><?php echo  JText::_('COM_QUIPU_YOUR_CUSTOMER_ORDERS') ?></h2>
<?php if (is_array($this->customer->orders) && count($this->customer->orders)): ?>
    <div id="quipu-customer-orders">
        <div class="quipu-customer-orders-header">
            <div class="quipu-customer-order-header-item"><?php echo  JText::_('COM_QUIPU_NUMBER') ?></div>
            <div class="quipu-customer-order-header-item"><?php echo  JText::_('COM_QUIPU_DATE') ?></div>
            <div class="quipu-customer-order-header-item"><?php echo  JText::_('COM_QUIPU_TOTAL') ?></div>
            <div class="quipu-customer-order-header-item"><?php echo  JText::_('COM_QUIPU_STATUS') ?></div>
            <div class="quipu-customer-order-header-item"><?php echo  JText::_('COM_QUIPU_DOCS') ?></div>

        </div>
        <?php foreach ($this->customer->orders as $order): ?>
            <div class="quipu-customer-order">
                <div class="quipu-customer-order-item"><?php echo  $order->order_number ?></div>
                <div class="quipu-customer-order-item"><?php echo  IWDate::_($order->order_date) ?></div>
                <div class="quipu-customer-order-item"><?php echo  IWUtils::fmtEuro($order->total) ?></div>
                <div class="quipu-customer-order-item">
                    <span class="status-<?php echo  $order->status ?>">
                        <?php echo  JText::_('COM_QUIPU_ORDER_STATUS_' . $order->status) ?>
                    </span>
                </div>
                <div class="quipu-customer-order-item">
                    <a target="_blank" href="<?php echo JRoute::_('index.php?option=com_quipu&view=order&format=pdf&id=' . $order->id)?>"><?php echo  JText::_('COM_QUIPU_ORDER') ?></a>
                    <?php if ($order->invoice_id > 0): ?>
                        | <a target="_blank" href="<?php echo JRoute::_('index.php?option=com_quipu&view=invoice&format=pdf&id=' . $order->invoice_id)?>"><?php echo  JText::_('COM_QUIPU_INVOICE') ?></a>
                    <?php endif; ?>
                </div>
            </div>

        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p><?php echo  JText::_('COM_QUIPU_YOUR_CUSTOMER_ORDERS_EMPTY') ?></p>
<?php endif; ?>

