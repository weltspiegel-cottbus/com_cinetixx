<?php
defined('_JEXEC') or die;
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
			<td><?php echo $item->trailer_url; ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
