<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Blog;

use Cms\AbstractCmsModule;
use Blog\Service\PostManager;
use Blog\Service\CategoryManager;
use Blog\Service\TaskManager;
use Blog\Service\SiteService;

final class Module extends AbstractCmsModule
{
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
			'configManager' => $this->getConfigService(),
			'taskManager' => new TaskManager($postMapper),
			'postManager' => $postManager,
			'categoryManager' => $categoryManager
		);
	}
}
