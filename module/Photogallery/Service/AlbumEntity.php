<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Photogallery\Service;

use Krystal\Stdlib\VirtualEntity;

final class AlbumEntity extends VirtualEntity
{
	/**
	 * Checks if an album has a cover
	 * 
	 * @return boolean
	 */
	public function hasCover()
	{
		return $this->getCover() !== '';
	}
}
