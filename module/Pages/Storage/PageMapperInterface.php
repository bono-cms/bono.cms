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

interface PageMapperInterface
{
	/**
	 * Inserts a page
	 * 
	 * @param array $data Page data
	 * @return boolean
	 */
	public function insert(array $data);

	/**
	 * Updates a page
	 * 
	 * @param array $data Page data
	 * @return boolean
	 */
	public function update(array $data);

	/**
	 * Updates whether SEO should be enabled or not
	 * 
	 * @param string $id Page id
	 * @param string $seo Either 0 or 1
	 * @return boolean
	 */
	public function updateSeoById($id, $seo);

	/**
	 * Fetches all pages filtered by pagination
	 * 
	 * @param string $page Current page id
	 * @param string $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage);

	/**
	 * Fetches page title by its associated id
	 * 
	 * @param string $id Page id
	 * @retunr string
	 */
	public function fetchTitleById($id);

	/**
	 * Fetches page data by its associated id
	 * 
	 * @param string $id Page id
	 * @return array
	 */
	public function fetchById($id);

	/**
	 * Deletes a page by its associated id
	 * 
	 * @param string $id Page id
	 * @return boolean
	 */
	public function deleteById($id);
}
