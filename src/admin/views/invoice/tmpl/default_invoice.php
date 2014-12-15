<?php

defined('_JEXEC') or die('Restricted access');
$estilo_numero = 'style="text-align:right;"';
$estilo_numero_total1 = 'style="text-align:right;border-top:1pt solid #111111"';
$estilo_total = 'style="font-weight:bold;text-align:right;"';
$estilo_total1 = 'style="font-weight:bold;text-align:right;border-top:1pt solid #111111"';
$estilo_total2 = 'style="font-weight:bold;text-align:right;border-top:1pt solid #CCCCCC"';
$estilo_numero2 = 'style="text-align:right;border-top:1pt solid #CCCCCC"';
$estilo_total_factura = 'style="font-weight:bold;text-align:right;border-top:1px solid #bbb;"';
$estilo_titulo_datos = 'width="45%" style="font-weight:bold;color:#000000;"';
$estilo_titulo = '';
$estilo_celda_datos = 'width="45%" style="color:#000000;"';
$estilo_titulo_detalle = 'style="font-weight:bold;color:#000000;border-bottom:1px solid #111111;"';
$estilo_descripcion = '';
$estilo_tabla_detalle = '';

$is_refund = !empty($this->item->rectification_of_number);
?>
<table style="margin:0;padding:0;">
    <tr>
        <td style="margin:0;padding:0;">
            <table width="100%" style="font-size:9pt;" align="left" cellpadding="1mm">
                <tr>
                    <th colspan="2" style="font-weight:bold;color:#000000;border:1px solid gray"><?php echo  $this->config->company_name ?></th>
                </tr>
                <tr>
                    <td width="30%"><?php echo  JText::_('COM_QUIPU_VAT_NO') ?>:</td><td><?php echo  $this->config->vatno ?></td>
                </tr><tr>
                    <td><?php echo  JText::_('COM_QUIPU_ADDRESS') ?>:</td><td><?php echo  $this->config->address ?></td>
                </tr><tr>
                    <td><?php echo  JText::_('COM_QUIPU_ZIP_CODE') ?>:</td><td><?php echo  $this->config->zip ?></td>
                </tr><tr>
                    <td><?php echo  JText::_('COM_QUIPU_STATE') ?>:</td><td><?php echo  $this->config->state ?></td>
                </tr><tr>
                    <td><?php echo  JText::_('COM_QUIPU_PHONE') ?>:</td><td><?php echo  $this->config->phone ?></td>
                </tr><tr>
                    <td><?php echo  JText::_('COM_QUIPU_EMAIL') ?>:</td><td><?php echo  $this->config->email ?></td>
                </tr>
            </table>
        </td>
        <td>
            <table width="100%" style="font-size:9pt;" align="left" cellpadding="1mm">
                <tr>
                    <th colspan="2" style="font-weight:bold;color:#000000;border:1px solid gray"><?php echo  JText::_('COM_QUIPU_INVOICE_DATA') ?></th>
                </tr>
                <tr>
                    <td <?php echo  $estilo_titulo_datos ?>><?php echo  JText::_('COM_QUIPU_INVOICE_NUMBER') ?></td>
                    <td <?php echo  $estilo_celda_datos ?>><b><?php echo  $this->item->invoice_number ?></b></td>
                </tr>
                <?php if($this->item->rectification_of_number):?>
                <tr>
                    <td <?php echo  $estilo_titulo_datos ?>><?php echo  JText::_('COM_QUIPU_RECTIFIES_INVOICE') ?></td>
                    <td <?php echo  $estilo_celda_datos ?>><?php echo  $this->item->rectification_of_number ?></td>
                </tr>
                <?php endif;?>                
                <?php if($this->item->customer_reference):?>
                <tr>
                    <td <?php echo  $estilo_titulo_datos ?>><?php echo  JText::_('COM_QUIPU_CUSTOMER_REF') ?></td>
                    <td <?php echo  $estilo_celda_datos ?>><?php echo  $this->item->customer_reference ?></td>
                </tr>
                <?php endif;?>                
                <tr>
                    <td <?php echo  $estilo_titulo_datos ?>><?php echo  JText::_('COM_QUIPU_INVOICE_DATE') ?></td>
                    <td <?php echo  $estilo_celda_datos ?>><?php echo  IWDate::_($this->item->invoice_date) ?></td>
                </tr>
                <tr>
                    <td <?php echo  $estilo_titulo_datos ?>><?php echo  JText::_('COM_QUIPU_INVOICE_DUE_DATE') ?></td>
                    <td <?php echo  $estilo_celda_datos ?>><?php echo  IWDate::_($this->item->due_date) ?></td>
                </tr>
                <?php if($this->item->payment_method):?>
                <tr>
                    <td <?php echo  $estilo_titulo_datos ?>><?php echo  JText::_('COM_QUIPU_PAYMENT_METHOD') ?></td>
                    <td <?php echo  $estilo_celda_datos ?>><?php echo  $this->item->payment_method ?></td>
                </tr>                
                <?php endif;?>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <table width="100%" style="font-size:9pt;" align="left" cellpadding="1mm">
                <tr>
                    <th colspan="2" style="font-weight:bold;color:#000000;border:1px solid gray"><b><?php echo  JText::_('COM_QUIPU_CUSTOMER') ?></b></th>
                </tr>

                <tr>
                    <td width="35mm" <?php echo  $estilo_titulo ?>><?php echo  JText::_('COM_QUIPU_CUSTOMER') ?></td>
                    <td><?php echo  $this->item->customer ?></td>
                </tr>
                <tr>
                    <td <?php echo  $estilo_titulo ?>><?php echo  JText::_('COM_QUIPU_VAT_NO') ?></td>
                    <td><?php echo  $this->item->vatno ?></td>
                </tr>
                <tr>
                    <td <?php echo  $estilo_titulo ?>><?php echo  JText::_('COM_QUIPU_ADDRESS') ?></td>
                    <td><?php echo  $this->item->address ?></td>
                </tr>
                <tr>
                    <td <?php echo  $estilo_titulo ?>><?php echo  JText::_('COM_QUIPU_PHONE') ?></td>
                    <td><?php echo  $this->item->phone ?></td>
                </tr>
                <tr>
                    <td <?php echo  $estilo_titulo ?>><?php echo  JText::_('COM_QUIPU_EMAIL') ?></td>
                    <td><?php echo  $this->item->email ?></td>
                </tr>                
            </table>            
        </td>
    </tr>
