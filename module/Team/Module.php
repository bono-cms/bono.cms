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
use Krystal\Stdlib\VirtualEntity;
use Cms\AbstractCmsModule;
use Team\Service\TeamManager;
use Team\Service\SiteService;

final class Module extends AbstractCmsModule
{
	/**
	 * Returns image manager service
	 * 
	 * @return \Krystal\Image\Tool\ImageManager
	 */
	private function getImageManager()
	{
		$config = $this->getConfigService()->getEntity();

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
		$teamManager = new TeamManager(
			$this->getMapper('/Team/Storage/MySQL/TeamMapper'), 
			$this->getImageManager(), 
			$this->getHistoryManager()
		);

		return array(
			'siteService' => new SiteService($teamManager),
			'teamManager' => $teamManager,
			'configManager' => $this->getConfigService()
		);
	}
}
