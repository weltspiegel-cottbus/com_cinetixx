<?php
/**
 * @package     Weltspiegel\Component\Cinetixx\Site\View\Event
 *
 * @copyright   Weltspiegel Cottbus
 * @license     MIT; see LICENSE file
 */

namespace Weltspiegel\Component\Cinetixx\Site\View\Event;

\defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use stdClass;
use Weltspiegel\Component\Cinetixx\Site\Model\EventModel;


/**
 * View class for a single event
 *
 * @since 1.0.0
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * The single event
	 *
	 * @var stdClass
	 * @since 1.0.0
	 */
	protected stdClass $item;

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
		/** @var EventModel $model */
		$model = $this->getModel();
		$this->item = $model->getItem();

		parent::display($tpl);
	}
}