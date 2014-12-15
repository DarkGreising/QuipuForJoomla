<div class="iw-import">
<?php

defined('_JEXEC') or die('Restricted access');
		$link = JRoute::_('index.php?option=' . IWRequest::getCmd('option') .'&t=' . IWRequest::getCmd('view') . '&view=import&tmpl=component');
		$text = JText::_('COM_QUIPU_IMPORT_CSV');
		?><a rel="{size: {x: 700, y: 500}, handler:'iframe'}" class="modal btn btn-mini" href="<?php echo $link?>" title="<?php echo $text?>"><i class="icon icon-upload"></i> <?php echo $text?></a>
</div>

