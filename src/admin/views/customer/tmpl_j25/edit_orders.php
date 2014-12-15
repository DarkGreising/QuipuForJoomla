<?php
defined('_JEXEC') or die('Restricted access');

$doc = JFactory::getDocument();
$doc->addScript(JURI::root() . '/administrator/components/com_quipu/assets/js/omnigrid.js');
$doc->addStyleSheet(JURI::root() . '/administrator/components/com_quipu/assets/css/omnigrid.css');
?><script type="text/javascript">


    window.addEvent('domready', function(){
        var container_width = $('iw_grid').getSize().x;
        function onGridSelect(evt){        
            var id = evt.target.getDataByRow(evt.row).id;
            var url = '<?php echo JRoute::_('index.php?option=com_quipu&task=order.edit&id=',false);?>' + id;
            window.location.href=url;
        }
        var col_width = container_width / 6;

        var cmu = [{
            header: 'ID',
            dataIndex: 'id',
            dataType: 'number',
            width:col_width
        },
        {
            header: '<?php echo JText::_('COM_QUIPU_NUMBER')?>',
            dataIndex: 'order_number',
            dataType: 'string',
            width:col_width
        },
        {
            header: '<?php echo JText::_('COM_QUIPU_CUSTOMER_REF')?>',
            dataIndex: 'customer_reference',
            dataType: 'string',
            width:col_width
        },
        {
            header: '<?php echo JText::_('COM_QUIPU_DATE')?>',
            dataIndex: 'order_date',
            dataType: 'string',
            width:col_width
        },
        {
            header: '<?php echo JText::_('COM_QUIPU_TOTAL')?>',
            dataIndex: 'total',
            dataType: 'string',
            width:col_width
        },
        {
            header: '<?php echo JText::_('COM_QUIPU_STATUS')?>',
            dataIndex: 'status',
            dataType: 'string',
            width:col_width
        }
    ]; 
    

    datagrid = new omniGrid('iw_grid', {
        columnModel: cmu,
        url: '<?php echo JRoute::_('index.php?option=com_quipu&view=orders&format=json&filter_cliente=' . (int) $this->item->id,false)?>',
        perPageOptions: [10, 20, 50, 100, 200],
        perPage: 10,
        page: 1,
        pagination: true,
        serverSort: true,
        showHeader: true,
        alternaterows: true,
        showHeader: true,
        sortHeader: false,
        resizeColumns: false,
        multipleSelection: false,


        width: container_width,
        height: 400
    });

    datagrid.addEvent('click', onGridSelect);

    });

</script>

<div id="iw_grid" style="width:100%"></div>
