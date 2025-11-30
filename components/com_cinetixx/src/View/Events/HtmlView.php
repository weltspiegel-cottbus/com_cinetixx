<?php
/**
 * @package     Weltspiegel\Component\Cinetixx\Site\View\Events
 *
 * @copyright   Weltspiegel Cottbus
 * @license     MIT; see LICENSE file
 */

namespace Weltspiegel\Component\Cinetixx\Site\View\Events;

\defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Weltspiegel\Component\Cinetixx\Site\Model\EventsModel;

/**
 * View class for the list of current events.
 *
 * @since 1.0.0
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * Page title, will be used in browser title and page.
	 * Overrides menu item settings
	 *
	 * @var string
	 * @since 1.0.0
	 */
	protected string $title;

	/**
	 * The list of current events
	 *
	 * @var array
	 *
	 * @since 1.0.0
	 */
	protected array $items;

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

		$this->title = 'Programmübersicht';
		$this->setDocumentTitle($this->title);

		parent::display($tpl);
	}
}