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
		return $this->getBlogModule()->getService('configManager')->getEntity();
	}

	/**
	 * Returns category manager
	 * 
	 * @return \Blog\Service\CategoryManager
	 */
	final protected function getCategoryManager()
	{
		return $this->getBlogModule()->getService('categoryManager');
	}

	/**
	 * Returns post manager
	 * 
	 * @return \Blog\Service\PostManager
	 */
	final protected function getPostManager()
	{
		return $this->getBlogModule()->getService('postManager');
	}

	/**
	 * Returns blog module
	 * 
	 * @return \Blog\Module
	 */
	final protected function getBlogModule()
	{
		return $this->moduleManager->getModule('Blog');
	}
}
