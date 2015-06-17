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

interface ImageMapperInterface
{
	/**
	 * Fetches image's file name by its associated id
	 * 
	 * @param string $id Image's id
	 * @return string
	 */
	public function fetchFileNameById($id);

	/**
	 * Fetches all published images by associated product id
	 * 
	 * @param string $productId
	 * @return array
	 */
	public function fetchAllPublishedByProductId($productId);

	/**
	 * Fetches all images by associated product id
	 * 
	 * @param string $productId
	 * @return array
	 */
	public function fetchAllByProductId($productId);

	/**
	 * Adds an image
	 * 
	 * @param string $productId
	 * @param string $image Image's file name
	 * @param string $order Sort order
	 * @param string $published Whether image is published or not by default
	 * @return boolean
	 */
	public function insert($productId, $image, $order, $published);

	/**
	 * Deletes all images by associated product id
	 * 
	 * @param string $productId
	 * @return boolean
	 */
	public function deleteAllByProductId($productId);

	/**
	 * Updates image's filename by its associated id
	 * 
	 * @param string $id Image id
	 * @param string $filename Image filename
	 * @return boolean
	 */
	public function updateFileNameById($id, $filename);

	/**
	 * Updates sort order by image's associated id
	 * 
	 * @param string $id Image's id
	 * @param string $order New sort order
	 * @return boolean
	 */
	public function updateOrderById($id, $order);

	/**
	 * Updates image's published state
	 * 
	 * @param string $id Image's id
	 * @param string $published New state, either 1 or 0
	 * @return boolean
	 */
	public function updatePublishedById($id, $published);

	/**
	 * Delete an image by its associated id
	 * 
	 * @param string $id
	 * @return boolean Depending on success
	 */
	public function deleteById($id);
}
