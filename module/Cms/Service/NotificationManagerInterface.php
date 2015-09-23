<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Service;

interface NotificationManagerInterface
{
	/**
	 * Marks all messages as read
	 * 
	 * @return boolean
	 */
	public function nullify();

	/**
	 * Returns amount of unviewed notifications
	 * 
	 * @return integer
	 */
	public function getUnviewedCount();

	/**
	 * Adds a new notification
	 * 
	 * @param string $message
	 * @return boolean
	 */
	public function notify($message);

	/**
	 * Deletes a notification by its associated id
	 * 
	 * @param string $id Target id
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
	 * Fetch all notification entities filtered by pagination
	 * 
	 * @param integer $page
	 * @param integer $itemsPerPage
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage);

	/**
	 * Clears all notifications
	 * 
	 * @return boolean
	 */
	public function clearAll();
}
