<!-- COMIENZO BARRA DE FILTRO -->
<fieldset id="filter-bar">
    <div class="filter-dates onerow">
<?php

defined('_JEXEC') or die('Restricted access');
        echo '<div class="dateFrom"><span>' . JText::_('COM_QUIPU_Mostrar datos desde: ') . '</span>' . $this->calendar1 . '</div>';
        echo '<div class="dateTo"><span>' . JText::_('COM_QUIPU_hasta: ') . '</span>' . $this->calendar2 . '</div>';
        ?>
        <button type="submit" class="boton-fechas"><?php echo JText::_('COM_QUIPU_Ok'); ?></button>
    </div>

    <div class="filter-select fltrt">                                    
        
    </div>


</fieldset>
<div class="clr"> </div>
<!-- FIN BARRA DE FILTRO --> 
