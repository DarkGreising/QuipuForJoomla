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
        <link rel="stylesheet" href="<?php echo  $route ?>/assets/css/quipu.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo $root?>administrator/templates/system/css/system.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo $root?>administrator/templates/bluestork/css/template.css" type="text/css" />
        <script src="<?php echo $root?>media/system/js/mootools-core.js" type="text/javascript"></script>
        <script src="<?php echo $root?>media/system/js/core.js" type="text/javascript"></script>
        <script src="<?php echo $root?>media/system/js/mootools-more.js" type="text/javascript"></script>
        <script src="<?php echo $root?>media/system/js/modal.js" type="text/javascript"></script>
        <script src="<?php echo  $route ?>/assets/js/iw_behavior.js" type="text/javascript"></script>
        <script src="<?php echo  $route ?>/assets/js/iw_functions.js" type="text/javascript"></script>
        <script src="<?php echo  $route ?>/assets/js/invoice.js" type="text/javascript"></script>
        <script type="text/javascript">
            window.iw = {i18n:{}};window.iw.i18n.pleasewait = "Un momento...";
        </script>
        <!--[if IE 7]>
        <link href="templates/bluestork/css/ie7.css" rel="stylesheet" type="text/css" />
        <![endif]-->

        <!--[if gte IE 8]>
        <link href="templates/bluestork/css/ie8.css" rel="stylesheet" type="text/css" />
        <![endif]-->
    </head>
    <body>
        <div class="fltrt">
            <a 
                id="1826693594-1" 
                class="ajx quipu-payment" 
                href="<?php echo  JRoute::_('index.php?option=' . $option . '&task=api.invoice&invoice[paid]=true&invoice[create-movement]=true&invoice[id]=' . $this->invoice->id, false) ?>" 
                data-method="post" 
                data-confirm="<?php echo  JText::sprintf('COM_QUIPU_REGISTER_PAYMENT_CONFIRM', $this->bank->name) ?>" 
                data-postreload="1">
                    <?php echo  JText::_('COM_QUIPU_REGISTER_PAYMENT') ?>
            </a> 
        </div>        
        <div id="payment-bank-activities" class="clrrt">
            <?php if(is_array($this->activities) && count($this->activities)):?>
            <p><?php echo JText::sprintf('COM_QUIPU_PAYMENT_CHOOSE_ACTIVITIES',$this->bank->name)?></p>
            <form action="<?php echo  JRoute::_('index.php?option=' . $option . '&task=api.invoice&invoice[paid]=true&invoice[id]=' . $this->invoice->id, false) ?>" method="post" name="qForm" id="frm-choose-activities"> 
                <fieldset class="activities">
                    <ul class="checklist">
                        <?php foreach ($this->activities as $item): ?>
                            <li>
                                <input type="checkbox" name="invoice[payment-movements][]" value="<?php echo  $item->id ?>" />
                                <?php echo  IWDate::_($item->activity_date) . ' - ' . $item->description . ': ' . IWUtils::fmtEuro($item->amount, false) ?>
                            </li>
                        <?php endforeach; ?>                    
                    </ul>
                </fieldset>
                <div class="buttons">
                    <input id="bt-choose-activities" type="button" name="s" value="<?php echo  JText::_('COM_QUIPU_OK') ?>" />
                </div>
            </form>
            <?php else:?>
            <p><?php echo JText::sprintf('COM_QUIPU_PAYMENT_NO_ACTIVITIES',$this->bank->name)?></p>
            <?php endif;?>
        </div>
    </body>
</html>
