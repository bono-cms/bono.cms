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

interface TaskManagerInterface
{
	/**
	 * Gets photos count by their album id
	 * 
	 * @param string $albumId
	 * @return integer
	 */
	public function getCountByAlbumId($albumId);

	/**
	 * Fetches album bag by its id
	 * 
	 * @param string $albumId
	 * @return array
	 */
	public function fetchByAlbumId($albumId);
}
