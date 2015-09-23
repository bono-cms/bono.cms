<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Photogallery\Service;

interface SiteServiceInterface
{
	/**
	 * Fetches all photo entities by associated album id
	 * 
	 * @param string $id Album id
	 * @return array
	 */
	public function getAllByAlbumId($id);
}
