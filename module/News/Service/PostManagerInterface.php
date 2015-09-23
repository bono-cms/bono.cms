<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace News\Service;

/**
 * API for PostManager
 */
interface PostManagerInterface
{
	/**
	 * Returns post breadcrumb collection
	 * 
	 * @param \News\Service\PostEntity $post
	 * @return array
	 */
	public function getBreadcrumbs(PostEntity $post);

	/**
	 * Increments view count by post id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function incrementViewCount($id);

	/**
	 * Update published by their associated ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updatePublished(array $pair);

	/**
	 * Updates seo values by associated ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updateSeo(array $pair);

	/**
	 * Delete posts by their associated ids
	 * 
	 * @param array $ids
	 * @return boolean
	 */
	public function deleteByIds(array $ids);

	/**
	 * Returns prepared paginator's instance
	 * 
	 * @return \Krystal\Paginate\Paginator
	 */
	public function getPaginator();

	/**
	 * Returns an id of latest post
	 * 
	 * @return integer
	 */
	public function getLastId();

	/**
	 * Adds a post
	 * 
	 * @param array $form Form data
	 * @return boolean Depending on success
	 */
	public function add(array $form);

	/**
	 * Updates a post
	 * 
	 * @param array $form Form data
	 * @return boolean Depending on success
	 */
	public function update(array $form);

	/**
	 * Deletes a post by its associated id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function deleteById($id);

	/**
	 * Fetches a post by its associated 
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id);

	/**
	 * Fetches all posts filtered by pagination
	 * 
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Items per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage);

	/**
	 * Fetches all posts associated with category id and filtered by pagination
	 * 
	 * @param string $id Category id
	 * @param boolean $published Whether to fetch only published records
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Items per page count
	 * @return array
	 */
	public function fetchAllByCategoryIdAndPage($id, $published, $page, $itemsPerPage);

	/**
	 * Fetches all published posts associated with category id
	 * 
	 * @param string $categoryId
	 * @param integer $limit Amount of returned posts
	 * @return array
	 */
	public function fetchAllPublishedByCategoryId($categoryId, $limit);

	/**
	 * Fetches random published posts
	 * 
	 * @param integer $amount
	 * @param string $categoryId Optionally can be filtered by category id
	 * @return array
	 */
	public function fetchRandomPublished($amount, $categoryId = null);

	/**
	 * Fetches all posts
	 * 
	 * @return array
	 */
	public function fetchAll();
}
