<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Slider;

use Krystal\Cache\MemoryCache;
use Cms\AbstractCmsModule;
use Slider\Service\CategoryManager;
use Slider\Service\ImageManager;
use Slider\Service\TaskManager;
use Slider\Service\ImageManagerFactory;
use Slider\Service\SiteService;

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
	 * {@inhertiDoc}
	 */
	public function getTranslations($language)
	{
		return $this->loadArray(__DIR__.'/Translations/'.$language.'/messages.php');
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
		// Build required mappers
		$imageMapper = $this->getMapper('/Slider/Storage/MySQL/ImageMapper');
		$categoryMapper = $this->getMapper('/Slider/Storage/MySQL/CategoryMapper');

		$historyManager = $this->getHistoryManager();

		$imageManager = new ImageManager($imageMapper, $categoryMapper, new ImageManagerFactory($this->getAppConfig()), $historyManager);

		return array(
			
			'siteService' => new SiteService($imageManager, new MemoryCache),
			'categoryManager' => new CategoryManager($categoryMapper, $historyManager),
			'imageManager'  => $imageManager,
			'taskManager' => new TaskManager($imageMapper)
		);
	}
}
