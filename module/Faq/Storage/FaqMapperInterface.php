<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Faq\Storage;

interface FaqMapperInterface
{
	/**
	 * Fetches question name by its associated id
	 * 
	 * @param string $id Faq id
	 * @return string
	 */
	public function fetchQuestionById($id);

	/**
	 * Update published state by its associated faq id
	 * 
	 * @param integer $id Faq id
	 * @param string $published Either 0 or 1
	 * @return boolean
	 */
	public function updatePublishedById($id, $published);

	/**
	 * Update an order by its associated faq id
	 * 
	 * @param string $id Faq id
	 * @param integer $order New sort order
	 * @return boolean
	 */
	public function updateOrderById($id, $order);

	/**
	 * Fetches all faqs filtered by pagination
	 * 
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage);

	/**
	 * Fetches all published faqs filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllPublishedByPage($page, $itemsPerPage);

	/**
	 * Fetches all published faqs
	 * 
	 * @return array
	 */
	public function fetchAllPublished();

	/**
	 * Inserts a faq
	 * 
	 * @param array $data Faq data
	 * @return boolean
	 */
	public function insert(array $data);

	/**
	 * Updates a fqq
	 * 
	 * @param array $data Faq data
	 * @return boolean
	 */
	public function update(array $data);

	/**
	 * Deletes a faq by its associated id
	 * 
	 * @param string $id Faq id
	 * @return boolean
	 */
	public function deleteById($id);

	/**
	 * Fetches faq data by its associated id
	 * 
	 * @param string $id Faq id
	 * @return array
	 */
	public function fetchById($id);
}
