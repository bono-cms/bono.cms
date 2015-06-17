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

interface CategoryManagerInterface
{
	/**
	 * Returns category's breadcrumbs
	 * 
	 * @param \Shop\Service\CategoryEntity $category
	 * @return array
	 */
	public function getBreadcrumbs(CategoryEntity $category);

	/**
	 * Fetches all categories
	 * 
	 * @return array
	 */
	public function fetchAll();

	/**
	 * Returns last category's id
	 * 
	 * @return integer
	 */
	public function getLastId();

	/**
	 * Fetches dummy category's entity
	 * 
	 * @return \Shop\Service\CategoryEntity
	 */
	public function fetchDummy();

	/**
	 * Updates a category
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input);

	/**
	 * Adds a category
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function add(array $input);

	/**
	 * Removes a category by its associated id
	 * 
	 * @param string $id Category id
	 * @return boolean
	 */
	public function removeById($id);

	/**
	 * Fetches category's entity by its associated id
	 * 
	 * @param string $id Category id
	 * @return \Shop\Service\CategoryEntity|boolean
	 */
	public function fetchById($id);
}
