<?php

/**
 * @package     Weltspiegel\Component\Cinetixx
 *
 * @copyright   Weltspiegel Cottbus
 * @license     MIT; see LICENSE file
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;

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

// Get component params for booking link type
$params = ComponentHelper::getParams('com_cinetixx');
$linkType = $params->get('booking_link_type', 'newtab');

// Build booking URL
$bookingUrl = 'https://www.kinoheld.de/kino-cottbus/filmtheater-weltspiegel/vorstellung/' . $showId . '?mode=widget#panel-seats';

// CSS classes - semantic only
$cssClass = 'booking-link';
if (!empty($options['class'])) {
    $cssClass .= ' ' . $options['class'];
}

// Build link attributes based on type
$attributes = [
    'href' => htmlspecialchars($bookingUrl),
    'class' => $cssClass,
    'data-show-id' => htmlspecialchars($showId),
    'data-booking-type' => $linkType
];

// Add target="_blank" for newtab type
if ($linkType === 'newtab') {
    $attributes['target'] = '_blank';
    $attributes['rel'] = 'noopener noreferrer';
}

// Build attributes string
$attrString = '';
foreach ($attributes as $key => $value) {
    $attrString .= ' ' . $key . '="' . $value . '"';
}

?>
<a<?= $attrString ?>><?= htmlspecialchars($label) ?></a>
