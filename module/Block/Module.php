<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
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
	public function getRoutes()
	{
		return include(__DIR__) . '/Config/routes.php';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTranslations($language)
	{
		return $this->loadArray(__DIR__ . '/Translations/'.$language.'/messages.php');
	}

	/**
	 * {@inheritDoc}
	 */
	public function getConfigData()
	{
		return include(__DIR__ . '/Config/module.config.php');
	}

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
