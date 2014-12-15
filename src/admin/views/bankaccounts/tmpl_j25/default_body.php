<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach ($this->items as $i => $item): ?>
    <tr class="row<?php echo $i % 2; ?>">
        <td>
            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
        </td>
        <td>
            <a href="<?php echo  JRoute::_('index.php?option=com_quipu&task=bankaccount.edit&id=' . $item->id); ?>" title="<?php echo  JText::_('COM_QUIPU_EDIT'); ?>">
                <?php echo $item->name; ?>
            </a>
        </td>
        <td>
            <?php echo $item->account_no; ?>
        </td>
        <td>
            <?php echo $item->balance; ?>
        </td>
        <td>
            <a href="<?php echo  JRoute::_('index.php?option=com_quipu&view=bankactivities&filter_bankaccount=' . $item->id); ?>" title="<?php echo  JText::_('COM_QUIPU_BANK_ACTIVIES'); ?>">
                <?php echo  JText::_('COM_QUIPU_BANK_ACTIVIES'); ?>
            </a>
        </td>

    </tr>
<?php endforeach; ?>

