<?php
/**
 * @package     Weltspiegel\Component\Cinetixx\Administrator\Helper
 *
 * @copyright   Weltspiegel Cottbus
 * @license     MIT; see LICENSE file
 */

namespace Weltspiegel\Component\Cinetixx\Administrator\Helper;

\defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\Cache\CacheControllerFactoryInterface;
use Joomla\CMS\Cache\Controller\CallbackController;
use Joomla\CMS\Factory;
use Joomla\Http\Http;
use stdClass;

/**
 * Cinetixx API helper methods
 *
 * @since 1.0.0
 */
abstract class CinetixxHelper
{

	/**
	 * Cinetixx Web Service Url
	 * See: http://services.cinetixx.eu/Services/CinetixxService.asmx
	 *
	 * @since 1.0.0
	 */
	private const string svcUrl = 'https://api.cinetixx.de/Services/CinetixxService.asmx/GetShowInfoV6';

	/**
	 * Internal cached cache controller
	 *
	 * @var CallbackController
	 *
	 * @since 1.0.0
	 */
	private static CallbackController $cache;

	/**
	 * Internal helper to return the cached cache controller or create it initially
	 *
	 * @return CallbackController
	 *
	 * @since 1.0.0
	 */
	private static function getCache(): CallbackController
	{
		return static::$cache ??= Factory::getContainer()
			->get(CacheControllerFactoryInterface::class)
			->createCacheController('callback', ['defaultgroup' => 'com_cinetixx']);
	}

	/**
	 * Method to retrieve the parsed events from the Cinetixx web service
	 *
	 * @param $mandatorId
	 *
	 * @return array
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public static function getCinetixxEvents($mandatorId): array
	{
		$url = static::svcUrl . "?mandatorId=$mandatorId";
		$http = new Http();
		$response = $http->get($url);

		$xml = simplexml_load_string($response->getBody());

		$eventIds = []; // Collecting unique event ids
		$events = [];   // Mapped events

		foreach ($xml->Show as $show) {

			$eventId = (string) $show->EVENT_ID;
			$event = null;

			if (!in_array($eventId, $eventIds))
			{
				$eventIds[] = $eventId;
				$event = new stdClass();

				$event->eventId = $eventId;
				$event->title = (string) $show->VERANSTALTUNGSTITEL;

				$event->trailerUrl = trim($show->MOVIE_LINK) ?: false;
				$event->trailerId = YouTubeHelper::parseYoutubeId($event->trailerUrl);

				$events[$eventId] = $event;
			}
		}

		$app = Factory::getApplication();
		if ($app->isClient("administrator")) {
			$app->enqueueMessage( "Aktuelle Cinetixx-Daten wurden geladen.");
		}

		return $events;
	}

	/**
	 * Returns (cached) Cinetixx events
	 *
	 * @param   string  $mandatorId
	 *
	 * @return array
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public static function getEvents(string $mandatorId): array
	{
		return static::getCache()->get([CinetixxHelper::class, "getCinetixxEvents"], [$mandatorId], 'cinetixx.events');
	}

	/**
	 * Returns array of current Cinetixx events
	 *
	 * @param   string  $mandatorId
	 *
	 * @return array
	 *
	 * @throws Exception
	 * @since version
	 */
	public static function getEventIds(string $mandatorId): array
	{
		$events = static::getEvents($mandatorId);
		return array_keys($events);
	}
}