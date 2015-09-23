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

interface TaskManagerInterface
{
	/**
	 * Counts amount of slides in given category
	 * 
	 * @param string $categoryId
	 * @return integer
	 */
	public function getSlidesCountByCategoryId($categoryId);
}
