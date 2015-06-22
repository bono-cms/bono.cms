<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace News\Controller;

use Site\Controller\AbstractController;

final class Category extends AbstractController
{
	/**
	 * Shows a category by its id (Aware of pagination as well)
	 * 
	 * @param string $id Category id
	 * @param integer $pageNumber current page number
	 * @param string $code Language code
	 * @param string $slug Category page's slug
	 * @return string
	 */
	public function indexAction($id, $pageNumber = 1, $code = null, $slug = null)
	{
		$categoryManager = $this->getService('News', 'categoryManager');
		$page = $categoryManager->fetchById($id);

		if ($page !== false) {

			$this->loadSitePlugins();
			$this->view->getBreadcrumbBag()
					   ->add($categoryManager->getBreadcrumbs($page));

			$postManager = $this->getService('News', 'postManager');
			$config = $this->getService('News', 'configManager')->getEntity();

			// Now get all posts associated with provided category id
			$posts = $postManager->fetchAllPublishedByCategoryIdAndPage($id, $pageNumber, $config->getPerPageCount());

			// Prepare pagination
			$paginator = $postManager->getPaginator();
			$this->preparePaginator($paginator, $code, $slug, $pageNumber);

			return $this->view->render($config->getCategoryTemplate(), array(
				'paginator' => $paginator,
				'posts' => $posts,
				'page' => $page
			));

		} else {

			return false;
		}
	}
}
