<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
$app =JFactory::getApplication();
$ofcPath = JURI::base() . 'components/com_quipu/assets/ofc/open-flash-chart.swf';
if(!array_key_exists('chart-type', $this->widget)){
    $this->widget['chart-type'] = '';
}
        
?>
<script type="text/javascript">    
    window.addEvent("domready", function(){   
        var swfObj = new Swiff('<?php echo $ofcPath?>', {
            id: '<?php echo $this->token?>-graph',
            width:<?php echo $this->widget['width']?>,
            height: <?php echo $this->widget['height']?>,
            properties:{ name:'<?php echo $this->widget['title']?>' },
            container: $('widget_<?php echo  $this->token ?>'),
            params:{ wmode: 'transparent' },
            vars:{ 'data-file':'<?php echo JRoute::_($this->widget['dataurl'].'&transient=true&r=' . rand(),false)?>', 'loading':'<?php echo JText::_('COM_QUIPU_LOADING')?>' } 
        });            
});		
</script>
<h2 class="widget-<?php echo $this->widget['group']?>"><?php echo  JText::_($this->widget['title']) ?></h2>
<div class="widget-body widget-<?php echo $this->widget['group']?>">
<?php if ($this->widget['info']): ?>
    <div class="widget-info"><?php echo  JText::_($this->widget['info']) ?></div>
<?php endif; ?>    
<div class="panel-widget-chart widget-chart-<?php echo $this->widget['chart-type']?>" id="widget_<?php echo  $this->token ?>">
    <?php echo  JText::_('COM_QUIPU_Cargando...') ?>
</div>
</div>