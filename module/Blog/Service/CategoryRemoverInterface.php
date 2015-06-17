<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Blog\Service;

interface CategoryRemoverInterface
{
	/**
	 * Removes a category and its associated posts
	 * 
	 * @param string $id Category's id
	 * @return boolean
	 */
	public function removeAllById($id);
}
