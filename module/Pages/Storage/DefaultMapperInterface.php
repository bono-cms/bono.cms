<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Pages\Storage;

interface DefaultMapperInterface
{
	/**
	 * Updates a page's default id by its associated id
	 * 
	 * @param string $id Page id
	 * @return boolean
	 */
	public function update($id);

	/**
	 * Inserts a new page id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function insert($id);

	/**
	 * Checks whether given language id has a default page id
	 * 
	 * @return boolean
	 */
	public function exists();

	/**
	 * Fetches all defaults
	 * 
	 * @return array
	 */
	public function fetchAll();

	/**
	 * Fetches a default page id by the current mapper's language
	 * 
	 * @return string
	 */
	public function fetchDefaultId();

	/**
	 * Fetches default data by its associated id
	 * 
	 * @param string $id
	 * @return string
	 */
	public function fetchById($id);
}
