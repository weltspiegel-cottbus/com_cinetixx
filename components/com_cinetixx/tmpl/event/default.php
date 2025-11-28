<?php

\defined('_JEXEC') or die;

use Weltspiegel\Component\Cinetixx\Administrator\Helper\YouTubeHelper;

$event = $this->item;

?>
<h1><?= $event->title ?></h1>

<?php if (!empty($event->trailerId)): ?>
    <div>
        <iframe
                width="522"
                height="288"
                src="<?= YouTubeHelper::generateTrailerLink($event->trailerId) ?>"
                allowfullscreen
                referrerpolicy="strict-origin-when-cross-origin"
        ></iframe>
    </div>
<?php endif; ?>

