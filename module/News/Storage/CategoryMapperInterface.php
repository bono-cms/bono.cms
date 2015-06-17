<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace News\Storage;

interface CategoryMapperInterface
{
	/**
	 * Deletes a category by its associated id
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
	 * Fetches category title by its associated id
	 * 
	 * @param string $id Category id
	 * @return string
	 */
	public function fetchTitleById($id);

	/**
	 * Fetches category data by its associated id
	 * 
	 * @param string $id Category id
	 * @return array
	 */
	public function fetchById($id);

	/**
	 * Inserts a category
	 * 
	 * @param array $data Category data
	 * @return boolean
	 */
	public function insert(array $data);

	/**
	 * Updates a category
	 * 
	 * @param array $data Category data
	 * @return boolean
	 */
	public function update(array $data);
}
