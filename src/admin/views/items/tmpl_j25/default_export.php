<div class="iw-export">
<?php

defined('_JEXEC') or die('Restricted access');
		$link = JRoute::_('index.php?option=' . IWRequest::getCmd('option') .'&view=' . IWRequest::getCmd('view') . '&format=excel');
		$text = JText::_('COM_QUIPU_EXPORT_EXCEL');
		?><a href="<?php echo $link?>" title="<?php echo $text?>"><?php echo $text?></a>
</div>

