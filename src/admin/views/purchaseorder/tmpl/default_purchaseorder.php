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
$estilo_celda_datos = 'width="45%" style="color:#000000;"';
$estilo_titulo_detalle = 'style="font-weight:bold;color:#000000;border-bottom:1px solid #111111;"';
$estilo_descripcion = '';
$estilo_tabla_detalle = '';
$estilo_titulo = '';
?>
<table style="margin:0;padding:0;">
    <tr>
        <td style="margin:0;padding:0;">
            <table width="100%" style="font-size:9pt;" align="left" cellpadding="1mm">
            <tr>
            <th colspan="2" style="font-weight:bold;color:#000000;border:1px solid gray"><?php echo $this->config->company_name?></th>
            </tr>
            <tr>
            <td width="30%"><?php echo JText::_('COM_QUIPU_VAT_NO')?>:</td><td><?php echo $this->config->vatno?></td>
            </tr><tr>
            <td><?php echo JText::_('COM_QUIPU_ADDRESS')?>:</td><td><?php echo $this->config->address?></td>
            </tr><tr>
            <td><?php echo JText::_('COM_QUIPU_ZIP_CODE')?>:</td><td><?php echo $this->config->zip?></td>
            </tr><tr>
            <td><?php echo JText::_('COM_QUIPU_STATE')?>:</td><td><?php echo $this->config->state?></td>
            </tr><tr>
            <td><?php echo JText::_('COM_QUIPU_PHONE')?>:</td><td><?php echo $this->config->phone?></td>
            </tr><tr>
            <td><?php echo JText::_('COM_QUIPU_EMAIL')?>:</td><td><?php echo $this->config->email?></td>
            </tr>
            </table>
        </td>
        <td>
            <table width="100%" style="font-size:9pt;" align="left" cellpadding="1mm">
                <tr>
                    <th colspan="2" style="font-weight:bold;color:#000000;border:1px solid gray"><?php echo JText::_('COM_QUIPU_ORDER_DATA')?></th>
                </tr>
                <tr>
                    <td <?php echo  $estilo_titulo_datos ?>><?php echo  JText::_('COM_QUIPU_ORDER_NUMBER') ?></td>
                    <td <?php echo  $estilo_celda_datos ?>><b><?php echo  $this->item->order_number ?></b></td>
                </tr>
                <tr>
                    <td <?php echo  $estilo_titulo_datos ?>><?php echo  JText::_('COM_QUIPU_ORDER_DATE') ?></td>
                    <td <?php echo  $estilo_celda_datos ?>><?php echo  IWDate::_($this->item->order_date) ?></td>
                </tr>
                <?php if($this->item->supplier_reference):?>
                <tr>
                    <td <?php echo  $estilo_titulo_datos ?>><?php echo  JText::_('COM_QUIPU_SUPPLIER_REF') ?></td>
                    <td <?php echo  $estilo_celda_datos ?>><?php echo  $this->item->supplier_reference ?></td>
                </tr>
                <?php endif;?>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2" >            
            <table width="100%" cellpadding="1mm">
                <tr>
                    <th colspan="2" style="font-weight:bold;color:#000000;border:1px solid gray"><b><?php echo JText::_('COM_QUIPU_SUPPLIER')?></b></th>
                </tr>
                
                <tr>
                    <td width="35mm" <?php echo  $estilo_titulo ?>><?php echo  JText::_('COM_QUIPU_SUPPLIER') ?></td>
                    <td><?php echo  $this->supplier->company_name ?></td>
                </tr>
                <tr>
                    <td <?php echo  $estilo_titulo ?>><?php echo  JText::_('COM_QUIPU_VAT_NO') ?></td>
                    <td><?php echo  $this->supplier->vatno ?></td>
                </tr>
                <tr>
                    <td <?php echo  $estilo_titulo ?>><?php echo  JText::_('COM_QUIPU_ADDRESS') ?></td>
                    <td><?php echo  $this->supplier->address ?></td>
                </tr>
                <tr>
                    <td <?php echo  $estilo_titulo ?>><?php echo  JText::_('COM_QUIPU_PHONE') ?></td>
                    <td><?php echo  $this->supplier->phone ?></td>
                </tr>
                <tr>
                    <td <?php echo  $estilo_titulo ?>><?php echo  JText::_('COM_QUIPU_EMAIL') ?></td>
                    <td><?php echo  $this->supplier->email ?></td>
                </tr>                
            </table>
        </td>
    </tr>
