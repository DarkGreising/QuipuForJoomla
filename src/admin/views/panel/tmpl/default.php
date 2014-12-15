<?php
defined('_JEXEC') or die('Restricted access');

$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::base() . 'components/com_quipu/assets/css/panel.css');
?>
<?php if (!empty($this->sidebar)): ?>
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
    <?php else : ?>
        <div id="j-main-container">
        <?php endif; ?>
        <div id="workarea">  
            <div id="panel-widgets" class="widgets-<?php echo  $this->maxCols ?>-cols">

                <form action="<?php echo JURI::getInstance() ?>" method="post" name="adminForm" id="adminForm">

                    <?php

                    foreach ($this->widgets as $widget):
                        if ($widget['type'] != 'view'):
                            ?>
                            <div class="autoload widget w<?php echo  $widget['width'] . ' ' . $widget['class'] ?>" id="<?php echo  $widget['id'] ?>"></div>
                            <?php

                        else:
                            echo '<div class="' . $widget['class'] . ' widget-' . $widget['group'] . '" id="' . $widget['id'] . '">';
                            echo $this->loadTemplate('widget_' . $widget['tmpl']);
                            echo '</div>';
                        endif;
                        ?>
                    <?php endforeach; ?>    
                </form>
            </div>
            <script type="text/javascript">
                var timer;
                window.addEvent("domready", function(){
                    reloadWidgets();
                });
                function launchRefresh(){            
                    clearTimeout(timer);
                    timer = setTimeout('reloadWidgets();',1200);
                }
                function reloadWidgets(){
                    var u = "<?php echo  JRoute::_('index.php?option=com_quipu&view=panel&format=raw', false) ?>";
                    u += (u.indexOf("?")>0)?'&w=':'?w=';
                    Array.each($$('div.autoload'),function(item){                   
                        item.load(u + item.id,{evalScripts: true});                 
                    });
                    var spinner = document.id('spinner');
                    if(spinner)
                        spinner.hide();       
                }
                function addOptionToSelect(el, text, value, selected){
                    var op = new Option(text,value);
                    try
                    {
                        el.add(op, null);
                    }
                    catch (err)
                    {
                        el.add(op);
                    }
                    if(selected){
                        el.set('value',value);       
                    }        
                }        
            </script>
        </div>
    </div>
