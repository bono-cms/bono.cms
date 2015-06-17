<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Announcement\Storage;

interface AnnounceMapperInterface
{
	/**
	 * Fetches announce title by its associated id
	 * 
	 * @param string $id Announce id
	 * @return string
	 */
	public function fetchTitleById($id);

	/**
	 * Deletes all announces associated with provided category id
	 * 
	 * @param string $categoryId
	 * @return boolean
	 */
	public function deleteAllByCategoryId($categoryId);

	/**
	 * Deletes an announce by its associated id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function deleteById($id);

	/**
	 * Inserts an announce
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function insert(array $data);

	/**
	 * Updates an announce
	 * 
	 * @param array $data
	 * @return boolean Depending on success
	 */
	public function update(array $data);

	/**
	 * Fetches announce data by its associated id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id);

	/**
	 * Updates announce's seo value
	 * 
	 * @param string $id Advice id
	 * @param string $seo Either 0 or 1
	 * @return boolean
	 */
	public function updateSeoById($id, $seo);

	/**
	 * Updates announce's published value
	 * 
	 * @param string $id Advice id
	 * @param string $published Either 0 or 1
	 * @return boolean
	 */
	public function updatePublishedById($id, $published);

	/**
	 * Fetches all announces filtered by pagination
	 * 
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage);

	/**
	 * Fetches all published announces
	 * 
	 * @return array
	 */
	public function fetchAllPublished();

	/**
	 * Fetches all published announces filtered by pagination
	 * 
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllPublishedByPage($page, $itemsPerPage);

	/**
	 * Fetches all published announces filter by pagination
	 * 
	 * @param string $categoryId
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByCategoryIdAndPage($categoryId, $page, $itemsPerPage);
}
