<?php

namespace Weltspiegel\Component\Cinetixx\Administrator\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\FormController;

/**
 * Controller for a single event
 *
 * @since  1.0.0
 */
class EventController extends FormController
{
	/**
	 *  Method to prepared editing an existing record.
	 *  Injects the eventId in the user session
	 *
	 * @param $key
	 * @param $urlVar
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function edit($key = null, $urlVar = null): bool
	{
		$eventId = $this->input->get('event_id');
		$this->app->setUserState('com_cinetixx.event_id', $eventId);

		return parent::edit($key, $urlVar);
	}
}