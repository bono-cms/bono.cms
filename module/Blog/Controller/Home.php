<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Blog\Controller;

final class Home extends AbstractBlogController
{
	/**
	 * Shows all recent posts
	 * Usually used as a home page
	 * 
	 * @param integer $pageNumber Current page number
	 * @return string
	 */
	public function indexAction($pageNumber = 1)
	{
		$this->loadPlugins();

		$postManager = $this->getPostManager();
		$posts = $postManager->fetchAllByPage(true, $pageNumber, $this->getConfig()->getPerPageCount());

		// Tweak pagination
		$paginator = $postManager->getPaginator();

		// The pattern /(:var)/page/(:var) is reserved, so another one should be used instead
		$paginator->setUrl('/blog/pg/(:var)');

		$page = $this->getService('Pages', 'pageManager')->fetchDefault();

		return $this->view->render('blog-category', array(
			'paginator' => $paginator,
			'page' => $page,
			'posts' => $posts,
		));
	}

	/**
	 * Loads required plugins for view
	 * 
	 * @return void
	 */
	private function loadPlugins()
	{
		$this->loadSitePlugins();

		// No breadcrumbs on home page's display
		$this->view->getBreadcrumbBag()->clear();
	}
}
