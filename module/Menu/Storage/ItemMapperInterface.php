<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Menu\Storage;

use Krystal\Tree\AdjacencyList\ChildrenOrderSaverMapperInterface;

interface ItemMapperInterface extends ChildrenOrderSaverMapperInterface
{
	/**
	 * Fetches category id by its associated web page id
	 * 
	 * @param string $webPageId
	 * @return string
	 */
	public function fetchCategoryIdByWebPageId($webPageId);

	/**
	 * Fetches all items associated with given web page id
	 * 
	 * @param string $webPageId
	 * @return array
	 */
	public function fetchAllByWebPageId($webPageId);

	/**
	 * Fetches item's name by its associated id
	 * 
	 * @param string $id
	 * @return string
	 */
	public function fetchNameById($id);

	/**
	 * Fetches all items associated with given category id
	 * 
	 * @param string $categoryId
	 * @return array
	 */
	public function fetchAllByCategoryId($categoryId);

	/**
	 * Fetches all published items associated with given category id
	 * 
	 * @param string $categoryId
	 * @return array
	 */
	public function fetchAllPublishedByCategoryId($categoryId);

	/**
	 * Deletes an item by its associated id
	 * 
	 * @param string $id
	 * @return boolean Depending on success
	 */
	public function deleteById($id);

	/**
	 * Deletes all items associated with given category id
	 * 
	 * @param string $categoryId
	 * @return boolean
	 */
	public function deleteAllByCategoryId($categoryId);

	/**
	 * Delete all items associated with given parent id
	 * 
	 * @param string $parentId
	 * @return boolean
	 */
	public function deleteAllByParentId($parentId);

	/**
	 * Deletes all items by associated web page id
	 * 
	 * @param string $webPageId
	 * @return boolean
	 */
	public function deleteAllByWebPageId($webPageId);

	/**
	 * Fetches an item by its associated id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id);

	/**
	 * Fetches all items
	 * 
	 * @return array
	 */
	public function fetchAll();

	/**
	 * Inserts an item
	 * 
	 * @param array $data
	 * @return boolean Depending on success
	 */
	public function insert(array $data);

	/**
	 * Updates an item
	 * 
	 * @param array $data
	 * @return boolean Depending on success
	 */
	public function update(array $data);
}
