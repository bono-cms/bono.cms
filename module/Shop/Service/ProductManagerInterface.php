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

interface ProductManagerInterface
{
	/**
	 * Returns product's breadcrumbs collection
	 * 
	 * @param \Shop\Service\ProductEntity $product
	 * @return array
	 */
	public function getBreadcrumbs(ProductEntity $product);

	/**
	 * Fetches all product's photo entities by its associated id
	 * 
	 * @param string $id Product id
	 * @return array
	 */
	public function fetchAllImagesById($id);

	/**
	 * Fetches all published product's photo entities by its associated id
	 * 
	 * @param string $id Product id
	 * @return array
	 */
	public function fetchAllPublishedImagesById($id);

	/**
	 * Increments view count by product's id
	 * 
	 * @param string $id Product id
	 * @return boolean
	 */
	public function incrementViewCount($id);

	/**
	 * Updates prices by their associated ids and values
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updatePrices(array $pair);

	/**
	 * Updates published state by their associated ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updatePublished(array $pair);

	/**
	 * Update SEO state by their associated ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updateSeo(array $pair);

	/**
	 * Removes products by their associated ids
	 * 
	 * @param array $ids
	 * @return boolean
	 */
	public function removeByIds(array $ids);

	/**
	 * Returns prepared paginator's instance
	 * 
	 * @return \Krystal\Paginate\Paginator
	 */
	public function getPaginator();

	/**
	 * Returns last product's id
	 * 
	 * @return integer
	 */
	public function getLastId();

	/**
	 * Adds a product
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function add(array $input);

	/**
	 * Updates a product
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input);

	/**
	 * Fetches all published product entities with maximal view counts
	 * 
	 * @param integer $limit Fetching limit
	 * @param string $categoryId Optionally can be filtered by category id
	 * @return array
	 */
	public function fetchAllPublishedWithMaxViewCount($limit, $categoryId = null);

	/**
	 * Returns minimal product's price associated with provided category id
	 * It's aware only of published products
	 * 
	 * @param string $categoryId
	 * @return float
	 */
	public function getMinCategoryPriceCount($categoryId);

	/**
	 * Fetches all published product entities associated with given category id
	 * 
	 * @param string $categoryId
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @param string $sort Sorting constant
	 * @return array
	 */
	public function fetchAllPublishedByCategoryIdAndPage($categoryId, $page, $itemsPerPage, $sort);

	/**
	 * Fetches all product entities filtered by pagination
	 * 
	 * @param integer $page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage);

	/**
	 * Fetches all product entities associated with category id and filtered by pagination
	 * 
	 * @param integer $id Category id
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByCategoryIdAndPage($id, $page, $itemsPerPage);

	/**
	 * Fetches all published product entities associated with given category id
	 * 
	 * @param string $categoryId
	 * @return array
	 */
	public function fetchAllPublishedByCategoryId($categoryId);

	/**
	 * Fetches product's entity by its associated id
	 * 
	 * @param string $id
	 * @return \Krystal\Stdlib\VirtualEntity|boolean
	 */
	public function fetchById($id);

	/**
	 * Fetches latest product entities
	 * 
	 * @param integer $limit Limit for fetching
	 * @return array
	 */
	public function fetchLatestPublished($limit);
}
