<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Photogallery;

use Cms\AbstractCmsModule;
use Krystal\Config\File\FileArray;
use Photogallery\Service\AlbumManager;
use Photogallery\Service\PhotoManager;
use Photogallery\Service\TaskManager;
use Photogallery\Service\ConfigManager;
use Photogallery\Service\PhotoManagerFactory;

final class Module extends AbstractCmsModule
{
	/**
	 * Returns prepared image manager
	 * 
	 * @return \Krystal\Image\Too\ImageManager
	 */
	private function getImageManagerService()
	{
		$factory = new PhotoManagerFactory($this->getAppConfig(), $this->getConfigService()->getEntity());
		return $factory->build();
	}

	/**
	 * Returns prepared and configured configuration
	 * 
	 * @return \Krystal\Config\FileArray
	 */
	private function getConfigService()
	{
		static $config = null;

		if (is_null($config)) {
			$adapter = new FileArray(__DIR__.'/Config/module.config.php');
			$adapter->load();

			$config = new ConfigManager($adapter);
		}
		
		return $config;
	}

	/**
	 * Returns language id
	 * 
	 * @return integer
	 */
	private function getLanguageId()
	{
		$cms = $this->moduleManager->getModule('Cms');
		$languageManager = $cms->getService('languageManager');

		$config = $this->getConfigService();

		if ($config->get('language_support') == '1') {
			return $languageManager->getCurrentId();
		} else {
			return 0;
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function getServiceProviders()
	{
		// Build mappers
		$albumMapper = $this->getMapper('/Photogallery/Storage/MySQL/AlbumMapper');
		$photoMapper = $this->getMapper('/Photogallery/Storage/MySQL/PhotoMapper');

		// Grab required services
		$historyManager = $this->getHistoryManager();
		$webPageManager = $this->getWebPageManager();
		$imageManager = $this->getImageManagerService();

		$albumManager = new AlbumManager($albumMapper, $photoMapper, $imageManager, $webPageManager, $historyManager, $this->getMenuWidget());

		return array(
			
			'configManager' => $this->getConfigService(),
			'taskManager' => new TaskManager($photoMapper, $albumManager),
			'photoManager' => new PhotoManager($photoMapper, $albumMapper, $imageManager, $historyManager),
			'albumManager' => $albumManager,
		);
	}
}
