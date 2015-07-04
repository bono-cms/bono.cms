<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Blog;

use Krystal\Config\File\FileArray;
use Cms\AbstractCmsModule;
use Blog\Service\PostManager;
use Blog\Service\CategoryManager;
use Blog\Service\TaskManager;
use Blog\Service\ConfigManager;
use Blog\Service\SiteService;

final class Module extends AbstractCmsModule
{
	/**
	 * Returns configuration manager
	 * 
	 * @return \Blog\Service\ConfigManager
	 */
	private function getConfigManager()
	{
		$adapter = new FileArray(__DIR__ . '/Config/module.config.php');
		$adapter->load();

		return new ConfigManager($adapter);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getServiceProviders()
	{
		$postMapper = $this->getMapper('/Blog/Storage/MySQL/PostMapper');
		$categoryMapper = $this->getMapper('/Blog/Storage/MySQL/CategoryMapper');

		$webPageManager = $this->getWebPageManager();
		$historyManager = $this->getHistoryManager();

		$postManager = new PostManager($postMapper, $categoryMapper, $webPageManager, $historyManager);
		$categoryManager = new CategoryManager($categoryMapper, $postMapper, $webPageManager, $historyManager, $this->getMenuWidget());

		$siteService = new SiteService($categoryMapper, $postMapper, $webPageManager);

		return array(
			'siteService' => $siteService,
			'configManager' => $this->getConfigManager(),
			'taskManager' => new TaskManager($postMapper),
			'postManager' => $postManager,
			'categoryManager' => $categoryManager
		);
	}
}
