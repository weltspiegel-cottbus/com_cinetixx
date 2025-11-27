<?php
\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Weltspiegel\Component\Cinetixx\Administrator\View\Events\HtmlView;

/** @var HtmlView $this */

$listOrder     = $this->escape($this->state->get('list.ordering'));
$listDirection = $this->escape($this->state->get('list.direction'));

?>
<form action="<?= Route::_('index.php?option=com_cinetixx&view=events') ?>" method="post" name="adminForm" id="adminForm">
    <div id="j-main-container" class="j-main-container">
        <?= LayoutHelper::render('joomla.searchtools.default', ['view' => $this]) ?>

        <table class="table">
            <thead>
            <tr>
                <th>
                    <?php echo HTMLHelper::_('searchtools.sort', 'Event', 'title', $listDirection, $listOrder); ?>
                </th>
                <th>
                    Trailer (YouTube ID)
                </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($this->items as $i => $item) : ?>
                <tr>
                    <td><?= $item->cinetixxTitle ?></td>
                    <th scope="row">
                        <a href="<?= Route::_("index.php?option=com_cinetixx&task=event.edit&id=" . $item->id . "&event_id=" . $item->event_id) ?>"
                           title="<?= \Joomla\CMS\Language\Text::_('JACTION_EDIT') ?>">
                            <?php if ($item->trailer_id) : ?>
                                <?= $this->escape($item->trailer_id); ?>
                            <?php elseif ($item->cinetixxTrailerId) : ?>
                                <?= $this->escape($item->cinetixxTrailerId) . ' (Cinetixx)'; ?>
                            <?php else : ?>
                                Noch kein Trailer gesetzt
                            <?php endif; ?>
                        </a>
                    </th>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <input type="hidden" name="task" value="">
        <input type="hidden" name="boxchecked" value="0">
        <?= HTMLHelper::_('form.token') ?>
    </div>
</form>