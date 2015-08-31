<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Team\Service;

interface TeamManagerInterface
{
	/**
	 * Fetches all published member entities
	 * 
	 * @return array
	 */
	public function fetchAllPublished();

	/**
	 * Returns prepared paginator's instance
	 * 
	 * @return \Krystal\Paginate\Paginator
	 */
	public function getPaginator();

	/**
	 * Fetches all member entities filtered by pagination
	 * 
	 * @param integer $page
	 * @param integer $itemsPerPage
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage);

	/**
	 * Fetches all published member entities filtered by pagination
	 * 
	 * @param integer $page Page number
	 * @param integer $itemsPerPage Items per page count
	 * @return array
	 */
	public function fetchAllPublishedByPage($page, $itemsPerPage);

	/**
	 * Returns last member id
	 * 
	 * @return integer
	 */
	public function getLastId();

	/**
	 * Adds a member
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function add(array $input);

	/**
	 * Updates a member
	 * 
	 * @param array $input Raw input data
	 * @return boolean Depending on success
	 */
	public function update(array $input);

	/**
	 * Fetches member's entity by associated id
	 * 
	 * @param string $id Member's id
	 * @return array
	 */
	public function fetchById($id);

	/**
	 * Removes a member by associated id
	 * 
	 * @param string $id Member id
	 * @return boolean
	 */
	public function deleteById($id);

	/**
	 * Delete members by ids
	 * 
	 * @param array $ids
	 * @return boolean
	 */
	public function deleteByIds(array $ids);

	/**
	 * Update orders by their associated ids
	 * 
	 * @param array $orders
	 * @return boolean
	 */
	public function updateOrders(array $orders);

	/**
	 * Update published state by their associated ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updatePublished(array $pair);
}
