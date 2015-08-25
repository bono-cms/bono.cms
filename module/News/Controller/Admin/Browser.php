<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace News\Controller\Admin;

final class Browser extends AbstractAdminController
{
	/**
	 * Shows all posts
	 * 
	 * @param integer $page Current page
	 * @return string
	 */
	public function indexAction($page = 1)
	{
		$this->loadSharedPlugins();

		$paginator = $this->getPostManager()->getPaginator();
		$paginator->setUrl('/admin/module/news/browse/(:var)');

		return $this->view->render($this->getTemplatePath(), $this->getWithSharedVars(array(
			'posts' => $this->getPostManager()->fetchAllByPage($page, $this->getSharedPerPageCount()),
			'paginator' => $paginator,
		)));
	}

	/**
	 * Shows all posts associated with provided category id
	 * 
	 * @param integer $id Category id
	 * @param integer $page Current page number
	 * @return string
	 */
	public function categoryAction($id, $page = 1)
	{
		$this->loadSharedPlugins();

		$paginator = $this->getPostManager()->getPaginator();
		$paginator->setUrl('/admin/module/news/browse/(:var)');

		return $this->view->render($this->getTemplatePath(), $this->getWithSharedVars(array(

			'posts' => $this->getPostManager()->fetchAllByCategoryIdAndPage($id, false, $page, $this->getSharedPerPageCount()),
			'categoryId' => $id,
			'paginator' => $paginator,
		)));
	}

	/**
	 * Saves options that come from a table
	 * 
	 * @return string
	 */
	public function saveAction()
	{
		if ($this->request->hasPost('published', 'seo')) {

			$published = $this->request->getPost('published');
			$seo = $this->request->getPost('seo');

			$postManager = $this->getPostManager();

			if ($postManager->updatePublished($published) && $postManager->updateSeo($seo)) {

				$this->flashBag->set('success', 'Settings have been saved successfully');
				return '1';
			}
		}
	}

	/**
	 * Removes a category
	 * 
	 * @return string
	 */
	public function deleteCategoryAction()
	{
		if ($this->request->hasPost('id')) {

			$id = $this->request->getPost('id');

			if ($this->getCategoryManager()->deleteById($id)) {
				$this->flashBag->set('success', 'Selected category has been removed successfully');
				return '1';
			}
		}
	}

	/**
	 * Deletes selected posts
	 * 
	 * @return string
	 */
	public function deleteSelectedAction()
	{
		if ($this->request->hasPost('toDelete')) {

			$ids = array_keys($this->request->getPost('toDelete'));

			$postManager = $this->getPostManager();
			$postManager->deleteByIds($ids);

			$this->flashBag->set('success', 'Selected posts have been removed successfully');
		} else {
			$this->flashBag->set('warning', 'You should select at least one post to remove');
		}

		return '1';
	}

	/**
	 * Deletes a post
	 * 
	 * @return string
	 */
	public function deleteAction()
	{
		if ($this->request->hasPost('id')) {
			$id = $this->request->getPost('id');

			if ($this->getPostManager()->deleteById($id)) {
				$this->flashBag->set('success', 'Selected post has been removed successfully');
				return '1';
			}
		}
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
	 * Return shared variables
	 * 
	 * @param array $overrides
	 * @return array
	 */
	private function getWithSharedVars(array $overrides)
	{
		$this->view->getBreadcrumbBag()->add(array(
			array(
				'link' => '#',
				'name' => 'News'
			)
		));

		$vars = array(
			'title' => 'News',
			'categories' => $this->getCategoryManager()->fetchAll(),
			'taskManager' => $this->getTaskManager(),
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
}
