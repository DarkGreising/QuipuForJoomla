<?php

defined('_JEXEC') or die('Restricted access');
$y = date('Y');
?><div class="iw-list-side-col" id="iw-financials">
    <div class="iw-list-side-col-content">
        <h2><?php echo  JText::_('COM_QUIPU_BALANCE_HISTORY') ?></h2> 

        <?php foreach ($this->financials->quarters as $q=>$data): ?>
            <h4><?php echo  JText::sprintf('COM_QUIPU_BALANCE_' . $q, $y) ?></h4>
            <?php if (is_array($data) && count($data)): ?>
                <ul>
                    <?php
                    foreach ($data as $k => $v) {
                        echo '<li >' . JText::_($k) . ': <span class="' . ($v < 0 ? 'negative ' : '') . 'number">' . IWUtils::fmtEuro($v) . '</span></li>';
                    }
                    ?>
                </ul> 
            <?php else: ?>
            <?php echo JText::_('COM_QUIPU_BALANCE_NODATA')?>
            <?php endif; ?>


        <?php endforeach; ?>
        <h3><?php echo  JText::_('COM_QUIPU_BALANCE_LASTYEAR') ?></h3> 
        <ul>
            <?php
            foreach ($this->financials->last as $k => $v) {
                echo '<li >' . JText::_($k) . ': <span class="' . ($v < 0 ? 'negative ' : '') . 'number">' . IWUtils::fmtEuro($v) . '</span></li>';
            }
            ?>
        </ul>               
    </div>
</div>
