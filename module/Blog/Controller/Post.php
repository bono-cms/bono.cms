<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Blog\Controller;

use Krystal\Stdlib\VirtualEntity;

final class Post extends AbstractBlogController
{
	/**
	 * Shows a post by its associated id
	 * 
	 * @param string $id Post's id
	 * @return string
	 */
	public function indexAction($id)
	{
		$post = $this->getPostManager()->fetchById($id);

		// If $post isn't false, then $id is valid and $post itself is an instance of entity class
		if ($post !== false) {
			$this->loadPlugins($post);

			return $this->view->render($this->getConfig()->getPostTemplate(), array(
				'page' => $post,
				'post' => $post,
			));

		} else {

			// Returning false triggers 404 error automatically
			return false;
		}
	}

	/**
	 * Loads required plugins for view
	 * 
	 * @param \Krystal\Stdlib\VirtualEntity $post
	 * @return void
	 */
	private function loadPlugins(VirtualEntity $post)
	{
		$this->loadSitePlugins();
		$this->view->getBreadcrumbBag()->add($this->getPostManager()->getBreadcrumbs($post));
	}
}
