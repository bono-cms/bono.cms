<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Contract;

interface WebPageMapperAwareInterface
{
	/**
	 * Updates web page id by its associated id
	 * 
	 * @param integer $id
	 * @param integer $webPageId
	 * @return boolean
	 */
	public function updateWebPageIdById($id, $webPageId);

	/**
	 * Fetches web page id by target id
	 * 
	 * @param integer $id
	 * @return integer
	 */
	public function fetchWebPageIdById($id);

	/**
	 * Fetches title by web page id
	 * 
	 * @param string $webPageId
	 * @return string
	 */
	public function fetchTitleByWebPageId($webPageId);
}
