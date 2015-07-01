<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Announcement\Service;

interface CategoryManagerInterface
{
	/**
	 * Fetches category data by its associated id
	 * 
	 * @param string $id Category id
	 * @return boolean|\Krystal\Stdlib\VirtualEntity
	 */
	public function fetchById($id);

	/**
	 * Returns last category id
	 * 
	 * @return integer
	 */
	public function getLastId();

	/**
	 * Fetches all category bags
	 * 
	 * @return array
	 */
	public function fetchAll();

	/**
	 * Deletes a category by its associated id
	 * 
	 * @param string $id Category id
	 * @return boolean
	 */
	public function deleteById($id);

	/**
	 * Updates a category
	 * 
	 * @param array $input Raw form data
	 * @return boolean Depending on success
	 */
	public function update(array $input);

	/**
	 * Adds a category
	 * 
	 * @param array $input Raw form data
	 * @return boolean
	 */
	public function add(array $input);
}
