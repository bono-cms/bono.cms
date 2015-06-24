<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Faq\Service;

use Krystal\Stdlib\VirtualEntity;

interface FaqManagerInterface
{
	/**
	 * Returns FAQ breadcrumbs for view layer
	 * 
	 * @param \Krystal\Stdlib\VirtualEntity $faq
	 * @return array
	 */
	public function getBreadcrumbs(VirtualEntity $faq);

	/**
	 * Fetches dummy FAQ entity
	 * 
	 * @return \Krystal\Stdlib\VirtualEntity
	 */
	public function fetchDummy();

	/**
	 * Updates published states by their associated ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updatePublished(array $pair);

	/**
	 * Updates orders by their associated ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updateOrders(array $pair);

	/**
	 * Fetches all FAQs filtered by pagination
	 * 
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @param boolean $published Whether to fetch only published ones
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage, $published);

	/**
	 * Fetches all published faq bags
	 * 
	 * @return array
	 */
	public function fetchAllPublished();

	/**
	 * Adds a FAQ
	 * 
	 * @param array $input Raw form data
	 * @return boolean
	 */
	public function add(array $input);

	/**
	 * Updates a FAQ
	 * 
	 * @param array $input Raw form data
	 * @return boolean
	 */
	public function update(array $input);

	/**
	 * Returns last faq id
	 * 
	 * @return integer
	 */
	public function getLastId();

	/**
	 * Returns prepared paginator's instance
	 * 
	 * @return \Krystal\Paginate\Pagination
	 */
	public function getPaginator();

	/**
	 * Fetches a faq bag by its associated id
	 * 
	 * @param string $id Faq id
	 * @return boolean|\Krystal\Stdlib\VirtualBag
	 */
	public function fetchById($id);

	/**
	 * Deletes a faq by its associated id
	 * 
	 * @param string $id Faq id
	 * @return boolean
	 */
	public function deleteById($id);

	/**
	 * Deletes faqs by their associated ids
	 * 
	 * @param array $ids Array of ids
	 * @return boolean
	 */
	public function deleteByIds(array $ids);
}
