<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Advice\Service;

/* API for AdviceManager */
interface AdviceManagerInterface
{
	/**
	 * Update published state by their associated ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updatePublished(array $pair);

	/**
	 * Delete advices by their associated ids
	 * 
	 * @param array $ids
	 * @return boolean
	 */
	public function deleteByIds(array $ids);

	/**
	 * Fetches dummy advice entity
	 * 
	 * @return \Krystal\Stdlib\VirtualEntity
	 */
	public function fetchDummy();

	/**
	 * Fetches random advice entity
	 * 
	 * @return boolean|\Krystal\Stdlib\VirtualEntity
	 */
	public function fetchRandom();

	/**
	 * Returns prepared paginator's instance
	 * 
	 * @return \Krystal\Paginate\Paginator
	 */
	public function getPaginator();

	/**
	 * Returns last advice id
	 * 
	 * @return integer
	 */
	public function getLastId();

	/**
	 * Adds an advice
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function add(array $input);

	/**
	 * Updates an advice
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $data);

	/**
	 * Fetches advice entities filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Items per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage);

	/**
	 * Deletes an advice by its associated id
	 * 
	 * @param string $id Advice id
	 * @return boolean
	 */
	public function deleteById($id);

	/**
	 * Fetches advice's entity by its associated id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id);
}
