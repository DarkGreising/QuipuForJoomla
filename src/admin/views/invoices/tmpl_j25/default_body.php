<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
$total = 0.0;
?>
<?php foreach ($this->items as $i => $item):
    $total += $item->total;
    ?>
    <tr class="row<?php echo $i % 2; ?> <?php echo  ($item->status == IW_ERP_ESTADO_FACTURA_VENCIDA ? ' invalid' : '') ?>">
        <td>
    <?php echo JHtml::_('grid.id', $i, $item->id); ?>
        </td>
        <td>
            <a href="<?php echo  JRoute::_('index.php?option=com_quipu&task=invoice.edit&id=' . $item->id); ?>" title="<?php echo  JText::_('COM_QUIPU_EDIT'); ?>">
    <?php echo $item->invoice_number; ?>
            </a>
        </td>
        <td>
    <?php echo $item->customer_name; ?>
        </td>
        <td>
    <?php echo IWDate::_($item->invoice_date); ?>
        </td>
        <td>
    <?php echo IWDate::_($item->due_date); ?>
        </td>
        <td class="<?php echo  $item->total < 0 ? 'negative ' : '' ?>number">
    <?php echo IWUtils::fmtEuro($item->total); ?>
        </td>
        <td>
    <?php echo JText::_("COM_QUIPU_INVOICE_STATUS_$item->status"); ?>
        </td>
    </tr>
<?php endforeach; ?>
<tr>
    <td colspan="5"></td>
    <td class="<?php echo  $total < 0 ? 'negative ' : '' ?>number sum">
        <i class="icon-16-stats"></i>
        <strong>&#8721; <?php echo IWUtils::fmtEuro($total); ?></strong>
    </td>
    <td></td>
</tr>
