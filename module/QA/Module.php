<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Qa;

use Cms\AbstractCmsModule;
Use Qa\Service\QaManager;

final class Module extends AbstractCmsModule
{
	/**
	 * {@inheritDoc}
	 */
	public function getServiceProviders()
	{
		return array(
			'qaManager' => new QaManager($this->getMapper('/Qa/Storage/MySQL/QaMapper'), $this->getHistoryManager()),
			'configManager' => $this->getConfigService()
		);
	}
}
