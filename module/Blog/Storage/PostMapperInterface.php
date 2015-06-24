<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Blog\Storage;

interface PostMapperInterface
{
	/**
	 * Fetches post name by its associated id
	 * 
	 * @param string $id Post id
	 * @return string
	 */
	public function fetchTitleById($id);

	/**
	 * Updates post's published state
	 * 
	 * @param string $id Post id
	 * @param string $published Either 0 or 1
	 * @return boolean
	 */
	public function updatePublished($id, $published);

	/**
	 * Update post comment's state, if they are enabled or not
	 * 
	 * @param string $id Post id
	 * @param string $comments Either 0 or 1
	 * @return boolean
	 */
	public function updateComments($id, $comments);

	/**
	 * Updates post seo's state, if must be indexed or not
	 * 
	 * @parma string $id Post id
	 * @param string $seo Either 0 or 1
	 * @return boolean
	 */
	public function updateSeo($id, $seo);

	/**
	 * Inserts a post
	 * 
	 * @param array $data Post data
	 * @return boolean
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
	 * Fetches post data by its associated id
	 * 
	 * @param string $id Post id
	 * @return array
	 */
	public function fetchById($id);

	/**
	 * Deletes a post by its associated id
	 * 
	 * @param string $id Post id
	 * @return boolean
	 */
	public function deleteById($id);

	/**
	 * Deletes all posts associated with provided category id
	 * 
	 * @param string $categoryId
	 * @return boolean
	 */
	public function deleteByCategoryId($categoryId);

	/**
	 * Fetches all published posts
	 * 
	 * @return array
	 */
	public function fetchAllPublished();

	/**
	 * Fetches all posts associated with given category id and filtered by pagination
	 * 
	 * @param string $categoryId
	 * @param boolean $published Whether to fetch only published records
	 * @param integer $page Current page
	 * @param integer Per page count
	 * @return array
	 */
	public function fetchAllByCategoryIdAndPage($categoryId, $published, $page, $itemsPerPage);

	/**
	 * Fetches all posts filtered by pagination
	 * 
	 * @param boolean $published Whether to fetch only published records
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($published, $page, $itemsPerPage);

	/**
	 * Counts all posts associated with given category id
	 * 
	 * @param string $categoryId
	 * @return integer
	 */
	public function countAllByCategoryId($categoryId);
}
