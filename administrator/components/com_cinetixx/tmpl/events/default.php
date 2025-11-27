<?php
\defined('_JEXEC') or die;

use Joomla\CMS\Router\Route;
?>
<table class="table">
	<thead>
	<tr>
		<th>Event</th>
		<th>Trailer (YouTube ID)</th>
        <th>Cinetixx Event ID</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($this->items as $i => $item) : ?>
		<tr>
            <td><?php echo $item->cinetixxTitle; ?></td>
			<th scope="row">
				<a href="<?= Route::_("index.php?option=com_cinetixx&task=event.edit&id=" . $item->id . "&event_id=" . $item->event_id ) ?>" title="<?= \Joomla\CMS\Language\Text::_('JACTION_EDIT') ?>">
					<?php if ($item->trailer_id) : ?>
                        <?= $this->escape($item->trailer_id); ?>
                    <?php elseif ($item->cinetixxTrailerId) : ?>
                        <?= $this->escape($item->cinetixxTrailerId) . ' (Cinetixx)'; ?>
                    <?php else : ?>
                        Noch kein Trailer gesetzt
                    <?php endif; ?>
				</a>
			</th>
            <td><?php echo $item->event_id; ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
