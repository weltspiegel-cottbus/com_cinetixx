<?php
/**
 * @package     Weltspiegel\Component\Cinetixx\Administrator\View\Events
 *
 * @copyright   Weltspiegel Cottbus
 * @license     MIT; see LICENSE file
 */

namespace Weltspiegel\Component\Cinetixx\Administrator\View\Events;

\defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\Registry\Registry;
use Weltspiegel\Component\Cinetixx\Administrator\Model\EventsModel;

/**
 * View class for the list of current events.
 *
 * @since  1.0.0
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * An array of events
	 *
	 * @var array
	 *
	 * @since 1.0.0
	 */
	protected array $items;

	/**
	 * The model state
	 *
	 * @var   Registry
	 *
	 * @since 1.0.0
	 */
	protected Registry $state;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public function display($tpl = null): void
	{
		/** @var EventsModel $model */
		$model = $this->getModel();
		$this->items = $model->getItems();
		$this->state = $model->getState();
		$this->filterForm    = $model->getFilterForm();
		$this->activeFilters = $model->getActiveFilters();

		if (!\count($this->items)) {
			$this->setLayout('empty');
		}

		if ($this->getLayout() !== 'modal') {
			$this->addToolbar();
		}

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.0.0
	 */
	protected function addToolbar(): void {
		ToolbarHelper::title('Cinetixx Events', 'fa fa-film');
	}
}