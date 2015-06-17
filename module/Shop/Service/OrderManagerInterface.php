<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Shop\Service;

interface OrderManagerInterface
{
	/**
	 * Approves an order by its associated id
	 * 
	 * @param string $id Order's id
	 * @return boolean
	 */
	public function approveById($id);

	/**
	 * Removes an order by its associated id
	 * 
	 * @param string $id Order's id
	 * @return boolean
	 */
	public function removeById($id);

	/**
	 * Fetches all order's details by its associated id
	 * 
	 * @param string $id Order id
	 * @return array
	 */
	public function fetchAllDetailsByOrderId($id);

	/**
	 * Returns prepared paginator's instance
	 * 
	 * @return \Krystal\Paginate\Paginator
	 */
	public function getPaginator();

	/**
	 * Makes an order
	 * 
	 * @param array $input Raw input data
	 * @param array $products Products from basket
	 * @return boolean
	 */
	public function make(array $input);

	/**
	 * Fetches latest order entities
	 * 
	 * @param integer $limit
	 * @return array
	 */
	public function fetchLatest($limit);

	/**
	 * Fetches all entities filtered by pagination
	 * 
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage);
}
