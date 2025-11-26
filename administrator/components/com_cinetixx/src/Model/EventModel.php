<?php

namespace Weltspiegel\Component\Cinetixx\Administrator\Model;

\defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\Form\Form;
use Joomla\CMS\MVC\Model\AdminModel;

class EventModel extends AdminModel
{

	/**
	 * Method to get the row form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  Form|boolean  A form object on success, false on failure
	 *
	 * @since   1.0.0
	 * @throws  Exception
	 */
	public function getForm($data = [], $loadData = true): false|Form
	{
		// Get the form.
		$form = $this->loadForm('com_cinetixx.event',
			'event', ['control' => 'jform', 'load_data' => $loadData]);

		if (empty($form))
		{
			return false;
		}

		return $form;
	}
}