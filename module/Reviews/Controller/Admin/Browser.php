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
		$this->view->getPluginBag()
				   ->appendScript($this->getWithAssetPath('/admin/browser.js'));

		$this->view->getBreadcrumbBag()->add(array(
			array(
				'name' => 'Reviews',
				'link' => '#'
			)
		));

		// Grab a service
		$reviewsManager = $this->moduleManager->getModule('Reviews')->getService('reviewsManager');

		$paginator = $reviewsManager->getPaginator();
		$paginator->setUrl('/admin/module/reviews/page/%s');

		return $this->view->render('browser', array(

			'title' => 'Reviews',
			'dateFormat' => $reviewsManager->getTimeFormat(),
			'reviews'	=> $reviewsManager->fetchAllByPage($page, $this->getSharedPerPageCount()),
			'paginator'	=> $paginator,
		));
	}

	/**
	 * Delete selected records
	 * 
	 * @return string
	 */
	public function deleteAction()
	{
		$id = $this->request->getPost('id');
		
		// Grab a service
		$reviewsManager = $this->moduleManager->getModule('Reviews')->getService('reviewsManager');
		$this->flashMessenger->set('success', 'A review has been removed successfully');
		
		return $reviewsManager->deleteById($id) ? '1' : '0';
	}

	/**
	 * Delete selected records
	 * 
	 * @return string
	 */
	public function deleteSelectedAction()
	{
		if ($this->request->hasPost('toDelete')) {
			
			$ids = array_keys($this->request->getPost('toDelete'));
			
			// Grab a service
			$reviewsManager = $this->moduleManager->getModule('Reviews')->getService('reviewsManager');
			$reviewsManager->deleteByIds($ids);
			
			$flashKey = 'success';
			$flashMessage = 'Selected reviews have been successfully removed';
			
		} else {
			
			$flashKey = 'warning';
			$flashMessage = 'You should select at least one review to remove';
		}
		
		$this->flashMessenger->set($flashKey, $flashMessage);
		return '1';
	}

	/**
	 * Save changes on table form
	 * 
	 * @return string
	 */
	public function saveAction()
	{
		$published = $this->request->getPost('published');
		
		// Grab a service
		$reviewsManager = $this->moduleManager->getModule('Reviews')->getService('reviewsManager');
		$reviewsManager->updatePublished($published);
		
		$this->flashMessenger->set('success', 'Settings have been successfully saved');
		
		return '1';
	}
}
