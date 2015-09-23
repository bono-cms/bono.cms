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

use Krystal\Stdlib\VirtualEntity;

final class ImageEntity extends VirtualEntity
{
	/**
	 * Tells whether a slide image has a link
	 * 
	 * @return boolean
	 */
	public function hasLink()
	{
		return $this->getLink() !== '';
	}
}
