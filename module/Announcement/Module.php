<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Announcement;

use Cms\AbstractCmsModule;
use Announcement\Service\AnnounceManager;
use Announcement\Service\CategoryManager;
use Announcement\Service\SiteService;

final class Module extends AbstractCmsModule
{
	/**
	 * {@inheritDoc}
	 */
	public function getRoutes()
	{
		return include(__DIR__ . '/Config/routes.php');
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTranslations($language)
	{
		return $this->loadArray(__DIR__ . '/Translations/'.$language.'/messages.php');
	}

	/**
	 * {@inheritDoc}
	 */
	public function getConfigData()
	{
		return include(__DIR__ . '/Config/module.config.php');
	}

	/**
	 * {@inheritDoc}
	 */
	public function getServiceProviders()
	{
		// Build mappers
		$categoryMapper = $this->getMapper('/Announcement/Storage/MySQL/CategoryMapper');
		$announceMapper = $this->getMapper('/Announcement/Storage/MySQL/AnnounceMapper');

		$webPageManager = $this->getWebPageManager();
		$historyManager = $this->getHistoryManager();
		
		$announceManager = new AnnounceManager($announceMapper, $categoryMapper, $webPageManager, $historyManager);
		
		$siteService = new SiteService($announceManager, $categoryMapper);
		
		return array(
			'siteService' => $siteService,
			'announceManager' => $announceManager,
			'categoryManager' => new CategoryManager($categoryMapper, $announceMapper, $historyManager)
		);
	}
}
