<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Photogallery\Service;

/**
 * API for PhotoManager
 */
interface PhotoManagerInterface
{
	/**
	 * Fetches dummy photo bag
	 * 
	 * @return \Krystal\Stdlib\VirtualEntity
	 */
	public function fetchDummy();

	/**
	 * Fetches random published photo
	 * 
	 * @param string $albumId Optionally can be filtered by album id
	 * @return \Krystal\Stdlib\VirtualEntity|boolean
	 */
	public function fetchRandomPublished($albumId = null);

	/**
	 * Updates published state by their associated ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updatePublished(array $pair);

	/**
	 * Update orders by their associated ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updateOrders(array $pair);

	/**
	 * Removes photos by their associated ids
	 * 
	 * @param array $ids
	 * @return boolean
	 */
	public function deleteByIds(array $ids);

	/**
	 * Removes a photo by its associated id
	 * 
	 * @param string $id Photo id
	 * @return boolean
	 */
	public function deleteById($id);

	/**
	 * Returns prepared paginator's instance
	 * 
	 * @return \Krystal\Paginate\Paginator
	 */
	public function getPaginator();

	/**
	 * Returns last photo id
	 * 
	 * @return integer
	 */
	public function getLastId();

	/**
	 * Adds a photo
	 * 
	 * @param array $form Form data
	 * @return boolean
	 */
	public function add(array $form);

	/**
	 * Updates a photo
	 * 
	 * @param array $form Form data
	 * @return boolean
	 */
	public function update(array $form);

	/**
	 * Fetches a photo bag by its associated id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id);

	/**
	 * Fetches all photos filtered by pagination
	 * 
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Items per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage);

	/**
	 * Fetches all photos associated with album id
	 * 
	 * @param string $albumId
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByAlbumIdAndPage($albumId, $page, $itemsPerPage);

	/**
	 * Fetches only published photos
	 * 
	 * @param string $albumId
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllPublishedByAlbumIdAndPage($albumId, $page, $itemsPerPage);
}
