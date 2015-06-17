<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Shop\Storage;

interface CategoryMapperInterface
{
	/**
	 * Fetches breadcrumb's data
	 * 
	 * @param string $id Category id
	 * @return array
	 */
	public function fetchBcData();

	/**
	 * Fetches category's title by its associated id
	 * 
	 * @param string $id Category id
	 * @return string
	 */
	public function fetchTitleById($id);

	/**
	 * Fetches all categories
	 * 
	 * @return array
	 */
	public function fetchAll();

	/**
	 * Adds a category
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function insert(array $data);

	/**
	 * Updates a category
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function update(array $data);

	/**
	 * Fetches category's data by its associated id
	 * 
	 * @param string $id Category's id
	 * @return array
	 */
	public function fetchById($id);

	/**
	 * Deletes a category by its associated id
	 * 
	 * @param string $id Category id
	 * @return boolean
	 */
	public function deleteById($id);

	/**
	 * Deletes a category by its associated parent id
	 * 
	 * @param string $parentId Category parent id
	 * @return boolean
	 */
	public function deleteByParentId($parentId);

	/**
	 * Fetches all categories filtered by pagination
	 * 
	 * @param string $id Category's id
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByIdAndPage($id, $page, $itemsPerPage);

	/**
	 * Fetches all published categories by associated id and filtered by pagination
	 * 
	 * @param string $id Category id
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllPublishedByIdAndPage($id, $page, $itemsPerPage);
}
