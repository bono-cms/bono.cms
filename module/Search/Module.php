<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Search;

use Cms\AbstractCmsModule;
use Search\Service\SearchManager;
use Search\Service\SiteService;

final class Module extends AbstractCmsModule
{
	/**
	 * Returns mappers that should be attached to the search
	 * 
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
			),
			'Announcement' => array(
				'/Announcement/Storage/MySQL/SearchMapper'
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
			'siteService' => new SiteService(),
			'configManager' => $this->getConfigService(),
			'searchManager' => new SearchManager($searchMapper, $this->getWebPageManager())
		);
	}
}
