<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Announcement\Service;

interface SiteServiceInterface
{
	/**
	 * Gets all announce entities associated with provided category class
	 * 
	 * @param string $class Category class
	 * @return array
	 */
	public function getAllByClass($class);
}
