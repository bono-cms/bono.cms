<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace News\Service;

use Krystal\Stdlib\VirtualEntity;

/* API for Category manager */
interface CategoryManagerInterface
{
	/**
	 * Returns breadcrumbs for the view layer
	 * 
	 * @param \Krystal\Stdlib\VirtualEntity $category
	 * @return array
	 */
	public function getBreadcrumbs(VirtualEntity $category);

	/**
	 * Returns category's last id
	 * 
	 * @return string
	 */
	public function getLastId();

	/**
	 * Fetches dummy category entity
	 * 
	 * @return \Krystal\Stdlib\VirtualEntity
	 */
	public function fetchDummy();

	/**
	 * Fetches all category bags
	 * 
	 * @return array|boolean
	 */
	public function fetchAll();

	/**
	 * Adds a category
	 * 
	 * @param array $input Raw form data
	 * @return boolean Depending on success
	 */
	public function add(array $input);

	/**
	 * Updates a category
	 * 
	 * @param array $input Raw form data
	 * @return boolean Depending on success
	 */
	public function update(array $form);

	/**
	 * Fetches category bag by its associated id
	 * 
	 * @param string $id Category id
	 * @return \Krystal\Stdlib\VirtualBag|boolean
	 */
	public function fetchById($id);

	/**
	 * Deletes a category by its associated id
	 * 
	 * @param string $id Category id
	 * @return boolean Depending on success
	 */
	public function deleteById($id);
}
