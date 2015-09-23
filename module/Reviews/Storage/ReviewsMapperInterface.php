<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Reviews\Storage;

interface ReviewsMapperInterface
{
	/**
	 * Fetches review author's name by associated id
	 * 
	 * @param string $id
	 * @return string
	 */
	public function fetchNameById($id);

	/**
	 * Updates review published state by its associated id
	 * 
	 * @param string $id Review id
	 * @param string $published Either 0 or 1
	 * @return boolean
	 */
	public function updatePublishedById($id, $published);

	/**
	 * Inserts a review
	 * 
	 * @param array $data Review data
	 * @return boolean
	 */
	public function insert(array $data);

	/**
	 * Updates a review
	 * 
	 * @param array $data Review data
	 * @return boolean
	 */
	public function update(array $data);

	/**
	 * Fetches all published reviews filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage);

	/**
	 * Fetches all published reviews filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllPublishedByPage($page, $itemsPerPage);

	/**
	 * Fetches review data by its associated id
	 * 
	 * @param string $id Review id
	 * @return array
	 */
	public function fetchById($id);

	/**
	 * Deletes a review by its associated id
	 * 
	 * @param string $id Review id
	 * @return boolean
	 */
	public function deleteById($id);
}
