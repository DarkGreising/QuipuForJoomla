<?php
defined('_JEXEC') or die('Restricted access');
?><script type="text/javascript">

    jQuery(document).ready(function($){
        function onGridSelect(evt){        
            var id = evt.target.getDataByRow(evt.row).id;
            var url = '<?php echo  JRoute::_('index.php?option=com_quipu&task=order.edit&id=', false); ?>' + id;
            window.location.href=url;
        }

        var cmu = [{
                display: 'ID',
                name: 'id',
                dataType: 'number',
                width:100
            },
            {
                display: '<?php echo  JText::_('COM_QUIPU_NUMBER') ?>',
                name: 'order_number',
                dataType: 'string',
                width:100
            },
            {
                display: '<?php echo  JText::_('COM_QUIPU_CUSTOMER_REF') ?>',
                name: 'customer_reference',
                dataType: 'string',
                width:100
            },
            {
                display: '<?php echo  JText::_('COM_QUIPU_DATE') ?>',
                name: 'order_date',
                dataType: 'string',
                width:100
            },
            {
                display: '<?php echo  JText::_('COM_QUIPU_TOTAL') ?>',
                name: 'total',
                dataType: 'string',
                width:100
            },
            {
                display: '<?php echo  JText::_('COM_QUIPU_STATUS') ?>',
                name: 'status',
                dataType: 'string',
                width:100
            }
        ];         
        var o = $("#iw_grid").flexigrid({
            url: '<?php echo  JUri::root() . substr(JRoute::_('index.php?option=com_quipu&view=orders&format=json&filter_cliente=' . (int) $this->item->id, false), 1) ?>',
            autoload:true,
            dataType: 'json',
            colModel : cmu,
            width: 'auto',            
            height: 600
        });        
    });
    

</script>

<div id="iw_grid" style="width:100%"></div>
