<?php
defined('_JEXEC') or die;

use Joomla\CMS\Router\Route;
?>
<table class="table">
	<thead>
	<tr>
		<th>Event</th>
		<th>Trailer (YouTube ID)</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($this->items as $i => $item) : ?>
		<tr>
			<td><?php echo $item->event_id; ?></td>
			<th scope="row">
				<a href="<?= Route::_("index.php?option=com_cinetixx&task=event.edit&id=" . $item->id) ?>" title="<?= \Joomla\CMS\Language\Text::_('JACTION_EDIT') ?>">
					<?= $this->escape($item->trailer_url); ?>
				</a>
			</th>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
