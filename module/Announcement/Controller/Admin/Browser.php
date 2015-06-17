<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Announcement\Controller\Admin;

final class Browser extends AbstractBrowser
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

		$paginator = $this->getAnnounceManager()->getPaginator();
		$paginator->setUrl('/admin/module/announcement/page/%s');

		return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(
			'announces' => $this->getAnnounceManager()->fetchAllByPage($page, $this->getSharedPerPageCount()),
			'paginator' => $paginator,
		)));
	}

	/**
	 * Filters by category id
	 * 
	 * @param string $categoryId Category id
	 * @param integer $page Current page
	 * @return string
	 */
	public function categoryAction($categoryId, $page = 1)
	{
		$this->loadSharedPlugins();

		$paginator = $this->getAnnounceManager()->getPaginator();
		$paginator->setUrl('/admin/module/announcement/category/view/'.$categoryId.'/page/%s');

		return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(
			'categoryId' => $categoryId,
			'announces' => $this->getAnnounceManager()->fetchAllByCategoryIdAndPage($categoryId, $page, $this->getSharedPerPageCount()),
			'paginator' => $paginator,
		)));
	}

	/**
	 * Save settings from the table
	 * 
	 * @return string The response
	 */
	public function saveAction()
	{
		$published = $this->request->getPost('published');
		$seo = $this->request->getPost('seo');

		// Grab a service
		$announceManager = $this->getAnnounceManager();

		$announceManager->updatePublished($published);
		$announceManager->updateSeo($seo);

		$this->flashMessenger->set('success', 'Announce settings have been updated successfully');

		return '1';
	}

	/**
	 * Delete selected announces
	 * 
	 * @return string The response
	 */
	public function deleteSelectedAction()
	{
		if ($this->request->hasPost('toDelete')) {
			$ids = array_keys($this->request->getPost('toDelete'));

			$this->getAnnounceManager()->deleteByIds($ids);

			$flashKey = 'success';
			$flashMessage = 'Selected announces have been removed successfully';

		} else {

			$flashKey = 'warning';
			$flashMessage = 'You should select at least one announce to remove';
		}

		$this->flashMessenger->set($flashKey, $flashMessage);
		return '1';
	}

	/**
	 * Deletes an announce by its id
	 * 
	 * @return string The response
	 */
	public function deleteAction()
	{
		$id = $this->request->getPost('id');
		
		// Grab a service
		$announceManager = $this->getAnnounceManager();
		$announceManager->deleteById($id);
		
		$this->flashMessenger->set('success', 'The announces have been removed successfully');
		
		return '1';
	}

	/**
	 * Deletes a category by its associated id
	 * 
	 * @return string The response
	 */
	public function deleteCategoryAction()
	{
		$id = $this->request->getPost('id');
		
		$categoryManager = $this->getCategoryManager();
		$categoryManager->deleteById($id);
		
		$this->flashMessenger->set('success', 'Selected category has been removed successfully');
		
		return '1';
	}
}
