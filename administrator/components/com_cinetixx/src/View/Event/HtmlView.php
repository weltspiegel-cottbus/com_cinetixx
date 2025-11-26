<?php

namespace Weltspiegel\Component\Cinetixx\Administrator\View\Event;

\defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Weltspiegel\Component\Cinetixx\Administrator\Model\EventModel;

/**
 * View to edit a event.
 *
 * @since  1.0.0
 */
class HtmlView extends BaseHtmlView
{
	protected $form;
	protected $item;

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
		$model      = $this->getModel();
		$this->form = $model->getForm();
		$this->item = $model->getItem();

		$this->addToolbar();

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @throws Exception
	 *
	 * @since   1.0.0
	 */
	protected function addToolbar(): void {
		Factory::getApplication()->getInput()->set('hidemainmenu', true);

		ToolbarHelper::title('Cinetixx Events: Bearbeiten', 'fa fa-film');

		ToolbarHelper::apply('event.apply');
		ToolbarHelper::save('event.save');
		ToolbarHelper::cancel('event.cancel', 'JTOOLBAR_CLOSE');
	}
}