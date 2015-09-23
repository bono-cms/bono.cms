<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Announcement\Controller\Admin;

final class Browser extends AbstractAdminController
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
		$paginator->setUrl('/admin/module/announcement/page/(:var)');

		return $this->view->render($this->getTemplatePath(), $this->getWithSharedVars(array(
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
		$paginator->setUrl('/admin/module/announcement/category/view/'.$categoryId.'/page/(:var)');

		return $this->view->render($this->getTemplatePath(), $this->getWithSharedVars(array(
			'categoryId' => $categoryId,
			'announces' => $this->getAnnounceManager()->fetchAllByCategoryIdAndPage($categoryId, $page, $this->getSharedPerPageCount()),
			'paginator' => $paginator,
		)));
	}

	/**
	 * Save settings from the grid
	 * 
	 * @return string
	 */
	public function saveAction()
	{
		if ($this->request->hasPost('seo', 'published', 'order')) {

			$published = $this->request->getPost('published');
			$seo = $this->request->getPost('seo');
			$orders = $this->request->getPost('order');

			// Grab a service
			$announceManager = $this->getAnnounceManager();

			$announceManager->updatePublished($published);
			$announceManager->updateSeo($seo);
			$announceManager->updateOrders($orders);

			$this->flashBag->set('success', 'Announce settings have been updated successfully');

			return '1';
		}
	}

	/**
	 * Delete selected announces
	 * 
	 * @return string
	 */
	public function deleteSelectedAction()
	{
		if ($this->request->hasPost('toDelete')) {
			$ids = array_keys($this->request->getPost('toDelete'));

			$this->getAnnounceManager()->deleteByIds($ids);
			$this->flashBag->set('success', 'Selected announces have been removed successfully');

		} else {
			$this->flashBag->set('warning', 'You should select at least one announce to remove');
		}

		return '1';
	}

	/**
	 * Deletes an announce by its id
	 * 
	 * @return string
	 */
	public function deleteAction()
	{
		if ($this->request->hasPost('id')) {

			$id = $this->request->getPost('id');

			// Grab a service
			$announceManager = $this->getAnnounceManager();
			$announceManager->deleteById($id);

			$this->flashBag->set('success', 'The announces have been removed successfully');

			return '1';
		}
	}

	/**
	 * Deletes a category by its associated id
	 * 
	 * @return string
	 */
	public function deleteCategoryAction()
	{
		if ($this->request->hasPost('id')) {

			$id = $this->request->getPost('id');

			$categoryManager = $this->getCategoryManager();
			$categoryManager->deleteById($id);

			$this->flashBag->set('success', 'Selected category has been removed successfully');

			return '1';
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
	 * Returns shared variables
	 * 
	 * @param array $overrides
	 * @return array
	 */
	private function getWithSharedVars(array $overrides)
	{
		$this->view->getBreadcrumbBag()->add(array(
			array(
				'link' => '#',
				'name' => 'Announcement'
			)
		));

		$vars = array(
			'categories' => $this->getCategoryManager()->fetchAll(),
			'title' => 'Announcements',
		);

		return array_replace_recursive($vars, $overrides);
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
}
