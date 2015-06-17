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

interface PostRemoverInterface
{
	/**
	 * Removes all data by post's associated id
	 * 
	 * @param string $id Post's id
	 * @return boolean
	 */
	public function removeAllById($id);
}
