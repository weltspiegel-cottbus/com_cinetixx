<?php

/**
 * Layout for displaying showtimes with sliding 7-day viewport navigation
 *
 * @package     Weltspiegel\Component\Weltspiegel
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

// Get current date/time (use Europe/Berlin timezone to match show data)
$timezone = new DateTimeZone('Europe/Berlin');
$now = new DateTime('now', $timezone);
$today = $now->format('Y-m-d');

// Initial date range: today + 7 days
$dateFrom = (new DateTime('now', $timezone))->setTime(0, 0);
$dateTo = (clone $dateFrom)->modify('+7 days');
$startingFromToday = true;

// Check if first show is beyond current week
if (!empty($event->shows)) {
    try {
        $firstShowStart = new DateTime($event->shows[0]->showStart);

        if ($firstShowStart > $dateTo) {
            // Jump to the week containing the first show, aligned to current weekday
            $firstShowtimeWeekday = $firstShowStart->format('D');
            $currentWeekday = (new DateTime())->format('D');

            if ($firstShowtimeWeekday !== $currentWeekday) {
                $firstShowStart->modify('last ' . $currentWeekday);
            }
            $firstShowStart->setTime(0, 0);

            $dateFrom = $firstShowStart;
            $dateTo = (clone $dateFrom)->modify('+7 days');
            $startingFromToday = false;
        }
    } catch (Exception $e) {
        // Continue with default date range
    }
}

// Get the last show date (shows are sorted)
$lastShow = end($event->shows);
$lastShowDate = new DateTime($lastShow->showStart);

// Build viewports (weeks with shows)
$viewports = [];

// Helper function to count shows in a date range
$countShowsInRange = function($shows, $from, $to) {
    $count = 0;
    foreach ($shows as $show) {
        try {
            $showtime = new DateTime($show->showStart);
            if ($showtime > $from && $showtime < $to) {
                $count++;
            }
        } catch (Exception $e) {
            continue;
        }
    }
    return $count;
};

// Build weeks until we pass the last show (skip empty weeks)
while ($dateFrom <= $lastShowDate) {
    // Check if this week has any shows
    $showCount = $countShowsInRange($event->shows, $dateFrom, $dateTo);

    // Only create viewport if there are shows this week
    if ($showCount > 0) {
    $viewport = [
        'dateFrom' => clone $dateFrom,
        'dateTo' => clone $dateTo,
        'days' => [],
        'isFirstWeek' => $startingFromToday,
        'label' => $dateFrom->format('d.m.') . ' - ' . (clone $dateTo)->modify('-1 day')->format('d.m.Y')
    ];

    // Build 7 days for this viewport
    $date = clone $dateFrom;
    for ($i = 0; $i < 7; $i++) {
        $midnight = (clone $date)->modify('+1 day');
        $dayKey = $date->format('Y-m-d');

        $dayShows = [];
        foreach ($event->shows as $show) {
            try {
                $showtime = new DateTime($show->showStart);
                if ($showtime > $date && $showtime < $midnight) {
                    $dayShows[] = $show;
                }
            } catch (Exception $e) {
                continue;
            }
        }

        $viewport['days'][$dayKey] = [
            'date' => clone $date,
            'shows' => $dayShows
        ];

        $date->modify('+1 day');
    }

        $viewports[] = $viewport;
    }

    // Move to next week (whether we added a viewport or not)
    $dateFrom = $dateTo;
    $dateTo = (clone $dateTo)->modify('+7 days');
    $startingFromToday = false;
}

// Return early if no viewports (no shows in any range)
if (empty($viewports)) {
    return;
}

// Generate unique ID for this showbox instance
$showboxId = 'showbox-' . uniqid();

// Format date labels (with Europe/Berlin timezone)
$formatterDay = new IntlDateFormatter('de_DE', IntlDateFormatter::NONE, IntlDateFormatter::NONE, 'Europe/Berlin');
$formatterDay->setPattern('EEE');
$formatterDate = new IntlDateFormatter('de_DE', IntlDateFormatter::NONE, IntlDateFormatter::NONE, 'Europe/Berlin');
$formatterDate->setPattern('dd.MM.');

?>

<style>
.showbox-navigation {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.showbox-nav-btn {
    background: none;
    border: 1px solid #dee2e6;
    padding: 0.5rem 0.75rem;
    cursor: pointer;
    border-radius: 0.25rem;
    transition: all 0.2s;
}

.showbox-nav-btn:not(:disabled):hover {
    background-color: #f8f9fa;
    border-color: #adb5bd;
}

.showbox-nav-btn:disabled {
    opacity: 0.3;
    cursor: not-allowed;
}

.showbox-nav-btn svg {
    width: 1rem;
    height: 1rem;
    display: block;
}

.showbox-viewport {
    display: none;
}

.showbox-viewport.active {
    display: block;
}
</style>

<div class="showbox mt-3" id="<?= $showboxId ?>">
    <!-- Desktop: Horizontal Layout with Navigation -->
    <div class="d-none d-md-block">
        <?php if (count($viewports) > 1): ?>
            <div class="showbox-navigation">
                <button type="button" class="showbox-nav-btn" data-action="prev" aria-label="Vorherige Woche">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <span class="showbox-viewport-info fw-bold"></span>
                <button type="button" class="showbox-nav-btn" data-action="next" aria-label="Nächste Woche">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        <?php endif; ?>

        <?php foreach ($viewports as $viewportIndex => $viewport): ?>
            <div class="showbox-viewport <?= $viewportIndex === 0 ? 'active' : '' ?>" data-viewport="<?= $viewportIndex ?>" data-label="<?= htmlspecialchars($viewport['label']) ?>">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <?php $dayIndex = 0; ?>
                            <?php foreach ($viewport['days'] as $dayKey => $dayData): ?>
                                <th class="text-center">
                                    <?php if ($dayIndex === 0 && $viewport['isFirstWeek']): ?>
                                        Heute
                                    <?php else: ?>
                                        <?= $formatterDay->format($dayData['date']) ?><br><?= $formatterDate->format($dayData['date']) ?>
                                    <?php endif; ?>
                                </th>
                                <?php $dayIndex++; ?>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php foreach ($viewport['days'] as $dayKey => $dayData): ?>
                                <td class="text-center align-top">
                                    <?php if (!empty($dayData['shows'])): ?>
                                        <?php foreach ($dayData['shows'] as $index => $show): ?>
                                            <?php
                                            $showDateTime = new DateTime($show->showStart);
                                            $bookingStart = new DateTime($show->bookingStart);
                                            $bookingEnd = new DateTime($show->bookingEnd);
                                            $isBookable = ($now >= $bookingStart && $now <= $bookingEnd);
                                            ?>
                                            <?php if ($index > 0): ?>
                                                <br>
                                            <?php endif; ?>
                                            <?php if ($isBookable): ?>
                                                <?= LayoutHelper::render('booking.link', [
                                                    'showId' => $show->showId,
                                                    'label' => $showDateTime->format('H:i'),
                                                    'options' => ['class' => 'text-decoration-none']
                                                ], JPATH_SITE . '/components/com_weltspiegel/layouts') ?>
                                            <?php else: ?>
                                                <?= $showDateTime->format('H:i') ?>
                                            <?php endif; ?>
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
        <?php endforeach; ?>
    </div>

    <!-- Mobile: Vertical Layout (Single Table) -->
    <div class="d-md-none">
        <table class="table table-sm table-bordered">
            <tbody>
            <?php
            $isFirstDay = true;
            foreach ($viewports as $viewport):
                $dayIndex = 0;
                foreach ($viewport['days'] as $dayKey => $dayData):
                    if (!empty($dayData['shows'])):
            ?>
                <tr>
                    <td class="fw-bold">
                        <?php if ($isFirstDay && $viewport['isFirstWeek']): ?>
                            Heute
                        <?php else: ?>
                            <?= $formatterDay->format($dayData['date']) ?>, <?= $formatterDate->format($dayData['date']) ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php foreach ($dayData['shows'] as $show): ?>
                            <?php
                            $showDateTime = new DateTime($show->showStart);
                            $bookingStart = new DateTime($show->bookingStart);
                            $bookingEnd = new DateTime($show->bookingEnd);
                            $isBookable = ($now >= $bookingStart && $now <= $bookingEnd);
                            ?>
                            <?php if ($isBookable): ?>
                                <?= LayoutHelper::render('booking.link', [
                                    'showId' => $show->showId,
                                    'label' => $showDateTime->format('H:i'),
                                    'options' => ['class' => 'text-decoration-none']
                                ], JPATH_SITE . '/components/com_weltspiegel/layouts') ?>
                            <?php else: ?>
                                <?= $showDateTime->format('H:i') ?>
                            <?php endif; ?>
                            <?php if ($show !== end($dayData['shows'])): ?>
                                 |
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </td>
                </tr>
            <?php
                        $isFirstDay = false;
                    endif;
                    $dayIndex++;
                endforeach;
            endforeach;
            ?>
            </tbody>
        </table>
    </div>
</div>

<?php if (count($viewports) > 1): ?>
<script>
(function() {
    'use strict';

    const showboxId = '<?= $showboxId ?>';
    const showbox = document.getElementById(showboxId);

    if (!showbox) {
        return;
    }

    const viewports = showbox.querySelectorAll('.showbox-viewport');
    const prevBtn = showbox.querySelector('[data-action="prev"]');
    const nextBtn = showbox.querySelector('[data-action="next"]');
    const viewportInfo = showbox.querySelector('.showbox-viewport-info');

    if (!prevBtn || !nextBtn || !viewportInfo) {
        return;
    }

    let currentViewportIndex = 0;

    function updateDisplay() {
        // Hide all viewports
        viewports.forEach(viewport => viewport.classList.remove('active'));

        // Show current viewport
        if (viewports[currentViewportIndex]) {
            viewports[currentViewportIndex].classList.add('active');

            // Update viewport info label
            const viewportLabel = viewports[currentViewportIndex].dataset.label;
            viewportInfo.textContent = viewportLabel;
        }

        // Update button states
        prevBtn.disabled = currentViewportIndex === 0;
        nextBtn.disabled = currentViewportIndex === viewports.length - 1;
    }

    function navigateViewport(direction) {
        if (direction === 'prev' && currentViewportIndex > 0) {
            currentViewportIndex--;
        } else if (direction === 'next' && currentViewportIndex < viewports.length - 1) {
            currentViewportIndex++;
        }
        updateDisplay();
    }

    // Event listeners
    prevBtn.addEventListener('click', () => navigateViewport('prev'));
    nextBtn.addEventListener('click', () => navigateViewport('next'));

    // Initialize
    updateDisplay();
})();
</script>
<?php endif; ?>
