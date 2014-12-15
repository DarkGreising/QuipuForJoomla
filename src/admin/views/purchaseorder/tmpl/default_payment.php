<?php

defined('_JEXEC') or die('Restricted access');
$root = JURI::root();
$option = IWRequest::getCmd('option');
$route = $root . 'administrator/components/' . $option;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es-es" lang="es-es" dir="ltr" >
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Quipu</title>
        <link href="/administrator/templates/isis/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
        <link rel="stylesheet" href="<?php echo  $root ?>administrator/components/com_quipu/assets/css/dyntable.css" type="text/css" />
        <link rel="stylesheet" href="/media/system/css/modal.css" type="text/css" />
        <link rel="stylesheet" href="/media/system/css/calendar-jos.css" type="text/css"  title="Green"  media="all" />
        <link rel="stylesheet" href="<?php echo  $root ?>administrator/components/com_quipu/assets/css/flexigrid.pack.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo  $root ?>administrator/components/com_quipu/assets/css/quipu_j3.css" type="text/css" />
        <link rel="stylesheet" href="templates/isis/css/template.css" type="text/css" />
        <script src="<?php echo  $root ?>media/jui/js/jquery.min.js" type="text/javascript"></script>
        <script src="<?php echo  $root ?>media/jui/js/jquery-noconflict.js" type="text/javascript"></script>
        <script src="<?php echo  $root ?>media/jui/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?php echo  $root ?>administrator/components/com_quipu/assets/js/invoice_j3.js" type="text/javascript"></script>
        <script src="<?php echo  $root ?>media/system/js/mootools-core.js" type="text/javascript"></script>
        <script src="<?php echo  $root ?>media/system/js/core.js" type="text/javascript"></script>
        <script src="<?php echo  $root ?>media/system/js/mootools-more.js" type="text/javascript"></script>
        <script src="<?php echo  $root ?>media/system/js/modal.js" type="text/javascript"></script>
        <script src="<?php echo  $root ?>media/system/js/calendar.js" type="text/javascript"></script>
        <script src="<?php echo  $root ?>media/system/js/calendar-setup.js" type="text/javascript"></script>
        <script src="<?php echo  $root ?>administrator/components/com_quipu/assets/js/invoice_j3.js" type="text/javascript"></script>
        <script src="<?php echo  $root ?>administrator/components/com_quipu/assets/js/iw_behavior_j3.js" type="text/javascript"></script>
        <script src="<?php echo  $root ?>administrator/components/com_quipu/assets/js/flexigrid.pack.js" type="text/javascript"></script>

    </head>
    <body>
        <div class="btn-toolbar clearfix">
            <a 
                id="1826693594-1" 
                class="ajx quipu-payment btn btn-small pull-right" 
                href="<?php echo  JRoute::_('index.php?option=' . $option . '&task=api.purchaseorder&purchaseorder[paid]=true&purchaseorder[create-movement]=true&purchaseorder[id]=' . $this->item->id, false) ?>" 
                data-method="post" 
                data-confirm="<?php echo  JText::sprintf('COM_QUIPU_REGISTER_PAYMENT_CONFIRM', $this->bank->name) ?>" 
                data-postreload="1">
                    <?php echo  JText::_('COM_QUIPU_REGISTER_PAYMENT') ?>
            </a> 
        </div>        
        <div id="payment-bank-activities" class="clrrt">
            <?php if (is_array($this->activities) && count($this->activities)): ?>
                <p><?php echo  JText::sprintf('COM_QUIPU_PAYMENT_CHOOSE_ACTIVITIES', $this->bank->name) ?></p>
                <form action="<?php echo  JRoute::_('index.php?option=' . $option . '&task=api.purchaseorder&purchaseorder[paid]=true&purchaseorder[id]=' . $this->item->id, false) ?>" method="post" name="qForm" id="frm-choose-activities"> 
                    <fieldset class="activities">
                        <ul class="checklist">
                            <?php foreach ($this->activities as $item): ?>
                                <li>
                                    <label class="checkbox">
                                        <input type="checkbox" name="purchaseorder[payment-movements][]" value="<?php echo  $item->id ?>" />
                                        <?php echo  IWDate::_($item->activity_date) . ' - ' . $item->description . ': ' . IWUtils::fmtEuro($item->amount, false) ?>
                                    </label>
                                </li>
                            <?php endforeach; ?>                    
                        </ul>
                    </fieldset>
                    <div class="buttons">
                        <input id="bt-choose-activities" type="button" class="btn btn-primary" name="s" value="<?php echo  JText::_('COM_QUIPU_OK') ?>" data-loading-text="<?php echo  JText::_('COM_QUIPU_PLEASEWAIT') ?>" />
                    </div>
                </form>
            <?php else: ?>
                <p><?php echo  JText::sprintf('COM_QUIPU_PAYMENT_NO_ACTIVITIES', $this->bank->name) ?></p>
            <?php endif; ?>
        </div>
    </body>
</html>
