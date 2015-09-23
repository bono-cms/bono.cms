<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Menu\Service;

interface CategoryManagerInterface
{
	/**
	 * Fetches the first category id
	 * 
	 * @return string
	 */
	public function fetchFirstId();

	/**
	 * Fetches the last inserted id
	 * 
	 * @return integer
	 */
	public function fetchLastId();

	/**
	 * Returns last inserted id
	 * 
	 * @return integer
	 */
	public function getLastId();

	/**
	 * Fetches unique category classes
	 * 
	 * @return array
	 */
	public function fetchClasses();

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

	/**
	 * Deletes a category by its associated id
	 * Also remove items associated with given category id
	 * 
	 * @param string $id
	 * @return boolean Depending on success
	 */
	public function deleteById($id);

	/**
	 * Fetches all categories
	 * Returns an array of category bags
	 * 
	 * @return array
	 */
	public function fetchAll();

	/**
	 * Fetches a category bag by its associated id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id);
}
