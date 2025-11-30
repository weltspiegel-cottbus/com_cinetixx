<?php
\defined('_JEXEC') or die;

use Joomla\CMS\Router\Route;

?>
<div>
	<h1><?php echo $this->escape($this->title); ?></h1>

	<div class="d-flex flex-column gap-3">
		<?php foreach ($this->items as $id => $event) : ?>

			<?php
			$detailRoute = Route::_('index.php?option=com_cinetixx&view=event&event_id=' . $id);
			?>
			<div>
				<a class="h3" href="<?= $detailRoute ?>"><?= $event->title ?></a>
			</div>
		<?php endforeach; ?>

	</div>

</div>