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

final class SiteService implements SiteServiceInterface
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
	 * A service to deal with recent products
	 * 
	 * @var \Shop\Service\RecentProductInterface
	 */
	private $recentProduct;

	/**
	 * State initialization
	 * 
	 * @param \Shop\Service\ProductManagerInterface $productManager
	 * @param \Shop\Service\RecentProductInterface $recentProduct
	 * @param \Krystal\Stdlib\VirtualEntity $config
	 * @return void
	 */
	public function __construct(ProductManagerInterface $productManager, RecentProductInterface $recentProduct, VirtualEntity $config)
	{
		$this->productManager = $productManager;
		$this->recentProduct = $recentProduct;
		$this->config = $config;
	}

	/**
	 * Returns minimal product's price associated with provided category id
	 * 
	 * @param string $categoryId
	 * @return float
	 */
	public function getMinCategoryPriceCount($categoryId)
	{
		return $this->productManager->getMinCategoryPriceCount($categoryId);
	}

	/**
	 * Returns an array of entities with products that have maximal view count
	 * 
	 * @param integer $limit
	 * @param string $categoryId Optionally can be filtered by category id
	 * @return array
	 */
	public function getProductsWithMaxViewCount($limit, $categoryId = null)
	{
		return $this->productManager->getProductsWithMaxViewCount($limit, $categoryId);
	}

	/**
	 * Returns an array of entities of recent products
	 * 
	 * @param string $id Current product id to be excluded
	 * @return array
	 */
	public function getRecentProducts($id)
	{
		// Since the method is usually gets called twice, it would make sense to cache its calls
		static $result = null;

		if ($result === null) {
			if ($this->config->getMaxRecentAmount() > 0) {
				$result = $this->recentProduct->getWithRecent($id);
			} else {
				// If that functionality is disabled, then dummy empty array is used instead
				$result = array();
			}
		}

		return $result;
	}

	/**
	 * Returns an array of latest product entities
	 * 
	 * @return array
	 */
	public function getLatest()
	{
		$count = $this->config->getShowCaseCount();
		return $this->productManager->fetchLatestPublished($count);
	}
}
