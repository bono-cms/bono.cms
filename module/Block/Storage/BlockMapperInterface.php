<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Block\Storage;

interface BlockMapperInterface
{
	/**
	 * Fetches block's name by its associated class name
	 * 
	 * @param string $class
	 * @return string
	 */
	public function fetchNameByClass($class);

	/**
	 * Fetches block's content by its associated class name
	 * 
	 * @param string $class
	 * @return string
	 */
	public function fetchContentByClass($class);

	/**
	 * Fetches block name by its associated id
	 * 
	 * @param string $id
	 * @return string
	 */
	public function fetchNameById($id);

	/**
	 * Fetches block data by its associated class name
	 * 
	 * @param string $class Block's class name
	 * @return array
	 */
	public function fetchByClass($class);

	/**
	 * Fetches all blocks
	 * 
	 * @return array
	 */
	public function fetchAll();

	/**
	 * Fetches all blocks filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $perPageCount Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $perPageCount);

	/**
	 * Fetches block data by its associated id
	 * 
	 * @param string $id Block id
	 * @return array
	 */
	public function fetchById($id);

	/**
	 * Inserts block's data
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function insert(array $input);

	/**
	 * Updates block's data
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input);

	/**
	 * Deletes a block by its associated id
	 * 
	 * @param string $id Block id
	 * @return boolean Depending on success
	 */
	public function deleteById($id);
}
