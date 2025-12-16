<?php

/**
 * @package     Weltspiegel\Component\Weltspiegel
 *
 * @copyright   Weltspiegel Cottbus
 * @license     MIT; see LICENSE file
 */

\defined('_JEXEC') or die;

/**
 * Layout variables
 * -----------------
 * @var   string  $showId      The show ID for booking
 * @var   string  $label       The link label (e.g., show time)
 * @var   array   $options     Additional options (class, etc.)
 */

$showId = $displayData['showId'] ?? '';
$label = $displayData['label'] ?? '';
$options = $displayData['options'] ?? [];

// Build booking URL
$bookingUrl = 'https://www.kinoheld.de/kino-cottbus/filmtheater-weltspiegel/vorstellung/' . $showId . '?mode=widget#panel-seats';

// CSS classes - semantic only
$cssClass = 'booking-link';
if (!empty($options['class'])) {
    $cssClass .= ' ' . $options['class'];
}

?>
<a href="<?= htmlspecialchars($bookingUrl) ?>"
   class="<?= $cssClass ?>"
   target="_blank"
   rel="noopener noreferrer"><?= htmlspecialchars($label) ?></a>