</table>


<p></p>

<table <?php echo  $estilo_tabla_detalle ?> cellpadding="2mm">
    <tr>
        <th width="25%" <?php echo  $estilo_titulo_detalle ?>><?php echo  JText::_('COM_QUIPU_ROW_DESCRIPTION') ?></th>
        <th width="15%" <?php echo  $estilo_titulo_detalle ?> align="right"><?php echo  JText::_('COM_QUIPU_UNIT_PRICE') ?></th>
        <th width="10%" <?php echo  $estilo_titulo_detalle ?> align="right"><?php echo  JText::_('COM_QUIPU_UNITS') ?></th>
        <th width="10%" <?php echo  $estilo_titulo_detalle ?> align="right"><?php echo  JText::_('COM_QUIPU_DISCOUNT') ?></th>
        <th width="15%" <?php echo  $estilo_titulo_detalle ?> align="right"><?php echo  JText::_('COM_QUIPU_BASE_PRICE') ?></th>
        <th width="10%" <?php echo  $estilo_titulo_detalle ?> align="right"><?php echo  JText::_('COM_QUIPU_TAX') ?></th>
        <th width="15%" <?php echo  $estilo_titulo_detalle ?> align="right"><?php echo  JText::_('COM_QUIPU_TOTAL') ?></th>
    </tr>
    <?php foreach ($this->details as $row): ?>
        <tr>
            <td <?php echo  $estilo_descripcion ?>><?php echo  $row->item ?>
                <?php if ($row->memo): ?>
                    <p style="color:darkgray"><i><?php echo  $row->memo ?></i></p>
                <?php endif; ?>
            </td>
            <td <?php echo  $estilo_numero ?>><font face="monospace"><?php echo  IWUtils::fmtEuro($row->unit_price,false) ?></font></td>
            <td <?php echo  $estilo_numero ?>><font face="monospace"><?php echo  (int)$row->units ?></font></td>
            <td <?php echo  $estilo_numero ?>><font face="monospace"><?php echo  (int)$row->discount ?>%</font></td>
            <td <?php echo  $estilo_numero ?>><font face="monospace"><b><?php echo  IWUtils::fmtEuro($row->base,false) ?></b></font></td>       
            <td <?php echo  $estilo_numero ?>><font face="monospace"><?php echo  $row->tax ?>%</font></td>
            <td <?php echo  $estilo_numero ?>><font face="monospace"><b><?php echo  IWUtils::fmtEuro($row->total,false) ?></b></font></td>       
        </tr>
    <?php endforeach; ?>
    <?php if ($this->item->retentions > 0): ?>
        <tr>
            <td colspan="4" <?php echo  $estilo_total2 ?>><?php echo  JText::_('COM_QUIPU_RETENTIONS') ?></td>
            <td colspan="2" <?php echo  $estilo_numero2 ?>><font face="monospace">- <?php echo  $this->item->retentions ?>%</font></td>
            <td  <?php echo  $estilo_total2 ?>><font face="monospace"><b>- <?php echo  IWUtils::fmtEuro($this->item->total_retentions, false) ?></b></font></td>
        </tr>    
    <?php endif; ?>
    <tr>
        <td colspan="5" <?php echo  $estilo_total2 ?>><?php echo  JText::_('COM_QUIPU_TAX') ?></td>
        <td colspan="2" <?php echo  $estilo_total2 ?>><font face="monospace"><b><?php echo  IWUtils::fmtEuro($this->item->total_tax, false) ?></b></font></td>        
    </tr>     
    <tr>
        <td colspan="5" <?php echo  $estilo_total1 ?>><?php echo  JText::_('TOTAL') ?></td>
        <td colspan="2" <?php echo  $estilo_numero_total1 ?>><font face="monospace"><b><?php echo  IWUtils::fmtEuro($this->item->total) ?></b></font></td>
    </tr>
</table>
<?php if(isset($this->item->payment_account) && !$is_refund): ?>
<p>
    <?php echo  JText::sprintf('COM_QUIPU_PAYMENT_ACCOUNT', $this->item->payment_account->account_no) ?>
</p>
<p>
    <?php echo  JText::sprintf('COM_QUIPU_PAYMENT_WIRETRANSFER_INFO', $this->item->invoice_number)?>
</p>
<?php endif; ?>



