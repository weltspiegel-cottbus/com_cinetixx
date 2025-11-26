<?php

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;

$wa = $this->document->getWebAssetManager();

$wa->useScript('keepalive');
$wa->useScript('form.validate');
?>
<form action="<?php echo Route::_('index.php?option=com_cinetixx&layout=edit&id=' . (int) $this->item->id); ?>"
      method="post" name="adminForm" id="item-form" class="form-validate">

	<?php echo $this->form->renderField('event_id'); ?>
	<?php echo $this->form->renderField('trailer_url'); ?>

	<input type="hidden" name="task" value="event.edit" />
	<?php echo HTMLHelper::_('form.token'); ?>
</form>
