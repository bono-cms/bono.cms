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

final class Post extends AbstractController
{
	/**
	 * Shows a post by its id
	 * 
	 * @param string $id Post id
	 * @return string
	 */
	public function indexAction($id)
	{
		$postManager = $this->getModuleService('postManager');
		$post = $postManager->fetchById($id);

		if ($post !== false) {

			$this->loadSitePlugins();
			$this->view->getBreadcrumbBag()
					   ->add($postManager->getBreadcrumbs($post));

			$config = $this->getModuleService('configManager')->getEntity();

			return $this->view->render($config->getPostTemplate(), array(
				'page' => $post,
				'post' => $post
			));

		} else {

			return false;
		}
	}
}
