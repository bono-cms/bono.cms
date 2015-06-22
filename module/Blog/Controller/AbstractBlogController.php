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

use Site\Controller\AbstractController;

abstract class AbstractBlogController extends AbstractController
{
	/**
	 * Returns configuration entity
	 * 
	 * @return \Krystal\Stdlib\VirtualEntity
	 */
	final protected function getConfig()
	{
		return $this->getModuleService('configManager')->getEntity();
	}

	/**
	 * Returns category manager
	 * 
	 * @return \Blog\Service\CategoryManager
	 */
	final protected function getCategoryManager()
	{
		return $this->getModuleService('categoryManager');
	}

	/**
	 * Returns post manager
	 * 
	 * @return \Blog\Service\PostManager
	 */
	final protected function getPostManager()
	{
		return $this->getModuleService('postManager');
	}
}
