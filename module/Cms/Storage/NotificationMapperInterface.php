<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Storage;

interface NotificationMapperInterface
{
	/**
	 * Fetch all records filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage);

	/**
	 * Makes all notifications as read
	 * 
	 * @return boolean
	 */
	public function nullify();

	/**
	 * Counts all not viewed records
	 * 
	 * @return integer
	 */
	public function countUnviewed();

	/**
	 * Inserts a notification
	 * 
	 * @param string $timestamp
	 * @param string $viewed Either 0 or 1
	 * @param string $message
	 * @return boolean
	 */
	public function insert($timestamp, $viewed, $message);

	/**
	 * Deletes a notification by its associated id
	 * 
	 * @param string $id Notification id
	 * @return boolean
	 */
	public function deleteById($id);
}
