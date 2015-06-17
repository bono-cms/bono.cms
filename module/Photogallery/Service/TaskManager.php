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

use Photogallery\Storage\PhotoMapperInterface;
use Photogallery\Service\AlbumManager;

/**
 * TaskManager is used inside templates only. Just like as a helper
 */
final class TaskManager implements TaskManagerInterface
{
	/**
	 * Any compliant photo mapper
	 * 
	 * @var \Photogallery\Storage\PhotoMapperInterface
	 */
	private $photoMapper;

	/**
	 * Album manager to grab stuff
	 * 
	 * @var \Photogallery\Service\AlbumManager
	 */
	private $albumManager;
	
	/**
	 * State initialization
	 * 
	 * @param \Photogallery\Storage\PhotoMapperInterface $photoMapper
	 * @param \Photogallery\Service\AlbumManager $albumManager
	 * @return void
	 */
	public function __construct(PhotoMapperInterface $photoMapper, AlbumManager $albumManager)
	{
		$this->photoMapper = $photoMapper;
		$this->albumManager = $albumManager;
	}

	/**
	 * Gets photos count by their album id
	 * 
	 * @param string $albumId
	 * @return integer
	 */
	public function getCountByAlbumId($albumId)
	{
		return $this->photoMapper->countAllByAlbumId($albumId);
	}

	/**
	 * Fetches album bag by its id
	 * 
	 * @param string $albumId
	 * @return array
	 */
	public function fetchByAlbumId($albumId)
	{
		return $this->albumManager->fetchById($albumId);
	}
}
