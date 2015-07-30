<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Menu\Service;

interface MenuWidgetInterface
{
	/**
	 * Fetches only one empty item bag
	 * 
	 * @return array
	 */
	public function fetchAllAsOneDummy();

	/**
	 * Extracts only ids from item entities
	 * 
	 * @param array $items Item entities collection
	 * @return array
	 */
	public function getIdsFromEntities(array $items);

	/**
	 * Fetches all menu items associated with given web page id
	 * 
	 * @param string $webPageId
	 * @return array
	 */
	public function fetchAllByWebPageId($webPageId);

	/**
	 * Removes an item by its associated web page id
	 * 
	 * @param string $webPageId
	 * @return boolean
	 */
	public function deleteAllByWebPageId($webPageId);

	/**
	 * Updates a collection of widgets
	 * 
	 * @param array $input
	 * @param string $webPageId
	 * @param string $name
	 * @return boolean
	 */
	public function update(array $input, $webPageId, $name);

	/**
	 * Adds an item from a widget
	 * This method usually invoked from within another module services
	 * 
	 * @param array $input
	 * @param string $webPageId
	 * @param string $name
	 * @return boolean
	 */
	public function add(array $input, $webPageId, $name);
}
