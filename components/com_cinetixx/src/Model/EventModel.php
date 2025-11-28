<?php
/**
 * @package     Weltspiegel\Component\Cinetixx\Site\Model
 *
 * @copyright   Weltspiegel Cottbus
 * @license     MIT; see LICENSE file
 */

namespace Weltspiegel\Component\Cinetixx\Site\Model;

\defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ItemModel;
use stdClass;
use Weltspiegel\Component\Cinetixx\Administrator\Helper\CinetixxHelper;


/**
 *
 * @since 1.0.0
 */
class EventModel extends ItemModel
{

	/**
	 * Method to get an event
	 *
	 * @param $pk int|null
	 *
	 * @return stdClass
	 *
	 * @throws Exception
	 * @since 1.0.0
	 */
	public function getItem($pk = null): stdClass
	{
		$eventId = Factory::getApplication()->input->getInt('event_id');

		$params           = ComponentHelper::getParams('com_cinetixx');
		$mandatorId = $params->get('mandator_id');

		$event = CinetixxHelper::getEvent($mandatorId, $eventId);

		$db = $this->getDatabase();
		$query = $db->getQuery(true);

		$query->select('*')
			->from($db->quoteName('#__ws_cinetixx_events', 'a'))
			->where(
					$db->quoteName('a.event_id') . ' = ' . $db->quote($eventId)
			);

		$db->setQuery($query);

		$item = $db->loadObject();
		if(!empty($item) && !empty($item->trailer_id)) {
			$event->trailerId = $item->trailer_id;
		}

		return $event;
	}
}