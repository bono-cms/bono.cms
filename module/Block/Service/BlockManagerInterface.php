<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Block\Service;

interface BlockManagerInterface
{
	/**
	 * Returns prepared paginator's instance
	 * 
	 * @return \Krystal\Paginate\Paginator
	 */
	public function getPaginator();

	/**
	 * Fetches all blocks filtered by paginator
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage $itemsPerPage
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage);

	/**
	 * Returns last id
	 * 
	 * @return integer
	 */
	public function getLastId();

	/**
	 * Fetches all blocks
	 * 
	 * @return array
	 */
	public function fetchAll();

	/**
	 * Adds a block
	 * 
	 * @param array $form
	 * @return boolean
	 */
	public function add(array $form);

	/**
	 * Updates a block
	 * 
	 * @param array $form
	 * @return boolean Depending on success
	 */
	public function update(array $form);

	/**
	 * Fetches a block bag by its associated id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id);

	/**
	 * Deletes a block by its associated id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function deleteById($id);

	/**
	 * Delete blocks by their associated id
	 * 
	 * @param array $ids
	 * @return boolean
	 */
	public function deleteByIds(array $ids);
}
