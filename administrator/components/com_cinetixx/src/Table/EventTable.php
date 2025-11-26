<?php
/**
 * @package     Weltspiegel\Component\Cinetixx\Administrator\Table
 *
 * @copyright   Weltspiegel Cottbus
 * @license     MIT; see LICENSE file
 */

namespace Weltspiegel\Component\Cinetixx\Administrator\Table;

defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;
use Weltspiegel\Component\Cinetixx\Administrator\Helper\YouTubeHelper;

/**
 * Events table class.
 *
 * @since  1.0.0
 */
class EventTable extends Table
{
	/**
	 * Constructor
	 *
	 * @param   DatabaseDriver  $db  Database connector object
	 *
	 * @since   1.0.0
	 */
	public function __construct(DatabaseDriver $db)
	{
		parent::__construct('#__ws_cinetixx_events', 'id', $db);
	}

	/**
	 * Validates event data before saving
	 *
	 * @return bool
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public function check(): bool
	{
		parent::check();

		$this->trailer_url = trim($this->trailer_url);
		if ($this->trailer_url !== "") {
			if (!preg_match("/^[\w-]{11}$/", $this->trailer_url)) {
				$youTubeId = YouTubeHelper::parseYoutubeId($this->trailer_url);
				if (!$youTubeId) {
					throw new Exception('Ungültige YouTube ID oder YouTube URL.');
				}
				$this->trailer_url = $youTubeId;
			}
		}

		return true;
	}
}