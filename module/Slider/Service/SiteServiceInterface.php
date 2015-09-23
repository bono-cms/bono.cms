<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Slider\Service;

interface SiteServiceInterface
{
	/**
	 * Checks whether provided category's class has at least one slide image
	 * 
	 * @param string $class
	 * @return boolean
	 */
	public function has($class);

	/**
	 * Returns slide bags from given category class
	 * 
	 * @param string $class Category's class name
	 * @return array
	 */
	public function getAll($class);
}
