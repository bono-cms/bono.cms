<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Shop\Storage;

interface OrderInfoMapperInterface
{
	/**
	 * Adds new order data
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function insert(array $data);

	/**
	 * Fetches latest orders
	 * 
	 * @param integer $limit
	 * @return array
	 */
	public function fetchLatest($limit);

	/**
	 * Fetches all orders filtered by pagination
	 * 
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage);

	/**
	 * Deletes an order by its associated id
	 * 
	 * @param string $id Order's id
	 * @return boolean
	 */
	public function deleteById($id);

	/**
	 * Approves an order by its associated id
	 * 
	 * @param string $id Order's id
	 * @return boolean
	 */
	public function approveById($id);
}
