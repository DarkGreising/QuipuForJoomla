<?php
defined('_JEXEC') or die('Restricted Access');
$app = JFactory::getApplication();
?>
<script language="JavaScript">
    window.addEvent('domready',function(){
        var mooGenda = new MooGenda({
            id:'thisMonth',
            dateObject:new Date(),
            where:'a6widget_agenda',
            header:'<?php echo $this->widget['title']?>',
            state:key.MONTH,
            h_start:8,
            h_stop:24,
            getEventUrl:'<?php echo JRoute::_($this->widget['dataurl_read'])?>',
            storeEventUrl:'<?php echo JRoute::_($this->widget['dataurl_write'])?>'
        });
        mooGenda.write();
    });
</script>
<h2><?php echo  JText::_($this->widget['title']) ?></h2>
<div class="widget-body">
<div id="a6widget_agenda"></div>
</div>