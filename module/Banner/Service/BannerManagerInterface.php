<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Banner\Service;

/* API for Banner Manager */
interface BannerManagerInterface
{
	/**
	 * Returns prepared paginator's instance
	 * 
	 * @return \Krystal\Paginate\Paginator
	 */
	public function getPaginator();

	/**
	 * Returns last banner's id
	 * 
	 * @return integer
	 */
	public function getLastId();

	/**
	 * Fetches all banner entities filtered by pagination
	 * 
	 * @param string $page Current page
	 * @param string $itemsPerPage Per page count
	 * @return array An array of banner entities
	 */
	public function fetchAllByPage($page, $itemsPerPage);

	/**
	 * Fetches random banner's entity
	 * 
	 * @return \Krystal\Stdlib\VirtualEntity
	 */
	public function fetchRandom();

	/**
	 * Fetches banner's entity by its associated id
	 * 
	 * @param string $id Banner id
	 * @return boolean|\Krystal\Stdlib\VirtualEntity|boolean
	 */
	public function fetchById($id);

	/**
	 * Adds a banner
	 * 
	 * @param array $input Raw form input
	 * @return boolean
	 */
	public function add(array $form);

	/**
	 * Updates a banner
	 * 
	 * @param array $input Raw form input
	 * @return boolean
	 */
	public function update(array $input);

	/**
	 * Deletes a banner by its associated id
	 * 
	 * @param string $id Banner id
	 * @return boolean
	 */
	public function deleteById($id);

	/**
	 * Delete banners by their associated ids
	 * 
	 * @param array $ids Array of banner ids
	 * @return boolean
	 */
	public function deleteByIds(array $ids);
}
