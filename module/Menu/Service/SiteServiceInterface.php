<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Menu\Service;

interface SiteServiceInterface
{
	/**
	 * Defines an id of home web page id
	 * 
	 * @param string $homeWebPageId
	 * @return void
	 */
	public function setHomeWebPageId($homeWebPageId);

	/**
	 * Register menu blocks
	 * 
	 * @param array $collection
	 * @return void
	 */
	public function register(array $collection);

	/**
	 * Fetches menu category's name by its associated class name
	 * 
	 * @param string $class Menu category's class name
	 * @return string
	 */
	public function getCategoryNameByClass($class);

	/**
	 * Renders category block associated with provided web page id
	 * 
	 * @param string $webPageId
	 * @return string The block
	 */
	public function renderByAssocWebPageId($webPageId);

	/**
	 * Renders menu block by category's class name
	 * 
	 * @param string $class
	 * @param string $webPageId Used to add active class in li elements in template views
	 * @throws \RuntimeException When attempting to read non-existing class
	 * @return string
	 */
	public function renderByClass($class, $webPageId = null);
}
