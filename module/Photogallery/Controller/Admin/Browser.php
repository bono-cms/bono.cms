<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Photogallery\Controller\Admin;

use Cms\Controller\Admin\AbstractController;
use Krystal\Tree\AdjacencyList\TreeBuilder;
use Krystal\Tree\AdjacencyList\Render\PhpArray;

final class Browser extends AbstractController
{
	/**
	 * Shows a table
	 * 
	 * @param integer $page
	 * @return string
	 */
	public function indexAction($page = 1)
	{
		$this->loadSharePlugins();

		$paginator = $this->getPhotoManager()->getPaginator();
		$paginator->setUrl('/admin/module/photogallery/browse/%s');

		return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(
			'paginator' => $paginator,
			'photos' => $this->getPhotoManager()->fetchAllByPage($page, $this->getSharedPerPageCount()),
		)));
	}

	/**
	 * Filters photos by album id
	 * 
	 * @param string $albumId
	 * @param integer $page
	 * @return string
	 */
	public function albumAction($albumId, $page = 1)
	{
		$album = $this->getAlbumManager()->fetchById($albumId);

		if ($album !== false) {

			$this->loadSharePlugins();

			$paginator = $this->getPhotoManager()->getPaginator();
			$paginator->setUrl('/admin/module/photogallery/browse/album/'.$albumId.'/page/%s');

			return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(
				'albumId' => $albumId,
				'paginator' => $paginator,
				'photos' => $this->getPhotoManager()->fetchAllByAlbumIdAndPage($albumId, $page, $this->getSharedPerPageCount()),
			)));

		} else {
			return false;
		}
	}

	/**
	 * Save changes
	 * 
	 * @return string The response
	 */
	public function saveAction()
	{
		if ($this->request->hasPost('published', 'order')) {

			$published = $this->request->getPost('published');
			$orders = $this->request->getPost('order');

			// Grab a service
			$photoManager = $this->getPhotoManager();

			if ($photoManager->updatePublished($published) && $photoManager->updateOrders($orders)){

				$this->flashBag->set('success', 'Settings have been updated successfully');
				return '1';
			}
		}
	}

	/**
	 * Deletes an album with its content by its associated id
	 * 
	 * @return string
	 */
	public function deleteAlbumAction()
	{
		if ($this->request->hasPost('id')) {

			$id = $this->request->getPost('id');

			// Grab a service
			$albumManager = $this->moduleManager->getModule('Photogallery')->getService('albumManager');

			if ($albumManager->deleteById($id)) {

				$this->flashBag->set('success', 'Selected album has been removed successfully');
				return '1';
			}
		}
	}

	/**
	 * Deletes a photo by its associated id
	 * 
	 * @return string
	 */
	public function deleteAction()
	{
		if ($this->request->hasPost('id')) {
			$id = $this->request->getPost('id');

			if ($this->getPhotoManager()->deleteById($id)){

				$this->flashBag->set('success', 'Selected photo has been removed successfully');
				return '1';
			}
		}
	}

	/**
	 * Delete selected photos
	 * 
	 * @return string
	 */
	public function deleteSelectedAction()
	{
		if ($this->request->hasPost('toDelete')) {

			$ids = array_keys($this->request->getPost('toDelete'));
			$this->getPhotoManager()->deleteByIds($ids);

			$this->flashBag->set('success', 'Selected photos have been removed successfully');
		} else {
			$this->flashBag->set('warning', 'You should select at least one photo to remove');
		}

		return '1';
	}

	/**
	 * Loads shared plugins
	 * 
	 * @return void
	 */
	private function loadSharePlugins()
	{
		$this->view->getPluginBag()
				   ->appendScript($this->getWithAssetPath('/admin/browser.js'))
				   ->load('zoom');
	}

	/**
	 * Returns shared variables
	 * 
	 * @param array $overrides
	 * @return array
	 */
	private function getSharedVars(array $overrides)
	{
		$treeBuilder = new TreeBuilder($this->getAlbumManager()->fetchAll());
		$title = 'Photogallery';

		$this->view->getBreadcrumbBag()->add(array(
			array(
				'link' => '#',
				'name' => $title
			)
		));

		$vars = array(
			'taskManager' => $this->getModuleService('taskManager'),
			'albums' => $treeBuilder->render(new PhpArray('title')),
			'title' => $title
		);

		return array_replace_recursive($vars, $overrides);
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
	 * Returns Photo Manager
	 * 
	 * @return \Photogallery\Service\PhotoManager
	 */
	private function getPhotoManager()
	{
		return $this->getModuleService('photoManager');
	}

	/**
	 * Returns Album Manager
	 * 
	 * @return \Photogallery\Service\AlbumManager
	 */
	private function getAlbumManager()
	{
		return $this->getModuleService('albumManager');
	}	
}
