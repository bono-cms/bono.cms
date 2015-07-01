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

use Cms\AbstractCmsModule;
use Team\Service\TeamManager;
use Team\Service\TeamImageManagerFactory;

final class Module extends AbstractCmsModule
{
	/**
	 * Returns image manager
	 * 
	 * @return \Krystal\Image\Tool\ImageManager
	 */
	private function getImageManager()
	{
		$factory = new TeamImageManagerFactory($this->getAppConfig());
		return $factory->build();
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
