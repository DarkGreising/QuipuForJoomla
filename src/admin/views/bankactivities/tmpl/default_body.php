<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): ?>
	<tr class="row<?php echo  $i % 2; ?>">
		<td>
			<?php echo  JHtml::_('grid.id', $i, $item->id); ?>
		</td>
                <td>
                    <a href="<?php echo JRoute::_('index.php?option=com_quipu&task=bankactivity.edit&id='.$item->id);?>" title="<?php echo JText::_('COM_QUIPU_EDIT');?>">
                        <?php echo  $item->description; ?>
                    </a>
                    <?php if($item->paid_invoices):?>
                    <div class="row-detail"><?php echo JText::sprintf('COM_QUIPU_PAID_INVOICES',$item->paid_invoices)?></div>
                    <?php endif;?>
                </td>
                <td>
			<?php echo  $item->bankaccount_name; ?>
		</td>
                <td>
			<?php echo  IWDate::_($item->activity_date); ?>
		</td>
                <td>
			<?php echo  IWDate::_($item->value_date); ?>
		</td>
                <td class="<?php echo $item->amount<0?'negative ':''?>number">
			<?php echo  IWUtils::fmtEuro($item->amount,false); ?>
		</td>
                <td class="<?php echo $item->balance<0?'negative ':''?>number">
			<?php echo  IWUtils::fmtEuro($item->balance,false); ?>
		</td>
	</tr>
<?php endforeach; ?>

