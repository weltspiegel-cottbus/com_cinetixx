<?php

namespace Weltspiegel\Component\Cinetixx\Administrator\Table;

defined('_JEXEC') or die;

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;

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
}