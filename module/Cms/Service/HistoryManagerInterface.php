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

interface HistoryManagerInterface
{
	/**
	 * Defines user's id that make changes
	 * 
	 * @param string $userId
	 * @return void
	 */
	public function setUserId($userId);

	/**
	 * Sets whether history manager should be enabled or not
	 * 
	 * @param boolean $enabled
	 * @return void
	 */
	public function setEnabled($enabled);

	/**
	 * Adds a record
	 * 
	 * @param string $module A target module
	 * @param string $comment What have been done
	 * @param string $placeholder
	 * @return boolean
	 */
	public function write($module, $comment, $placeholder);

	/**
	 * Returns prepared paginator instance
	 * 
	 * @return \Krystal\Paginate\Paginator
	 */
	public function getPaginator();

	/**
	 * Fetches all record entities filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage);

	/**
	 * Deletes a record by its associated id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function deleteById($id);
}
