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

interface CategoryRemoverInterface
{
	/**
	 * Completely removes a category by its associated id
	 * 
	 * @param string $id Category's id
	 * @return boolean
	 */
	public function removeAllById($id);
}
