<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Announcement\Service;

use Krystal\Stdlib\VirtualEntity;

interface AnnounceManagerInterface
{
	/**
	 * Returns breadcrumb collection
	 * 
	 * @param \Krystal\Stdlib\VirtualEntity $announce
	 * @return array
	 */
	public function getBreadcrumbs(VirtualEntity $announce);

	/**
	 * Updates published state by associated announce ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updatePublished(array $pair);

	/**
	 * Updates SEO state by associated announce ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updateSeo(array $pair);

	/**
	 * Returns last announce id
	 * 
	 * @return integer
	 */
	public function getLastId();

	/**
	 * Returns prepared paginator's instance
	 * 
	 * @return \Krystal\Paginate\Paginator
	 */
	public function getPaginator();

	/**
	 * Fetches dummy announce bag
	 * 
	 * @return array
	 */
	public function fetchDummy();

	/**
	 * Adds an announce
	 * 
	 * @param array $input Raw form data
	 * @return boolean
	 */
	public function add(array $data);

	/**
	 * Updates an announce
	 * 
	 * @param array $input Raw form data
	 * @return boolean
	 */
	public function update(array $input);

	/**
	 * Fetches an announce by its associated id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id);

	/**
	 * Fetches all published announces filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllPublishedByPage($page, $itemsPerPage);

	/**
	 * Fetches all announces filtered by pagination
	 * 
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage);

	/**
	 * Fetches all announce associated with provided category id and filtered by pagination
	 * 
	 * @param string $categoryId
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByCategoryIdAndPage($categoryId, $page, $itemsPerPage);

	/**
	 * Deletes an announce by its associated id
	 * 
	 * @param string $id Announce id
	 * @return boolean
	 */
	public function deleteById($id);

	/**
	 * Delete announces by their associated ids
	 * 
	 * @param array $ids Array of announce ids
	 * @return boolean
	 */
	public function deleteByIds(array $ids);
}