</table>
            

<p></p>

<table <?php echo  $estilo_tabla_detalle ?> cellpadding="2mm">
    <tr>
        <th width="30%" <?php echo  $estilo_titulo_detalle ?>><?php echo  JText::_('COM_QUIPU_ROW_DESCRIPTION') ?></th>
        <th width="10%" <?php echo  $estilo_titulo_detalle ?> align="right"><?php echo  JText::_('COM_QUIPU_UNIT_PRICE') ?></th>
        <th width="6%" <?php echo  $estilo_titulo_detalle ?> align="right"><?php echo  JText::_('COM_QUIPU_UNITS') ?></th>
        <th width="10%" <?php echo  $estilo_titulo_detalle ?> align="right"><?php echo  JText::_('COM_QUIPU_DISCOUNT') ?></th>
        <th width="17%" <?php echo  $estilo_titulo_detalle ?> align="right"><?php echo  JText::_('COM_QUIPU_BASE_PRICE') ?></th>
        <th width="10%" <?php echo  $estilo_titulo_detalle ?> align="right"><?php echo  JText::_('COM_QUIPU_TAX') ?></th>
        <th width="17%" <?php echo  $estilo_titulo_detalle ?> align="right"><?php echo  JText::_('COM_QUIPU_TOTAL') ?></th>
    </tr>
    <?php foreach ($this->details as $row):?>
        <tr>
            <td <?php echo  $estilo_descripcion ?>><?php echo  $row->item ?>
                <?php if($row->memo):?>
                <p style="color:darkgray"><i><?php echo $row->memo?></i></p>
                <?php endif;?>
            </td>
            <td <?php echo  $estilo_numero ?>><font face="monospace"><?php echo  IWUtils::fmtEuro($row->unit_price, false) ?></font></td>
            <td <?php echo  $estilo_numero ?>><font face="monospace"><?php echo  (int)$row->units ?></font></td>
            <td <?php echo  $estilo_numero ?>><font face="monospace"><?php echo  (int)$row->discount ?>%</font></td>
            <td <?php echo  $estilo_numero ?>><font face="monospace"><b><?php echo  IWUtils::fmtEuro($row->base, false) ?></b></font></td>       
            <td <?php echo  $estilo_numero ?>><font face="monospace"><?php echo  $row->tax ?>%</font></td>
            <td <?php echo  $estilo_numero ?>><font face="monospace"><b><?php echo  IWUtils::fmtEuro($row->total, false) ?></b></font></td>       
        </tr>
    <?php endforeach; ?>
        
        <tr>
            <td colspan="5" <?php echo  $estilo_total2 ?>><?php echo JText::_('COM_QUIPU_BASE_PRICE')?></td>
            <td colspan="2" <?php echo  $estilo_numero2 ?>><font face="monospace"><?php echo  IWUtils::fmtEuro($this->item->base) ?></font></td>
        </tr>    
        <tr>
            <td colspan="5" <?php echo  $estilo_total2 ?>><?php echo JText::_('COM_QUIPU_TAX')?></td>
            <td colspan="2" <?php echo  $estilo_numero2 ?>><font face="monospace"><?php echo  IWUtils::fmtEuro($this->item->total_tax) ?></font></td>
        </tr>    
        <tr>
            <td colspan="5" <?php echo  $estilo_total1 ?>><?php echo JText::_('TOTAL')?></td>
            <td colspan="2" <?php echo  $estilo_numero_total1 ?>><font face="monospace"><b><?php echo  IWUtils::fmtEuro($this->item->total) ?></b></font></td>
        </tr>    
</table>

