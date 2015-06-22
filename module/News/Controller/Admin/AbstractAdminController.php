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

use Cms\Controller\Admin\AbstractController;

abstract class AbstractAdminController extends AbstractController
{
	/**
	 * Returns configuration manager
	 * 
	 * @return \News\Service\ConfigManager
	 */
	final protected function getConfigManager()
	{
		return $this->getModuleService('configManager');
	}

	/**
	 * Returns post manager
	 * 
	 * @return \News\Post\PostManager
	 */
	final protected function getPostManager()
	{
		return $this->getModuleService('postManager');
	}

	/** 
	 * Returns category manager
	 * 
	 * @return \News\Service\CategoryManager
	 */
	final protected function getCategoryManager()
	{
		return $this->getModuleService('categoryManager');
	}

	/**
	 * Returns task manager
	 * 
	 * @return \News\Service\TaskManager
	 */
	final protected function getTaskManager()
	{
		return $this->getModuleService('taskManager');
	}
}
