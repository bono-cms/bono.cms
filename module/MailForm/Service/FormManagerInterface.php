<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace MailForm\Service;

interface FormManagerInterface
{
	/**
	 * Updates SEO states by associated form ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updateSeo(array $pair);

	/**
	 * Sends a form
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function send(array $input);

	/**
	 * Fetches web page title by its associated id
	 * 
	 * @param string $webPageId
	 * @return string Web page title
	 */
	public function fetchTitleByWebPageId($webPageId);

	/**
	 * Fetches dummy form entity
	 * 
	 * @return \Krystal\Stdlib\VirtualEntity
	 */
	public function fetchDummy();

	/**
	 * Returns last id
	 * 
	 * @return integer
	 */
	public function getLastId();

	/**
	 * Delete by collection of ids
	 * 
	 * @param array $ids
	 * @return boolean
	 */
	public function deleteByIds(array $ids);

	/**
	 * Deletes a form by its associated id
	 * 
	 * @param string $id Form id
	 * @return boolean
	 */
	public function deleteById($id);

	/**
	 * Fetches all form entities
	 * 
	 * @return array
	 */
	public function fetchAll();

	/**
	 * Fetches form entity by its associated id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id);

	/**
	 * Adds a form
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function add(array $input);

	/**
	 * Updates a form
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input);
	
}
