<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Advice\Controller\Admin;

final class Browser extends AbstractAdminController
{
	/**
	 * Shows a grid
	 * 
	 * @param integer $page Current page
	 * @return string
	 */
	public function indexAction($page = 1)
	{
		$this->loadPlugins();

		// Grab a service
		$adviceManager = $this->getAdviceManager();

		// Configure pagination
		$paginator = $adviceManager->getPaginator();
		$paginator->setUrl('/admin/module/advice/page/%s');

		return $this->view->render('browser', array(

			'title' => 'Advice',
			'advices' => $adviceManager->fetchAllByPage($page, $this->getSharedPerPageCount()),
			'paginator' => $paginator,
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
				   ->appendScript($this->getWithAssetPath('/admin/browser.js'));
		
		$this->view->getBreadcrumbBag()->add(array(
			array(
				'link' => '#',
				'name' => 'Advice'
			)
		));
	}
	
	/**
	 * Save changes
	 * 
	 * @return string
	 */
	public function saveAction()
	{
		if ($this->request->hasPost('published')) {

			$published = $this->request->getPost('published');

			$this->getAdviceManager()->updatePublished($published);
			$this->flashBag->set('success', 'Settings have been updated successfully');

			return '1';
		}
	}

	/**
	 * Removes selected advice by its id
	 * 
	 * @return string
	 */
	public function deleteAction()
	{
		if ($this->request->hasPost('id')) {

			$id = $this->request->getPost('id');

			if ($this->getAdviceManager()->deleteById($id)) {

				$this->flashBag->set('success', 'Selected advice has been removed successfully');
				return '1';
			}
		}
	}

	/**
	 * Delete selected records
	 * 
	 * @return string The response
	 */
	public function deleteSelectedAction()
	{
		if ($this->request->hasPost('toDelete')) {

			$ids = array_keys($this->request->getPost('toDelete'));
			$this->getAdviceManager()->deleteByIds($ids);

			$flashKey = 'success';
			$flashMessage = 'Selected advices have been removed successfully';

		} else {

			$flashKey = 'warning';
			$flashMessage = 'You should select at least one advice to remove';
		}

		$this->flashBag->set($flashKey, $flashMessage);
		return '1';
	}
}
