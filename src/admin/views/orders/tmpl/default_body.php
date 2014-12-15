<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
$total = 0.0;
?>
<?php
foreach ($this->items as $i => $item):
    $class = 'row' . ($i % 2);
    switch ($item->status) {
        case(IW_ERP_ESTADO_PEDIDO_PENDIENTE):
            $class.=' warning';
            break;
        case(IW_ERP_ESTADO_PEDIDO_ENTREGADO):
        case(IW_ERP_ESTADO_PEDIDO_FACTURADO):
            $class.=' success';
            break;
        case(IW_ERP_ESTADO_PEDIDO_REEMBOLSADO):
        case(IW_ERP_ESTADO_PEDIDO_CANCELADO):
            break;
    }
    $class .= ' status-' . $item->status;
    $total += $item->total;
    ?><tr class="<?php echo  $class ?>">
        <td>
            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
        </td>
        <td>
            <a href="<?php echo  JRoute::_('index.php?option=com_quipu&task=order.edit&id=' . $item->id); ?>" title="<?php echo  JText::_('COM_QUIPU_EDIT'); ?>">
                <?php echo $item->order_number; ?>
            </a>
        </td>
        <td>
            <?php echo IWDate::_($item->order_date); ?>
        </td>
        <td>
            <a href="<?php echo  JRoute::_('index.php?option=com_quipu&task=customer.edit&id=' . $item->customer_id) ?>" title="<?php echo  $item->customer_name ?>">
                <?php echo $item->customer_name; ?>
            </a>
        </td>
        <td class="number">
            <?php echo IWUtils::fmtEuro($item->total); ?>
        </td>
        <td>
            <?php
            if ($item->status == IW_ERP_ESTADO_PEDIDO_FACTURADO) {
                $links = array();
                foreach (explode(' ', $item->invoice_id) as $iID) {
                    $links[] = '<a class="btn btn-mini" href="' . JRoute::_('index.php?option=com_quipu&task=invoice.edit&id=' . $iID) . '" title="' . JText::_('COM_QUIPU_ORDER_VIEW_INVOICE') . '">' .                            
                            JText::_("COM_QUIPU_ORDER_STATUS_$item->status") .
                            '</a>';
                }
                echo implode(' ', $links);
            }
            else{
                echo JText::_("COM_QUIPU_ORDER_STATUS_$item->status");
            }
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


