<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Photogallery\Storage;

interface AlbumMapperInterface
{
	/**
	 * Fetches album name by its associated id
	 * 
	 * @param string $id
	 * @return string
	 */
	public function fetchNameById($id);

	/**
	 * Fetches all albums
	 * 
	 * @return array
	 */
	public function fetchAll();

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

	/**
	 * Deletes an album by its associated id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function deleteById($id);

	/**
	 * Deletes all albums children by associated parent id
	 * 
	 * @pamra integer $parentId
	 * @return boolean
	 */
	public function deleteAllByParentId($parentId);

	/**
	 * Fetches a record by its id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id);

	/**
	 * Fetch all records filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Items Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage);
}
