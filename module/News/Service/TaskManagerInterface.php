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

interface TaskManagerInterface
{
	/**
	 * Count amount of posts in provided category id
	 * 
	 * @param string $categoryId
	 * @return integer
	 */
	public function getPostCountByCategoryId($categoryId);
}
