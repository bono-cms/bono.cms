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

interface PhotoMapperInterface
{
	/**
	 * Fetches a photo name by its associated id
	 * 
	 * @param string $id
	 * @return string
	 */
	public function fetchNameById($id);

	/**
	 * Delete all records associated with given album id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function deleteAllByAlbumId($albumId);

	/**
	 * Updates a record
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function update(array $data);

	/**
	 * Inserts a record
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function insert(array $data);

	/**
	 * Deletes a photo by its associated id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function deleteById($id);

	/**
	 * Fetches all published records
	 * 
	 * @return array
	 */
	public function fetchAllPublished();

	/**
	 * Fetches all published records by page
	 * 
	 * @param integer $page
	 * @param integer $itemsPerPage
	 * @return array
	 */
	public function fetchAllPublihedByPage($page, $itemsPerPage);

	/**
	 * Count amount of records associated with category id
	 * 
	 * @param string $albumId
	 * @return integer
	 */
	public function countAllByAlbumId($albumId);

	/**
	 * Fetches photo ids by associated album id
	 * 
	 * @param string $albumId
	 * @return array
	 */
	public function fetchPhotoIdsByAlbumId($albumId);

	/**
	 * Fetches a record by its associated id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id);

	/**
	 * Fetches all records
	 * 
	 * @return array
	 */
	public function fetchAll();

	/**
	 * Updates published state by its associated ids
	 * 
	 * @param string $id
	 * @param string $published
	 * @return boolean
	 */
	public function updatePublishedById($id, $published);

	/**
	 * Updates an order by its associated id
	 * 
	 * @param string $id
	 * @param integer $order
	 * @return boolean
	 */
	public function updateOrderById($id, $order);

	/**
	 * Fetch records associated with category id
	 * 
	 * @param string $categoryId
	 * @param integer $page
	 * @param integer $itemsPerPage
	 * @return array
	 */
	public function fetchAllByAlbumIdAndPage($albumId, $page, $itemsPerPage);

	/**
	 * Fetches only published records associated with given album id
	 * 
	 * @param string $albumId
	 * @param integer $page
	 * @param integer $itemsPerPage
	 * @return array
	 */
	public function fetchAllPublishedByAlbumIdAndPage($albumId, $page, $itemsPerPage);

	/**
	 * Fetches all published photos associated with provided album id
	 * 
	 * @param string $albumId
	 * @return array
	 */
	public function fetchAllPublishedByAlbumId($albumId);

	/**
	 * Fetch all records filter by pagination
	 * 
	 * @param integer $page
	 * @param integer $itemsPerPage
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage);

	/**
	 * Fetches all records filtered by album id
	 * 
	 * @param string $albumId
	 * @return array
	 */
	public function fetchAllByAlbumId($albumId);
}
