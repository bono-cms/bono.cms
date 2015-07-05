<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Shop\Service;

use Krystal\Stdlib\VirtualEntity;

final class SiteService
{
	/**
	 * Product manager service
	 * 
	 * @var \Shop\Service\SiteService
	 */
	private $productManager;

	/**
	 * Configuration entity
	 * 
	 * @var \Krystal\Stdlib\VirtualEntity
	 */
	private $config;

	/**
	 * State initialization
	 * 
	 * @param \Shop\Service\ProductManagerInterface $productManager
	 * @param \Krystal\Stdlib\VirtualEntity $config
	 * @return void
	 */
	public function __construct(ProductManagerInterface $productManager, VirtualEntity $config)
	{
		$this->productManager = $productManager;
		$this->config = $config;
	}

	/**
	 * Returns latest product entities
	 * 
	 * @return array
	 */
	public function getLatest()
	{
		$count = $this->config->getShowCaseCount();
		return $this->productManager->fetchLatestPublished($count);
	}
}
