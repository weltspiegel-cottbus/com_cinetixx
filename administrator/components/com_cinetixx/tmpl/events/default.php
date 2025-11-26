<?php
defined('_JEXEC') or die;

use Joomla\CMS\Router\Route;
?>
<table class="table">
	<thead>
	<tr>
		<th>Event</th>
		<th>Trailer (YouTube ID)</th>
        <th>Cinetixx Movie ID</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($this->items as $i => $item) : ?>
		<tr>
            <td><?php echo $item->cinetixxTitle; ?></td>
			<th scope="row">
				<a href="<?= Route::_("index.php?option=com_cinetixx&task=event.edit&id=" . $item->id) ?>" title="<?= \Joomla\CMS\Language\Text::_('JACTION_EDIT') ?>">
					<?= $this->escape($item->trailer_url); ?>
				</a>
			</th>
            <td><?php echo $item->event_id; ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
