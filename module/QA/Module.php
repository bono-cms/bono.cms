<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Qa;

use Krystal\Config\File\FileArray;
use Cms\AbstractCmsModule;
Use Qa\Service\QaManager;
Use Qa\Service\ConfigManager;

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
	 * {@inheritDoc}
	 */
	public function getServiceProviders()
	{
		return array(
			'qaManager' => new QaManager($this->getMapper('/Qa/Storage/MySQL/QaMapper'), $this->getHistoryManager(), $this->getNotificationManager()),
			'configManager' => $this->getConfigManager()
		);
	}
}
