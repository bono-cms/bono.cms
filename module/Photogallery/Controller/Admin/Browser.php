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

final class Browser extends AbstractBrowser
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
		$published = $this->request->getPost('published');
		$orders = $this->request->getPost('order');
		
		// Grab a service
		$photoManager = $this->getPhotoManager();
		$photoManager->updatePublished($published);
		$photoManager->updateOrders($orders);
		
		$this->flashMessenger->set('success', 'Settings have been updated successfully');
		
		return '1';
	}

	/**
	 * Deletes an album with its content by its associated id
	 * 
	 * @return string
	 */
	public function deleteAlbumAction()
	{
		$id = $this->request->getPost('id');
		
		// Grab a service
		$albumManager = $this->moduleManager->getModule('Photogallery')->getService('albumManager');
		$albumManager->deleteById($id);
		
		$this->flashMessenger->set('success', 'Selected album has been removed successfully');
		
		return '1';
	}

	/**
	 * Deletes a photo
	 * 
	 * @return string
	 */
	public function deleteAction()
	{
		$id = $this->request->getPost('id');
		
		$this->getPhotoManager()->deleteById($id);
		$this->flashMessenger->set('success', 'Selected photo has been removed successfully');
		
		return '1';
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
			
			$flashKey = 'success';
			$flashMessage = 'Selected photos have been removed successfully';
		
		} else {
			
			$flashKey = 'warning';
			$flashMessage = 'You should select at least one photo to remove';
		}
		
		$this->flashMessenger->set($flashKey, $flashMessage);
		
		return '1';
	}
}
