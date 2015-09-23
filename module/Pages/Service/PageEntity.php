<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Pages\Service;

use Krystal\Stdlib\VirtualEntity;

final class PageEntity extends VirtualEntity
{
	/**
	 * The alias to getDefault() 
	 * 
	 * @return boolean
	 */
	public function isDefault()
	{
		return $this->getDefault();
	}
}
