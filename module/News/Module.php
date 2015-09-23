<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace News;

use Cms\AbstractCmsModule;
use Krystal\Image\Tool\ImageManager;
use News\Service\CategoryManager;
use News\Service\PostManager;
use News\Service\TaskManager;
use News\Service\PostImageManagerFactory;
use News\Service\TimeBagFactory;
use News\Service\SiteService;

final class Module extends AbstractCmsModule
{
	/**
	 * {@inheritDoc}
	 */
	public function getServiceProviders()
	{
		$categoryMapper = $this->getMapper('/News/Storage/MySQL/CategoryMapper');
		$postMapper = $this->getMapper('/News/Storage/MySQL/PostMapper');

		$imageManager = $this->getImageManager();

		$webPageManager = $this->getWebPageManager();
		$historyManager = $this->getHistoryManager();

		$postManager = new PostManager($postMapper, $categoryMapper, $this->getTimeBag(), $webPageManager, $imageManager, $historyManager);

		return array(
			'siteService' => new SiteService($postManager),
			'configManager' => $this->getConfigService(),
			'taskManager' => new TaskManager($postMapper),
			'categoryManager' => new CategoryManager($categoryMapper, $postMapper, $webPageManager, $historyManager, $imageManager, $this->getMenuWidget()),
			'postManager' => $postManager
		);
	}

	/**
	 * Returns time bag
	 * 
	 * @return \News\Service\TimeBag
	 */
	private function getTimeBag()
	{
		$factory = new TimeBagFactory($this->getConfigEntity());
		return $factory->build();
	}

	/**
	 * Returns prepared and configured image manager service
	 * 
	 * @return \Krystal\Image\Tool\ImageManager
	 */
	private function getImageManager()
	{
		$config = $this->getConfigEntity();

		$plugins = array(
			'thumb' => array(
				'quality' => $config->getCoverQuality(),
				'dimensions' => array(
					// For administration panel
					array(200, 200),

					// Dimensions for the site
					array($config->getCoverWidth(), $config->getCoverHeight()),
					array($config->getThumbWidth(), $config->getThumbHeight()),
				)
			)
		);

		return new ImageManager(
			'/data/uploads/module/news/posts/',
			$this->appConfig->getRootDir(),
			$this->appConfig->getRootUrl(),
			$plugins
		);
	}
}
