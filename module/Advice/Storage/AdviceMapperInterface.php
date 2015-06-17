<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Advice\Storage;

interface AdviceMapperInterface
{
	/**
	 * Updates published state by associated id
	 * 
	 * @param string $id
	 * @param string $published
	 * @return boolean
	 */
	public function updatePublishedById($id, $published);

	/**
	 * Fetches a random advice
	 * 
	 * @return array
	 */
	public function fetchRandom();

	/**
	 * Fetches all advices filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Items per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage);

	/**
	 * Fetches all published advice filtered by pagination
	 * 
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Items per page count
	 * @return array
	 */
	public function fetchAllPublishedByPage($page, $itemsPerPage);

	/**
	 * Fetches all advices
	 * 
	 * @return array
	 */
	public function fetchAll();

	/**
	 * Fetches an advice by its associated id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id);

	/**
	 * Deletes an advice by its associated id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function deleteById($id);

	/**
	 * Inserts an advice
	 * 
	 * @param string $title
	 * @param string $content
	 * @param string $published
	 * @return boolean
	 */
	public function insert($title, $content, $published);

	/**
	 * Updates an advice
	 * 
	 * @param string $id
	 * @param string $title
	 * @param string $content
	 * @param string $published
	 * @return boolean Depending on success
	 */
	public function update($id, $title, $content, $published);
}
