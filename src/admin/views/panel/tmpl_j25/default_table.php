<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
$cols = $this->widget['cols'];
$names = array();
preg_match_all('/\"_[A-Z_]*\"/', $cols,$names);

foreach($names[0] as $n){
    $clean = substr($n, 1, strlen($n) - 2);
    $cols = str_replace($n,'"'. JText::_("COM_QUIPU_WIDGET$clean")  . '"',$cols);
}

?>
<script type="text/javascript">    
    window.addEvent("domready", function(){       	                
        grid_<?php echo $this->token?> = new omniGrid('widget_<?php echo $this->token?>', {
            columnModel: <?php echo $cols?>,
            url:"<?php echo  JRoute::_($this->widget['dataurl'],false) ?>",
            perPageOptions: [5,10,20],
            perPage:10,
            page:1,
            pagination:true,
            serverSort:false,
            showHeader: true,
            alternaterows: true,
            showHeader:true,
            sortHeader:false,
            resizeColumns:true,
            multipleSelection:false<?php if(IWBrowser::isIE7orIE8()):?>,            
            width:<?php echo $this->widget['width'] - 2?>,
            height:<?php echo $this->widget['height']?>
            <?php endif;?>
        });
        <?php if($this->widget['detailurl']):?>
        grid_<?php echo $this->token?>.addEvent('click', function(evt){
            id=evt.target.getDataByRow(evt.row).id;
            window.location.href='<?php echo JRoute::_($this->widget['detailurl'],false)?>' + id;
        });
        <?php endif;?>
    });		
</script>
<h2 class="widget-<?php echo $this->widget['group']?>"><?php echo JText::_($this->widget['title'])?></h2>
<div class="widget-body widget-<?php echo $this->widget['group']?>">
<?php if($this->widget['info']):?>
<div class="widget-info"><?php echo  JText::_($this->widget['info']) ?></div>
<?php endif;?>
<div class="panel-widget-table" id="widget_<?php echo $this->token?>"></div>
</div>