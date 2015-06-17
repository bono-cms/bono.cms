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

use Krystal\Stdlib\VirtualEntity;

final class PostEntity extends VirtualEntity
{
	/**
	 * Tells whether current bag has a cover
	 * 
	 * @return boolean
	 */
	public function hasCover()
	{
		return $this->getCover() !== '';
	}
}
