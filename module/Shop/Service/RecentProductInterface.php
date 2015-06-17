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

interface RecentProductInterface
{
	/**
	 * Returns a collection of recent products
	 * 
	 * @param string $id Current product id
	 * @return array
	 */
	public function getWithRecent($id);

	/**
	 * Removes a product id from the stack
	 * 
	 * @param string $id Product id
	 * @return boolean
	 */
	public function remove($id);

	/**
	 * Checks whether we have a product id in the stack
	 * 
	 * @param string $id Product id
	 * @return boolean
	 */
	public function has($id);

	/**
	 * Returns all recent product entities
	 * 
	 * @return array
	 */
	public function getAll();

	/**
	 * Prepends a recent product's id
	 * 
	 * @param string $id Product id
	 * @return boolean
	 */
	public function prepend($id);

	/**
	 * Loads data from a storage
	 * 
	 * @return void
	 */
	public function load();

	/**
	 * Saves data (usually with its all modifications) to a storage
	 * 
	 * @return boolean
	 */
	public function save();
}
