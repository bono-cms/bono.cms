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

interface SiteServiceInterface
{
	/**
	 * Returns an array of entities with products that have maximal view count
	 * 
	 * @param integer $limit
	 * @param string $categoryId Optionally can be filtered by category id
	 * @return array
	 */
	public function getProductsWithMaxViewCount($limit, $categoryId = null);

	/**
	 * Returns an array of entities of recent products
	 * 
	 * @param string $id Current product id to be excluded
	 * @return array
	 */
	public function getRecentProducts($id);

	/**
	 * Returns an array of latest product entities
	 * 
	 * @return array
	 */
	public function getLatest();
}
