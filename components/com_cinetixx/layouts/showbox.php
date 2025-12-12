<?php

/**
 * Layout for displaying showtimes for next 7 days
 *
 * @package     Weltspiegel\Component\Cinetixx
 * @copyright   Weltspiegel Cottbus
 * @license     MIT
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Layout\LayoutHelper;

/**
 * @var object $event Event object with shows array
 */
$event = $displayData;

// Return early if no shows
if (empty($event->shows) || !is_array($event->shows)) {
    return;
}

// Get current date/time
$now = new DateTime();
$today = $now->format('Y-m-d');

// Get the first show date (shows are already ordered)
try {
    $firstShowDate = new DateTime($event->shows[0]->showStart);
} catch (Exception $e) {
    return; // No valid first show
}

// Determine which week to display
$weekOffset = 0;
$daysUntilFirstShow = $now->diff($firstShowDate)->days;

// If first show is 7+ days away, calculate which week to display
if ($daysUntilFirstShow >= 7) {
    $weekOffset = floor($daysUntilFirstShow / 7);
}

// Build array of 7 days for the target week
$showsByDay = [];
$targetDays = [];
$startDate = (clone $now)->modify("+{$weekOffset} weeks");

for ($i = 0; $i < 7; $i++) {
    $date = (clone $startDate)->modify("+{$i} days");
    $dayKey = $date->format('Y-m-d');
    $targetDays[$dayKey] = $date;
    $showsByDay[$dayKey] = [];
}

// Filter and group shows for the target week
foreach ($event->shows as $show) {
    try {
        $showDateTime = new DateTime($show->showStart);
        $showDate = $showDateTime->format('Y-m-d');

        // Only include shows from the target week
        if (isset($showsByDay[$showDate])) {
            $showsByDay[$showDate][] = $show;
        }
    } catch (Exception $e) {
        // Skip invalid dates
        continue;
    }
}

// Format date labels
$formatter = new IntlDateFormatter('de_DE', IntlDateFormatter::NONE, IntlDateFormatter::NONE);
$formatter->setPattern('EEE, dd.MM.');

?>
<div class="showbox mt-3">
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <?php foreach ($targetDays as $dayKey => $date): ?>
                    <th class="text-center">
                        <?php if ($dayKey === $today): ?>
                            Heute
                        <?php else: ?>
                            <?= $formatter->format($date) ?>
                        <?php endif; ?>
                    </th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?php foreach ($targetDays as $dayKey => $date): ?>
                    <td class="text-center align-top">
                        <?php if (!empty($showsByDay[$dayKey])): ?>
                            <?php foreach ($showsByDay[$dayKey] as $index => $show): ?>
                                <?php
                                $showDateTime = new DateTime($show->showStart);
                                ?>
                                <?php if ($index > 0): ?>
                                    <br>
                                <?php endif; ?>
                                <?= LayoutHelper::render('booking.link', [
                                    'showId' => $show->showId,
                                    'label' => $showDateTime->format('H:i'),
                                    'options' => []
                                ], JPATH_SITE . '/components/com_cinetixx/layouts') ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            &nbsp;
                        <?php endif; ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        </tbody>
    </table>
</div>
