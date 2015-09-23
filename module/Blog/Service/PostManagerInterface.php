<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Blog\Service;

interface PostManagerInterface
{
	/**
	 * Updates SEO states by their associated ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updateSeo(array $pair);

	/**
	 * Update comments. Enabled or disable for particular post
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updateComments(array $pair);

	/**
	 * Updates published state by their associated ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updatePublished(array $pair);

	/**
	 * Returns time format
	 * 
	 * @return string
	 */
	public function getTimeFormat();

	/**
	 * Returns prepared paginator's instance
	 * 
	 * @return \Krystal\Paginate\Paginator
	 */
	public function getPaginator();

	/**
	 * Returns last post id
	 * 
	 * @return integer
	 */
	public function getLastId();

	/**
	 * Fetches all posts filtered by pagination
	 * 
	 * @param boolean $published Whether to fetch only published records
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Items per page count
	 * @return array
	 */
	public function fetchAllByPage($published, $page, $itemsPerPage);

	/**
	 * Fetches all posts associated with given category id and filtered by pagination
	 * 
	 * @param string $categoryId
	 * @param boolean $published Whether to fetch only published records
	 * @param integer $page Current page
	 * @param integer Items per page count
	 * @return array
	 */
	public function fetchAllByCategoryIdAndPage($categoryId, $published, $page, $itemsPerPage);

	/**
	 * Fetches all published post bags
	 * 
	 * @return array
	 */
	public function fetchAllPublished();

	/**
	 * Adds a post
	 * 
	 * @param array $form Form data
	 * @return boolean
	 */
	public function add(array $form);

	/**
	 * Updates a post
	 * 
	 * @param array $form Form data
	 * @return boolean
	 */
	public function update(array $form);

	/**
	 * Fetches post bag by its associated id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id);

	/**
	 * Removes a post by its associated id
	 * 
	 * @param string $id Post's id
	 * @return boolean
	 */
	public function removeById($id);

	/**
	 * Removes posts by their associated ids
	 * 
	 * @param array $ids
	 * @return boolean
	 */
	public function removeByIds(array $ids);
}
