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

interface OrderProductMapperInterface
{
	/**
	 * Counts the sum of sold products
	 * 
	 * @return float
	 */
	public function getPriceSumCount();

	/**
	 * Counts total amount of sold products
	 * 
	 * @return integer
	 */
	public function getQtySumCount();

	/**
	 * Deletes all products associated with provided order's id
	 * 
	 * @param string $id Order's id
	 * @return boolean
	 */
	public function deleteAllByOrderId($id);

	/**
	 * Add an order
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function insert(array $data);

	/**
	 * Fetches all details by associated order's id
	 * 
	 * @param string $id Order's id
	 * @return array
	 */
	public function fetchAllDetailsByOrderId($id);
}
