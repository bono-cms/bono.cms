<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Pages;

use Cms\AbstractCmsModule;
use Pages\Service\PageManager;
use Pages\Service\ConfigManager;
use Krystal\Config\File\FileArray;

final class Module extends AbstractCmsModule
{
	/**
	 * Returns prepared configuration service
	 * 
	 * @return \Pages\Service\ConfigManager
	 */
	private function getConfigManager()
	{
		$adapter = new FileArray($this->getPathProvider()->getWithConfigDir('module.config.php'));
		$adapter->load();

		$config = new ConfigManager($adapter);
		
		return $config;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getServiceProviders()
	{
		$pageMapper = $this->getMapper('/Pages/Storage/MySQL/PageMapper');
		$defaultMapper = $this->getMapper('/Pages/Storage/MySQL/DefaultMapper');

		$webPageManager = $this->getWebPageManager();
		$historyManager = $this->getHistoryManager();
		$menuWidget = $this->getMenuWidget();

		return array(
			'configManager' => $this->getConfigManager(),
			'pageManager' => new PageManager($pageMapper, $defaultMapper, $webPageManager, $historyManager, $menuWidget)
		);
	}
}
