<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Team\Storage;

interface TeamMapperInterface
{
	/**
	 * Fetches a name by associated id
	 * 
	 * @param string $id Member's id
	 * @return string
	 */
	public function fetchNameById($id);

	/**
	 * Updates an order by its associated id
	 * 
	 * @param string $id
	 * @param string $order
	 * @return boolean
	 */
	public function updateOrderById($id, $order);

	/**
	 * Update published state by its associated id
	 * 
	 * @param string $id
	 * @param string $published Either 0 or 1
	 * @return boolean
	 */
	public function updatePublishedById($id, $published);

	/**
	 * Updates a member
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input);

	/**
	 * Adds a member
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function insert(array $input);

	/**
	 * Deletes a record by its associated id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function deleteById($id);

	/**
	 * Fetches all published records
	 * 
	 * @param integer $page
	 * @param integer $itemsPerPage
	 * @return array
	 */
	public function fetchAllPublishedByPage($page, $itemsPerPage);

	/**
	 * Fetches all records
	 * 
	 * @return array
	 */
	public function fetchAll();

	/**
	 * Fetches all published records
	 * 
	 * @return array
	 */
	public function fetchAllPublished();

	/**
	 * Fetches all members filtered by pagination
	 * 
	 * @param integer $page
	 * @param integer $itemsPerPage
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage);

	/**
	 * Fetches a record by its associated id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id);
}
