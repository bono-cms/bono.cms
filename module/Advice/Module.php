<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Advice;

use Advice\Service\AdviceManager;
use Advice\Service\SiteService;
use Cms\AbstractCmsModule;

final class Module extends AbstractCmsModule
{
	/**
	 * {@inheritDoc}
	 */
	public function getServiceProviders()
	{
		$adviceManager = new AdviceManager($this->getMapper('/Advice/Storage/MySQL/AdviceMapper'), $this->getHistoryManager());
		$siteService = new SiteService($adviceManager);

		return array(
			'adviceManager' => $adviceManager,
			'siteService' => $siteService
		);
	}
}
