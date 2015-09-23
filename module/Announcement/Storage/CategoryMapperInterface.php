<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Announcement\Storage;

interface CategoryMapperInterface
{
	/**
	 * Fetches as a list
	 * 
	 * @return array
	 */
	public function fetchList();

	/**
	 * Inserts a category
	 * 
	 * @param string $name Category name
	 * @param string $class Category class
	 * @return boolean Depending on success
	 */
	public function insert($name, $class);

	/**
	 * Updates a category
	 * 
	 * @param string $id Category id
	 * @param string $name New category name
	 * @param string $class New category class
	 * @return boolean
	 */
	public function update($id, $name, $class);

	/**
	 * Delete a category by its associated id
	 * 
	 * @param string $id Category id
	 * @return boolean
	 */
	public function deleteById($id);

	/**
	 * Fetches all categories
	 * 
	 * @return array
	 */
	public function fetchAll();

	/**
	 * Fetches category data by its associated id
	 * 
	 * @param string $id Category id
	 * @return array
	 */
	public function fetchById($id);

	/**
	 * Fetches category name by its associated id
	 * 
	 * @param string $id
	 * @return string
	 */
	public function fetchNameById($id);
}
