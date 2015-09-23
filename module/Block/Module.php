<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Block;

use Cms\AbstractCmsModule;
use Block\Service\BlockManager;
use Block\Service\SiteService;

final class Module extends AbstractCmsModule
{
	/**
	 * {@inheritDoc}
	 */
	public function getServiceProviders()
	{
		$blockMapper = $this->getMapper('/Block/Storage/MySQL/BlockMapper');

		return array(
			'siteService' => new SiteService($blockMapper),
			'blockManager' => new BlockManager($blockMapper, $this->getHistoryManager())
		);
	}
}
