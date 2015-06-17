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

interface NotepadMapperInterface
{
	/**
	 * Checks whether notepad's data with provided user's id exists
	 * 
	 * @param string $userId
	 * @return boolean
	 */
	public function exists($userId);

	/**
	 * Inserts notepad's data
	 * 
	 * @param string $userId
	 * @param string $content
	 * @return boolean
	 */
	public function insert($userId, $content);

	/**
	 * Updates notepad's data
	 * 
	 * @param string $userId
	 * @param string $content
	 * @return boolean
	 */
	public function update($userId, $content);

	/**
	 * Fetches notepad's content by associated user id
	 * 
	 * @param string $userId
	 * @return string
	 */
	public function fetchByUserId($userId);
}
