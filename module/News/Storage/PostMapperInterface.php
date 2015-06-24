<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace News\Storage;

interface PostMapperInterface
{
	/**
	 * Removes all web pages by associated category id
	 * 
	 * @param string $categoryId
	 * @return boolean
	 */
	public function fetchAllWebPageIdsByCategoryId($categoryId);

	/**
	 * Fetches all post ids associated with provided category id
	 * 
	 * @param string $categoryId
	 * @return boolean
	 */
	public function fetchAllIdsWithImagesByCategoryId($categoryId);

	/**
	 * Update post's published state by its associated id
	 * 
	 * @param string $id Post id
	 * @param string $published Either 0 or 1
	 * @return boolean
	 */
	public function updatePublishedById($id, $published);

	/**
	 * Updates whether post's seo is enabled or not by its associated id
	 * 
	 * @param string $id Post id
	 * @param string $published Either 0 or 1
	 * @return boolean
	 */
	public function updateSeoById($id, $seo);

	/**
	 * Inserts a post
	 * 
	 * @param array $data Post data
	 * @return boolean Depending on success
	 */
	public function insert(array $data);

	/**
	 * Updates a post
	 * 
	 * @param array $data Post data
	 * @return boolean
	 */
	public function update(array $data);

	/**
	 * Deletes a post by its associated id
	 * 
	 * @param string $id Post id
	 * @return boolean
	 */
	public function deleteById($id);

	/**
	 * Deletes all posts associated with given category id
	 * 
	 * @param string $categoryId
	 * @return boolean
	 */
	public function deleteAllByCategoryId($categoryId);

	/**
	 * Fetches all posts
	 * 
	 * @return array
	 */
	public function fetchAll();

	/**
	 * Fetches all published posts
	 * 
	 * @return array
	 */
	public function fetchAllPublished();

	/**
	 * Fetches all published posts associated with given category id
	 * 
	 * @param string $categoryId
	 * @param integer $limit Limit for posts to be fetched
	 * @return array
	 */
	public function fetchAllPublishedByCategoryId($categoryId, $limit);

	/**
	 * Fetches all posts filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @param boolean $published Whether to fetch only published records
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage, $published);

	/**
	 * Fetches all posts by associated category id and filtered by pagination
	 * 
	 * @param string $categoryId
	 * @param boolean $published Whether to fetch only published records
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByCategoryIdAndPage($categoryId, $published, $page, $itemsPerPage);

	/**
	 * Fetches post title by its associated id
	 * 
	 * @param string $id Post id
	 * @return string
	 */
	public function fetchTitleById($id);

	/**
	 * Counts all posts by associated category id
	 * 
	 * @param string $categoryId
	 * @return integer
	 */
	public function countAllByCategoryId($categoryId);

	/**
	 * Fetches post data by its associated id
	 * 
	 * @param string $id Post id
	 * @return array
	 */
	public function fetchById($id);
}
