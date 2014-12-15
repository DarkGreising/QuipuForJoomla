<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
$total = 0.0;
?>
<?php foreach ($this->items as $i => $item): 
    $total += $item->total;?>
    <tr class="row<?php echo $i % 2; ?> status-<?php echo  $item->status ?>">
        <td>
            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
        </td>
        <td>
            <a href="<?php echo  JRoute::_('index.php?option=com_quipu&task=purchaseorder.edit&id=' . $item->id); ?>" title="<?php echo  JText::_('COM_QUIPU_EDIT'); ?>">
                <?php echo $item->order_number; ?>
            </a>
        </td>
        <td>
            <?php echo IWDate::_($item->order_date); ?>
        </td>
        <td>
            <a href="<?php echo  JRoute::_('index.php?option=com_quipu&task=supplier.edit&id=' . $item->supplier_id) ?>" title="<?php echo  $item->supplier_name ?>">
                <?php echo $item->supplier_name; ?>
            </a>
        </td>
        <td class="number">
            <?php echo IWUtils::fmtEuro($item->total); ?>
        </td>
        <td>
            <?php            
            echo JText::_("COM_QUIPU_ORDER_STATUS_$item->status");
            ?>
        </td>
    </tr>
<?php endforeach; ?>
<tr>
    <td colspan="4"></td>
    <td class="<?php echo  $total < 0 ? 'negative ' : '' ?>number sum">
        <i class="icon-16-stats"></i>
        <strong>&#8721; <?php echo IWUtils::fmtEuro($total); ?></strong>
    </td>
    <td></td>
</tr>

