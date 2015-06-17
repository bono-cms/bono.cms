<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace News;

use Cms\AbstractCmsModule;
use Krystal\Config\File\FileArray;
use Krystal\Image\Tool\ImageManager;
use News\Service\CategoryManager;
use News\Service\ConfigManager;
use News\Service\PostManager;
use News\Service\TaskManager;
use News\Service\PostImageManagerFactory;
use News\Service\TimeBagFactory;

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

		return array(

			'configManager' => $this->getConfigProvider(),
			'taskManager' => new TaskManager($postMapper),
			'categoryManager' => new CategoryManager($categoryMapper, $postMapper, $webPageManager, $historyManager, $imageManager, $this->getMenuWidget()),
			'postManager' => new PostManager($postMapper, $categoryMapper, $this->getTimeBag(), $webPageManager, $imageManager, $historyManager)
		);
	}

	/**
	 * Returns time bag
	 * 
	 * @return \News\Service\TimeBag
	 */
	private function getTimeBag()
	{
		$factory = new TimeBagFactory($this->getConfigProvider()->getEntity());
		return $factory->build();
	}

	/**
	 * Returns prepared config provider
	 * 
	 * @return \Krystal\Config\FileArray
	 */
	private function getConfigProvider()
	{
		static $config = null;

		if ($config === null) {
			$adapter = new FileArray(__DIR__.'/Config/module.config.php');
			$adapter->load();

			$config = new ConfigManager($adapter);
		}

		return $config;
	}

	/**
	 * Returns prepared and configured image manager
	 * 
	 * @return \Krystal\Image\Tool\ImageManager
	 */
	private function getImageManager()
	{
		$factory = new PostImageManagerFactory($this->getAppConfig(), $this->getConfigProvider()->getEntity());
		return $factory->build();
	}
}
