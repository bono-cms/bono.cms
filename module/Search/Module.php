<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Search;

use Krystal\Config\File\FileArray;
use Cms\AbstractCmsModule;
use Search\Service\SearchManager;
use Search\Service\ConfigManager;

final class Module extends AbstractCmsModule
{
	/**
	 * {@inheritDoc}
	 */
	public function getConfigData()
	{
		return include(__DIR__ . '/Config/module.config.php');
	}

	/**
	 * Returns prepared configuration service
	 * 
	 * @return \Pages\Service\ConfigManager
	 */
	private function getConfigManager()
	{
		$adapter = new FileArray(__DIR__.'/Config/module.config.php');
		$adapter->load();

		$config = new ConfigManager($adapter);

		return $config;
	}

	/**
	 * @return array
	 */
	private function grabMappers(array $collection)
	{
		$result = array();

		foreach ($collection as $module => $mappers) {
			if ($this->moduleManager->isLoaded($module)) {

				foreach ($mappers as $mapper) {
					array_push($result, $mapper);
				}
			}
		}

		return $result;
	}
	
	/**
	 * Returns all attached mappers for main mapper
	 * 
	 * @return array
	 */
	private function getAttachedMappers()
	{
		return array(
			'Pages' => array(
				'/Pages/Storage/MySQL/SearchMapper'
			),
			'News' => array(
				'/News/Storage/MySQL/SearchMapper'
			),
			'Shop' => array(
				'/Shop/Storage/MySQL/SearchMapper'
			),
			'Blog' => array(
				'/Blog/Storage/MySQL/SearchMapper'
			)
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getServiceProviders()
	{
		$searchMapper = $this->getMapper('/Search/Storage/MySQL/SearchMapper');
		
		foreach ($this->grabMappers($this->getAttachedMappers()) as $mapper) {
			$searchMapper->append($this->getMapper($mapper));
		}
		
		return array(
			'configManager' => $this->getConfigManager(),
			'searchManager' => new SearchManager($searchMapper, $this->getWebPageManager())
		);
	}
}
