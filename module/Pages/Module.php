<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Pages;

use Cms\AbstractCmsModule;
use Pages\Service\PageManager;

final class Module extends AbstractCmsModule
{
	/**
	 * {@inheritDoc}
	 */
	public function getServiceProviders()
	{
		$pageMapper = $this->getMapper('/Pages/Storage/MySQL/PageMapper');
		$defaultMapper = $this->getMapper('/Pages/Storage/MySQL/DefaultMapper');

		return array(
			'pageManager' => new PageManager($pageMapper, $defaultMapper, $this->getWebPageManager(), $this->getHistoryManager(), $this->getMenuWidget())
		);
	}
}
