<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Slider\Service;

interface CategoryManagerInterface
{
	/**
	 * Fetches as a list
	 * 
	 * @return array
	 */
	public function fetchList();

	/**
	 * Returns last category id
	 * 
	 * @return integer
	 */
	public function getLastId();

	/**
	 * Fetch a category bag by associated id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id);

	/**
	 * Fetches all category bags
	 * 
	 * @return array
	 */
	public function fetchAll();

	/**
	 * Deletes a category by its associated id
	 * 
	 * @param string $id
	 * @return boolean
	 */ 
	public function deleteById($id);

	/**
	 * Adds a category
	 * 
	 * @param array $data Form data
	 * @return boolean
	 */
	public function add(array $data);

	/**
	 * Updates a category
	 * 
	 * @param array $data Form data
	 * @return boolean
	 */
	public function update(array $data);
}
