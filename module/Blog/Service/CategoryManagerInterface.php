<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Blog\Service;

use Krystal\Stdlib\VirtualEntity;

interface CategoryManagerInterface
{
	/**
	 * Returns breadcrumbs for category by its entity
	 * 
	 * @param \Krystal\Stdlib\VirtualEntity $category
	 * @return array
	 */
	public function getBreadcrumbs(VirtualEntity $category);

	/**
	 * Returns last category's id
	 * 
	 * @return integer
	 */
	public function getLastId();

	/**
	 * Removes a category by its associated id
	 * 
	 * @param string $id Category's id
	 * @return boolean
	 */
	public function removeById($id);

	/**
	 * Fetches as a list
	 * 
	 * @return array
	 */
	public function fetchList();

	/**
	 * Fetches category's entity by its associated id
	 * 
	 * @param string $id
	 * @return \Krystal\Stdlib\VirtualEntity|boolean
	 */
	public function fetchById($id);

	/**
	 * Fetches all category entities
	 * 
	 * @return array
	 */
	public function fetchAll();

	/**
	 * Adds a category
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function add(array $input);

	/**
	 * Updates a category
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input);
}
