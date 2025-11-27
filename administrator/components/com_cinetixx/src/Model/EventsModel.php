<?php
/**
 * @package     Weltspiegel\Component\Cinetixx\Administrator\Model
 *
 * @copyright   Weltspiegel Cottbus
 * @license     MIT; see LICENSE file
 */

namespace Weltspiegel\Component\Cinetixx\Administrator\Model;

\defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\QueryInterface;
use Weltspiegel\Component\Cinetixx\Administrator\Helper\CinetixxHelper;

/**
 * Model class supporting a list of events
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

		$params           = ComponentHelper::getParams('com_cinetixx');
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

		return array_map(function ($event) use (&$items) {
			$mergedItem = [
				// Cinetixx event props
				"cinetixxTitle"     => $event->title,
				"cinetixxTrailerId" => $event->trailerId,
				// Database props
				"id"                => 0,
				"event_id"          => $event->eventId,
				"trailer_id"        => null,
			];

			if ($items)
			{
				$itemIx = array_search($event->eventId, array_column($items, 'event_id'));

				if ($itemIx !== false)
				{
					$mergedItem["id"]         = $items[$itemIx]->id;
					$mergedItem["trailer_id"] = $items[$itemIx]->trailer_id;
				}
			}

			return (object) $mergedItem;

		}, $events);
	}

	/**
	 * Build an SQL query to load the events.
	 *
	 * @return QueryInterface
	 *
	 * @throws Exception
	 *
	 * @since   1.0
	 */
	protected function getListQuery(): QueryInterface
	{
		$eventIds = CinetixxHelper::getEventIds($this->mandatorId);

		$db    = $this->getDatabase();
		$query = $db->createQuery();

		$query
			->select('id, event_id, trailer_id')
			->from('#__ws_cinetixx_events')
			->where('event_id IN (' . implode(',', $eventIds) . ')');


		return $query;

	}
}