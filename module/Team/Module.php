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
use Cms\AbstractCmsModule;
use Team\Service\TeamManager;
use Team\Service\TeamImageManagerFactory;

final class Module extends AbstractCmsModule
{
	/**
	 * Returns image manager service
	 * 
	 * @return \Krystal\Image\Tool\ImageManager
	 */
	private function getImageManager()
	{
		$options = array(
			'thumb' => array(
				'dimensions' => array(
					// For administration
					array(400, 200),
					// For site
					array(170, 170)
				)
			),
			
			// @TODO: Needed it even?
			'original' => array(
				'prefix' => 'original'
			)
		);

		return new ImageManager(
			'/data/uploads/module/team/',
			$this->appConfig->getRootDir(),
			$this->appConfig->getRootUrl(),
			$options
		);
	}

	/**
	 * {@inheritDor}
	 */
	public function getServiceProviders()
	{
		return array(
			'teamManager' => new TeamManager($this->getMapper('/Team/Storage/MySQL/TeamMapper'), $this->getImageManager(), $this->getHistoryManager())
		);
	}
}
