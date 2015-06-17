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

interface BasketManagerInterface
{
	/**
	 * Saves changes to a storage
	 * 
	 * @return \Shop\Service\BasketManager
	 */
	public function save();

	/**
	 * Loads data from a storage
	 * 
	 * @return void
	 */
	public function load();

	/**
	 * Clears the basket
	 * 
	 * @return void
	 */
	public function clear();

	/**
	 * Returns all product entities stored in the basket
	 * 
	 * @return array
	 */
	public function getProducts();

	/**
	 * Returns static by associated product id stored in the basket
	 * 
	 * @param string $id Product id
	 * @return array
	 */
	public function getProductStat($id);

	/**
	 * Returns total products quantity and total price of them all
	 * 
	 * @return array
	 */
	public function getAllStat();

	/**
	 * Returns total price of all products stored in the basket
	 * 
	 * @return float
	 */
	public function getTotalPrice();

	/**
	 * Returns total quantity
	 * 
	 * @return integer
	 */
	public function getTotalQuantity();

	/**
	 * Recounts price with new quantity
	 * 
	 * @param string $id Product id
	 * @param integer $newQty New quantity
	 * @return boolean
	 */
	public function recount($id, $newQty);

	/**
	 * Adds product's id to the basket
	 * 
	 * @param string $id Product id
	 * @param integer $qty Quantity of product ids to be added
	 * @return boolean
	 */
	public function add($id, $qty);

	/**
	 * Removes a product from a basket by its associated id
	 * 
	 * @param string $id Product id to be removed
	 * @param boolean
	 */
	public function removeById($id);
}
