<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Reviews\Service;

interface ReviewsManagerInterface
{
	/**
	 * Updates published state by their associated ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updatePublished(array $pair);

	/**
	 * Deletes reviews by theirs associated ids
	 * 
	 * @param array $ids
	 * @return boolean
	 */
	public function deleteByIds(array $ids);

	/**
	 * Deletes a review by its associated id
	 * 
	 * @param string $id Target review id to be removed
	 * @return boolean
	 */
	public function deleteById($id);

	/**
	 * Returns prepared paginator's instance
	 * 
	 * @return object
	 */
	public function getPaginator();

	/**
	 * Returns default time format
	 * 
	 * @return string
	 */
	public function getTimeFormat();

	/**
	 * Fetches all published reviews filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Items to be shown per page
	 * @return array
	 */
	public function fetchAllPublishedByPage($page, $itemsPerPage);

	/**
	 * Fetches all reviews filtered pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Items to be shown per page
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage);

	/**
	 * Returns last id
	 * 
	 * @return integer
	 */
	public function getLastId();

	/**
	 * Fetches a review by its associated id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id);

	/**
	 * Adds a review
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function add(array $data);

	/**
	 * Sends data from a user
	 * 
	 * @param array $data
	 * @param boolean $enableModeration Whether a review should be moderated or not
	 * @return boolean
	 */
	public function send(array $data, $enableModeration);

	/**
	 * Updates a review
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function update(array $data);
}
