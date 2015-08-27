<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Qa\Service;

interface QaManagerInterface
{
	/**
	 * Delete QA pairs by their associated ids
	 * 
	 * @param array $ids
	 * @return boolean
	 */
	public function deleteByIds(array $ids);

	/**
	 * Deletes QA pair by its associated id
	 * 
	 * @param string $id QA id
	 * @return boolean
	 */
	public function deleteById($id);

	/**
	 * Updates published states by their associate ids
	 * 
	 * @param array $pair Array of ids
	 * @return boolean
	 */
	public function updatePublished(array $pair);

	/**
	 * Returns default time format
	 * 
	 * @return string
	 */
	public function getTimeFormat();

	/**
	 * Returns last QA id
	 * 
	 * @return integer
	 */
	public function getLastId();

	/**
	 * Fetches all QA entities filtered by pagination
	 * 
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage);

	/**
	 * Fetches all published QA entities filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllPublishedByPage($page, $itemsPerPage);

	/**
	 * Adds a QA pair
	 * 
	 * @param array $input Raw form data
	 * @return boolean
	 */
	public function add(array $input);

	/**
	 * Updates QA pair
	 * 
	 * @param array $input Raw form data
	 * @return boolean
	 */
	public function update(array $input);
}
