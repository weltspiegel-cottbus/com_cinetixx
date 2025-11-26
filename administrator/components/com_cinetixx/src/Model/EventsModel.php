<?php
/**
 * @package     Weltspiegel\Component\Cinetixx\Administrator\Model
 *
 * @copyright   Weltspiegel Cottbus
 * @license     MIT; see LICENSE file
 */

namespace Weltspiegel\Component\Cinetixx\Administrator\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\QueryInterface;

/**
 * Model class supporting a list of events
 * @since version
 */
class EventsModel extends ListModel
{
	/**
	 * Method to get an array of events.
	 *
	 * @return array|false An array of events on success, false on failure.
	 *
	 * @since 1.0.0
	 */
	public function getItems(): array|false
	{
		$items = parent::getItems();
		// var_dump($items);
		return $items;
	}

	/**
	 * Build an SQL query to load the events.
	 *
	 * @return QueryInterface
	 *
	 * @since   1.0
	 */
	protected function getListQuery(): QueryInterface
	{
		$db    = $this->getDatabase();
		$query = $db->createQuery();

		$query
			->select('id, event_id, trailer_url')
			->from('#__ws_cinetixx_events');

		return $query;

	}
}