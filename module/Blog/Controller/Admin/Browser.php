<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Blog\Controller\Admin;

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

		$paginator = $this->getPostManager()->getPaginator();
		$paginator->setUrl('/admin/module/blog/page/%s');

		return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(

			'posts' => $this->getPostManager()->fetchAllByPage($page, $this->getSharedPerPageCount()),
			'paginator' => $paginator,
		)));
	}

	/**
	 * List all posts by associated category id
	 * 
	 * @param string $id Category id
	 * @param integer $page
	 * @return string
	 */
	public function categoryAction($categoryId, $page = 1)
	{
		$paginator = $this->getPostManager()->getPaginator();
		$paginator->setUrl('/admin/module/blog/category/view/'.$categoryId.'/page/%s');

		return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(
			'categoryId' => $categoryId,
			'posts' => $this->getPostManager()->fetchAllByCategoryIdAndPage($categoryId, $page, $this->getSharedPerPageCount()),
			'paginator' => $paginator,
		)));
	}

	/**
	 * Removes selected post by its associated id
	 * 
	 * @return string
	 */
	public function deleteAction()
	{
		if ($this->request->hasPost('id')) {

			$id = $this->request->getPost('id');

			$this->getPostManager()->removeById($id);
			$this->flashMessenger->set('success', 'Selected post has been removed successfully');

			return '1';
		}
	}

	/**
	 * Removes a category by its associated id
	 * 
	 * @return string
	 */
	public function deleteCategoryAction()
	{
		if ($this->request->hasPost('id')) {

			$id = $this->request->getPost('id');

			$this->getCategoryManager()->removeById($id);
			$this->flashMessenger->set('success', 'Selected category has been removed successfully');

			return '1';
		}
	}

	/**
	 * Removes selected posts
	 * 
	 * @return string
	 */
	public function deleteSelectedAction()
	{
		if ($this->request->hasPost('toDelete')) {

			$ids = array_keys($this->request->getPost('toDelete'));

			// Grab a service now
			$this->getPostManager()->removeByIds($ids);

			$flashKey = 'success';
			$flashMessage = 'Selected posts have been removed successfully';

		} else {

			$flashKey = 'warning';
			$flashMessage = 'You should select at least one blog post to remove';
		}
		
		$this->flashMessenger->set($flashKey, $flashMessage);
		return '1';
	}

	/**
	 * Saves changes from a table
	 * 
	 * @return string The response
	 */
	public function saveAction()
	{
		if ($this->request->hasPost('published', 'seo', 'comments')) {

			// Collect data from the request
			$published = $this->request->getPost('published');
			$seo = $this->request->getPost('seo');
			$comments = $this->request->getPost('comments');

			// Grab a service now
			$postManager = $this->getPostManager();

			// Do the bulk actions
			$postManager->updateSeo($seo);
			$postManager->updatePublished($published);
			$postManager->updateComments($comments);

			$this->flashMessenger->set('success', 'Post settings have been updated');

			return '1';
		}
	}
}
