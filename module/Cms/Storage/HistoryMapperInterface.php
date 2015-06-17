<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Storage;

interface HistoryMapperInterface
{
	/**
	 * Clears the history
	 * 
	 * @return boolean
	 */
	public function clear();

	/**
	 * Inserts a history track
	 * 
	 * @param array $data History data
	 * @return boolean
	 */
	public function insert(array $data);

	/**
	 * Fetches all history tracks filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @parma integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage);
}
