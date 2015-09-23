<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Shop\Service;

use Krystal\Stdlib\VirtualEntity;

final class CategoryEntity extends VirtualEntity
{
	/**
	 * Checks if a category has a cover
	 * 
	 * @return boolean
	 */
	public function hasCover()
	{
		return $this->getCover() !== '';
	}
}
