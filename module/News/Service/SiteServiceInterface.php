<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace News\Service;

interface SiteServiceInterface
{
	/**
	 * Returns random posts
	 * 
	 * @param integer $amount
	 * @param string $categoryId Optionally can be filtered by category id
	 * @return array
	 */
	public function getRandom($amount, $categoryId = null);
}
