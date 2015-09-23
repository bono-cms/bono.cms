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

/**
 * API for the Category Mapper
 */
interface CategoryMapperInterface
{
	/**
	 * Fetches category class by its associated id
	 * 
	 * @param string $id Category id
	 * @return string
	 */
	public function fetchClassById($id);

	/**
	 * Fetch maximal allowed depth of nested level
	 * 
	 * @param string $id Category id
	 * @return integer
	 */
	public function fetchMaxDepthById($id);

	/**
	 * Fetches category's name by its associated class name
	 * 
	 * @param string $class Category's name
	 * @return string
	 */
	public function fetchNameByClass($class);

	/**
	 * Fetch unique classes
	 * 
	 * @return array
	 */
	public function fetchClasses();

	/**
	 * Fetches category name by its associated id
	 * 
	 * @param string $id
	 * @return string
	 */
	public function fetchNameById($id);

	/**
	 * Fetches the first id
	 * 
	 * @return string
	 */
	public function fetchFirstId();

	/**
	 * Fetches the last id
	 * 
	 * @return integer
	 */
	public function fetchLastId();

	/**
	 * Fetches an id by its associated class
	 * 
	 * @param string $class
	 * @return string
	 */
	public function fetchIdByClass($class);

	/**
	 * Fetches all records
	 * 
	 * @return array
	 */
	public function fetchAll();

	/**
	 * Deletes a record by its associated id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function deleteById($id);

	/**
	 * Fetch a record by its associated id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id);

	/**
	 * Inserts a record
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function insert(array $data);

	/**
	 * Updates a record
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function update(array $data);
}
