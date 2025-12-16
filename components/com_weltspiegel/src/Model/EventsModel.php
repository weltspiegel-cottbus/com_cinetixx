<?php
/**
 * @package     Weltspiegel\Component\Weltspiegel\Site\Model
 *
 * @copyright   Weltspiegel Cottbus
 * @license     MIT; see LICENSE file
 */

namespace Weltspiegel\Component\Weltspiegel\Site\Model;

\defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\QueryInterface;
use Weltspiegel\Component\Weltspiegel\Administrator\Helper\CinetixxHelper;

/**
 * This models supports retrieving a list of events.
 *
 * @since 1.0.0
 */
class EventsModel extends ListModel
{
	/**
	 * Cinetixx Mandator ID
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	private string $mandatorId;

	/**
	 * Constructor
	 *
	 * @param   array                     $config
	 * @param   MVCFactoryInterface|null  $factory
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public function __construct($config = [], ?MVCFactoryInterface $factory = null)
	{
		parent::__construct($config, $factory);

		$params           = ComponentHelper::getParams('com_weltspiegel');
		$this->mandatorId = $params->get('mandator_id');
	}

	/**
	 * Method to get an array of events.
	 *
	 * @return array|false An array of events on success, false on failure.
	 *
	 * @throws Exception
	 * @since 1.0.0
	 */
	public function getItems(): array|false
	{
		$events = CinetixxHelper::getEvents($this->mandatorId);
		$items  = parent::getItems();

		if ($items) {
			foreach ($items as $item) {
				if(!empty($item->trailer_id)) {
					$events[$item->event_id]->trailerId = $item->trailer_id;
				}
			}
		}

		return $events;
	}

	/**
	 * Build an SQL query to load the events.
	 *
	 * @return QueryInterface
	 *
	 * @throws \Exception
	 *
	 * @since 1.0.0
	 */
	protected function getListQuery(): QueryInterface
	{
		$eventIds = CinetixxHelper::getEventIds($this->mandatorId);

		$db    = $this->getDatabase();
		$query = $db->createQuery();

		$query
			->select('id, event_id, trailer_id')
			->from('#__ws_cinetixx_events')
			->whereIn('event_id', $eventIds);

		return $query;
	}
}