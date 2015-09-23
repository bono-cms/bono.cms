<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Slider\Storage;

interface ImageMapperInterface
{
	/**
	 * Fetches image's name by its associated id
	 * 
	 * @param string $id Image id
	 * @return string
	 */
	public function fetchNameById($id);

	/**
	 * Updates image's published state by its associated id
	 * 
	 * @param integer $id Image id
	 * @param string $published Either 0 or 1
	 * @return boolean
	 */
	public function updatePublishedById($id, $published);

	/**
	 * Updates image's order by its associated id
	 * 
	 * @param string $id Image id
	 * @param string $order New order
	 * @return boolean
	 */
	public function updateOrderById($id, $order);

	/**
	 * Fetches all associated image ids with their associated category id
	 * 
	 * @param string $categoryId
	 * @return array
	 */
	public function fetchIdsByCategoryId($categoryId);

	/**
	 * Counts all images associated with given category id
	 * 
	 * @param string $categoryId
	 * @return integer
	 */
	public function countAllByCategoryId($categoryId);

	/**
	 * Fetches image id by its associated category id
	 * 
	 * @param string $id Image id
	 * @return string
	 */
	public function fetchCategoryIdById($id);

	/**
	 * Fetches all images filtered by pagination
	 * 
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage);

	/**
	 * Fetches all published images filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllPublishedByPage($page, $itemsPerPage);

	/**
	 * Fetches all published images associated with given category id
	 * 
	 * @param string $categoryId Category id
	 * @return array
	 */
	public function fetchAllPublishedByCategoryId($categoryId);

	/**
	 * Fetches all published images by category id and filtered by pagination
	 * 
	 * @param integer $categoryId
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllPublishedByCategoryIdAndPage($categoryId, $page, $itemsPerPage);

	/**
	 * Fetches all images by category id and filtered by pagination
	 * 
	 * @param string $categoryId
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByCategoryAndPage($categoryId, $page, $itemsPerPage);

	/**
	 * Updates an image
	 * 
	 * @param array $data Image data
	 * @return boolean
	 */
	public function update(array $data);

	/**
	 * Inserts an image
	 * 
	 * @param array $data Image data
	 * @return boolean
	 */
	public function insert(array $data);

	/**
	 * Fetches an image by its associated id
	 * 
	 * @param string $id Image id
	 * @return array
	 */
	public function fetchById($id);

	/**
	 * Deletes an image by its associated id
	 * 
	 * @param string $id Image id
	 * @return boolean
	 */
	public function deleteById($id);
}
