<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Controller\Admin\Languages;

use Cms\Controller\Admin\AbstractController;

final class Browser extends AbstractController
{
	/**
	 * {@inheritDoc}
	 */
	protected function bootstrap()
	{
		$this->disableLanguageCheck();
		parent::bootstrap();
	}

	/**
	 * Shows language's grid
	 * 
	 * @return string
	 */
	public function indexAction()
	{
		$this->loadPlugins();

		return $this->view->render('languages/browser', array(

			// We can't define an array which is called "languages", because that name is already in template's global scope
			'langs' => $this->getLanguageManager()->fetchAll(),
			'title' => 'Languages'
		));
	}

	/**
	 * Loads required plugins for view
	 * 
	 * @return void
	 */
	private function loadPlugins()
	{
		$this->view->getPluginBag()
				   ->appendScript($this->getWithAssetPath('/admin/language/browser.js'));
		
		$this->view->getBreadcrumbBag()->add(array(
			array(
				'name' => 'Languages',
				'link' => '#'
			)
		));
	}

	/**
	 * Returns language manager
	 * 
	 * @return \Admin\Service\LanguageManager
	 */
	private function getLanguageManager()
	{
		return $this->getService('Cms', 'languageManager');
	}

	/**
	 * Changes a language
	 * 
	 * @return string
	 */
	public function changeAction()
	{
		if ($this->request->hasPost('id') && $this->request->isAjax()) {

			$id = $this->request->getPost('id');

			$this->getLanguageManager()->setCurrentId($id);
			return '1';
		}
	}

	/**
	 * Saves the data
	 * 
	 * @return string The response
	 */
	public function saveAction()
	{
		if ($this->request->hasPost('default', 'published', 'order')) {

			// Grab request data
			$default	= $this->request->getPost('default');
			$published	= $this->request->getPost('published');
			$orders		= $this->request->getPost('order');

			// Grab a service
			$languageManager = $this->getLanguageManager();

			// Mark language id as a default
			$languageManager->makeDefault($default);
			$languageManager->updatePublished($published);
			$languageManager->updateOrders($orders);

			$this->flashBag->set('success', 'Settings have been updated successfully');

			// Assuming success
			return '1';
		}
	}

	/**
	 * Deletes a language by its associated id
	 * 
	 * @return string The response
	 */
	public function deleteAction()
	{
		if ($this->request->hasPost('id')) {

			$id = $this->request->getPost('id');

			if ($this->getLanguageManager()->deleteById($id)) {

				$this->flashBag->set('success', 'Selected language has been removed successfully');
				return '1';
			}
		}
	}
}
