<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Team;

use Krystal\Image\Tool\ImageManager;
use Krystal\Config\File\FileArray;
use Krystal\Stdlib\VirtualEntity;
use Cms\AbstractCmsModule;
use Team\Service\TeamManager;
use Team\Service\ConfigManager;

final class Module extends AbstractCmsModule
{
	/**
	 * Returns configuration manager
	 * 
	 * @return \Team\Service\ConfigManager
	 */
	private function getConfigManager()
	{
		$adapter = new FileArray(__DIR__ .'/Config/module.config.php');
		$adapter->load();

		return new ConfigManager($adapter);
	}

	/**
	 * Returns image manager service
	 * 
	 * @param \Krystal\Stdlib\VirtualEntity $config
	 * @return \Krystal\Image\Tool\ImageManager
	 */
	private function getImageManager(VirtualEntity $config)
	{
		$plugins = array(
			'thumb' => array(
				'dimensions' => array(
					// For administration panel
					array(400, 200),
					// For the site
					array($config->getCoverWidth(), $config->getCoverHeight())
				)
			)
		);

		return new ImageManager(
			'/data/uploads/module/team/',
			$this->appConfig->getRootDir(),
			$this->appConfig->getRootUrl(),
			$plugins
		);
	}

	/**
	 * {@inheritDor}
	 */
	public function getServiceProviders()
	{
		$config = $this->getConfigManager();

		$teamManager = new TeamManager(
			$this->getMapper('/Team/Storage/MySQL/TeamMapper'), 
			$this->getImageManager($config->getEntity()), 
			$this->getHistoryManager()
		);

		return array(
			'teamManager' => $teamManager,
			'configManager' => $config
		);
	}
}
