<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Reviews\Controller\Admin;

use Cms\Controller\Admin\AbstractController;

final class Browser extends AbstractController
{
	/**
	 * Shows a table
	 * 
	 * @param string $page Current page
	 * @return string
	 */
	public function indexAction($page = 1)
	{
		$reviewsManager = $this->getModuleService('reviewsManager');

		$paginator = $reviewsManager->getPaginator();
		$paginator->setUrl('/admin/module/reviews/page/%s');

		$this->loadPlugins();

		return $this->view->render('browser', array(

			'title' => 'Reviews',
			'dateFormat' => $reviewsManager->getTimeFormat(),
			'reviews'	=> $reviewsManager->fetchAllByPage($page, $this->getSharedPerPageCount()),
			'paginator'	=> $paginator,
		));
	}

	/**
	 * Loads required plugins for display
	 * 
	 * @return void
	 */
	private function loadPlugins()
	{
		$this->view->getPluginBag()
				   ->appendScript($this->getWithAssetPath('/admin/browser.js'));

		$this->view->getBreadcrumbBag()->add(array(
			array(
				'name' => 'Reviews',
				'link' => '#'
			)
		));
	}

	/**
	 * Removes a review by its associated id
	 * 
	 * @return string
	 */
	public function deleteAction()
	{
		if ($this->request->hasPost('id')) {

			$id = $this->request->getPost('id');

			$reviewsManager = $this->getModuleService('reviewsManager');
			$this->flashBag->set('success', 'A review has been removed successfully');

			return $reviewsManager->deleteById($id) ? '1' : '0';
		}
	}

	/**
	 * Batch removal of reviews by their associated ids
	 * 
	 * @return string
	 */
	public function deleteSelectedAction()
	{
		if ($this->request->hasPost('toDelete')) {

			$ids = array_keys($this->request->getPost('toDelete'));

			// Grab the service
			$reviewsManager = $this->getModuleService('reviewsManager');
			$reviewsManager->deleteByIds($ids);

			$this->flashBag->set('success', 'Selected reviews have been successfully removed');
		} else {
			$this->flashBag->set('warning', 'You should select at least one review to remove');
		}

		return '1';
	}

	/**
	 * Save changes on table form
	 * 
	 * @return string
	 */
	public function saveAction()
	{
		if ($this->request->hasPost('published')) {

			$published = $this->request->getPost('published');

			// Grab the service
			$reviewsManager = $this->getModuleService('reviewsManager');
			$reviewsManager->updatePublished($published);

			$this->flashBag->set('success', 'Settings have been successfully saved');

			return '1';
		}
	}
}
