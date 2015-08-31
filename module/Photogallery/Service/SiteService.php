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

final class SiteService implements SiteServiceInterface
{
	/**
	 * Photo manager service
	 * 
	 * @var \Photogallery\Service\PhotoManagerInterface
	 */
	private $photoManager;

	/**
	 * State initialization
	 * 
	 * @param \Photogallery\Service\PhotoManagerInterface $photoManager
	 * @return void
	 */
	public function __construct(PhotoManagerInterface $photoManager)
	{
		$this->photoManager = $photoManager;
	}

	/**
	 * Fetches all photo entities by associated album id
	 * 
	 * @param string $id Album id
	 * @return array
	 */
	public function getAllByAlbumId($id)
	{
		return $this->photoManager->fetchAllPublishedByAlbumId($id);
	}
}
