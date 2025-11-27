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

		parent::display($tpl);
	}
}