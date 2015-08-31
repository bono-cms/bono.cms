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
use Krystal\Image\Tool\ImageManager;
use Photogallery\Service\AlbumManager;
use Photogallery\Service\PhotoManager;
use Photogallery\Service\TaskManager;
use Photogallery\Service\ConfigManager;

final class Module extends AbstractCmsModule
{
	/**
	 * Returns album image manager
	 * 
	 * @return \Krystal\Image\ImageManager
	 */
	private function getAlbumImageManager()
	{
		// Grab configuration entity
		$config = $this->getConfigService()->getEntity();

		$plugins = array(
			'thumb' => array(
				'dimensions' => array(
					// Dimensions for administration panel
					array(200, 200),
					// Dimensions for the site
					array($config->getAlbumThumbWidth(), $config->getAlbumThumbHeight())
				)
			),

			'original' => array(
				'prefix' => 'original'
			)
		);

		return new ImageManager(
			'/data/uploads/module/photogallery/albums',
			$this->appConfig->getRootDir(),
			$this->appConfig->getRootUrl(),
			$plugins
		);
	}

	/**
	 * Returns prepared image manager
	 * 
	 * @return \Krystal\Image\Too\ImageManager
	 */
	private function getImageManagerService()
	{
		// Grab configuration entity
		$config = $this->getConfigService()->getEntity();

		$plugins = array(
			'thumb' => array(
				'quality' => $config->getQuality(),
				'dimensions' => array(
					// Dimensions for administration panel
					array(400, 200),
					// Dimensions for site previews. 200 are default values
					array($config->getWidth(), $config->getHeight())
				)
			),

			'original' => array(
				'quality' => $config->getQuality(),
				'prefix' => 'original',
				'max_width' => $config->getMaxWidth(),
				'max_height' => $config->getMaxHeight(),
			)
		);

		return new ImageManager(
			'/data/uploads/module/photogallery/photos',
			$this->appConfig->getRootDir(),
			$this->appConfig->getRootUrl(),
			$plugins
		);
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

		$albumManager = new AlbumManager(
			$albumMapper, 
			$photoMapper, 
			$this->getAlbumImageManager(), 
			$imageManager, 
			$webPageManager, 
			$historyManager, 
			$this->getMenuWidget()
		);

		return array(

			'configManager' => $this->getConfigService(),
			'taskManager' => new TaskManager($photoMapper, $albumManager),
			'photoManager' => new PhotoManager($photoMapper, $albumMapper, $imageManager, $historyManager),
			'albumManager' => $albumManager,
		);
	}
}
