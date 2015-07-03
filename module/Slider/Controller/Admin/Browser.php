<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Slider\Controller\Admin;

use Cms\Controller\Admin\AbstractController;

final class Browser extends AbstractController
{
	/**
	 * Shows a table
	 * 
	 * @param integer $page Current page
	 * @return string
	 */
	public function indexAction($page = 1)
	{
		$this->loadSharedPlugins();

		$paginator = $this->getImageManager()->getPaginator();
		$paginator->setUrl('/admin/module/slider/page/%s');

		return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(
			'title' => 'Slider',
			'images' => $this->getImageManager()->fetchAllByPage($page, $this->getSharedPerPageCount()),
			'paginator' => $paginator
		)));
	}

	/**
	 * Fetches images associated with category id
	 * 
	 * @param string $categoryId
	 * @param integer $page Current page number
	 * @return string
	 */
	public function categoryAction($categoryId, $page = 1)
	{
		$this->loadSharedPlugins();

		$paginator = $this->getImageManager()->getPaginator();
		$paginator->setUrl('/admin/module/slider/category/view/'.$categoryId.'/page/%s');

		return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(
			'categoryId' => $categoryId,
			'images'	 => $this->getImageManager()->fetchAllByCategoryAndPage($categoryId, $page, $this->getSharedPerPageCount()),
			'paginator' => $paginator,
		)));
	}

	/**
	 * Deletes a category by its associated id
	 * 
	 * @return string The response
	 */
	public function deleteCategoryAction()
	{
		if ($this->request->hasPost('id')) {

			// Get category id from request
			$id = $this->request->getPost('id');

			// Remove all images associated with provided category id
			if ($this->getImageManager()->deleteAllByCategoryId($id) && $this->getCategoryManager()->deleteById($id)) {

				$this->flashMessenger->set('success', 'The category has been removed successfully');
				return '1';
			}
		}
	}

	/**
	 * Deletes selected slide image
	 * 
	 * @return string
	 */
	public function deleteAction()
	{
		if ($this->request->hasPost('id')) {
			$id = $this->request->getPost('id');

			if ($this->getImageManager()->deleteById($id)) {

				$this->flashMessenger->set('success', 'Selected slider has been removed successfully');
				return '1';
			}
		}
	}

	/**
	 * Removes selected records
	 * 
	 * @return string
	 */
	public function deleteSelectedAction()
	{
		if ($this->request->hasPost('toDelete')) {

			$ids = array_keys($this->request->getPost('toDelete'));

			if ($this->getImageManager()->deleteByIds($ids)) {
				$flashKey = 'success';
				$flashMessage = 'Selected slides have been removed successfully';
			}
			
		} else {

			$flashKey = 'warning';
			$flashMessage = 'You should select at least one image to remove';
		}

		$this->flashMessenger->set($flashKey, $flashMessage);
		return '1';
	}

	/**
	 * Saves settings
	 * 
	 * @return string
	 */
	public function saveAction()
	{
		if ($this->request->has('published', 'order')) {

			// Get input variables first
			$published = $this->request->getPost('published');
			$orders = $this->request->getPost('order');

			$imageManager = $this->getImageManager();

			if ($imageManager->updatePublished($published) && $imageManager->updateOrders($orders)) {

				$this->flashMessenger->set('success', 'Settings have been updated successfully');
				return '1';
			}
		}
	}

	/**
	 * Returns template path
	 * 
	 * @return string
	 */
	private function getTemplatePath()
	{
		return 'browser';
	}

	/**
	 * Loads shared plugins
	 * 
	 * @return void
	 */
	private function loadSharedPlugins()
	{
		$this->view->getPluginBag()
				   ->appendScript($this->getWithAssetPath('/admin/browser.js'));
	}

	/**
	 * Returns image manager
	 * 
	 * @return \Slider\Service\ImageManager
	 */
	private function getImageManager()
	{
		return $this->getModuleService('imageManager');
	}

	/**
	 * Returns category manager
	 * 
	 * @return \Slider\Service\CategoryManager
	 */
	private function getCategoryManager()
	{
		return $this->getModuleService('categoryManager');
	}
	
	/**
	 * Returns task manager
	 * 
	 * @return \Slider\Service\TaskManager
	 */
	private function getTaskManager()
	{
		return $this->getModuleService('taskManager');
	}

	/**
	 * Returns shared variables
	 * 
	 * @param array $overrides
	 * @return array
	 */
	private function getSharedVars(array $overrides)
	{
		$this->view->getBreadcrumbBag()->add(array(
			array(
				'name' => 'Slider',
				'link' => '#'
			)
		));

		$vars = array(
			'title' => 'Slider',
			'taskManager' => $this->getTaskManager(),
			'categories' => $this->getCategoryManager()->fetchAll(),
		);

		return array_replace_recursive($vars, $overrides);
	}	
}
