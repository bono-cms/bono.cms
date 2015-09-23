<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Banner\Service;

final class SiteService implements SiteServiceInterface
{
	/**
	 * Banner manager service
	 * 
	 * @var \Banner\Service\BannerManagerInterface
	 */
	private $bannerManager;

	/**
	 * State initialization
	 * 
	 * @param \Banner\Service\BannerManagerInterface $bannerManager
	 * @return void
	 */
	public function __construct(BannerManagerInterface $bannerManager)
	{
		$this->bannerManager = $bannerManager;
	}

	/**
	 * Returns random banner's entity
	 * 
	 * @return \Krystal\Stdlib\VirtualEntity
	 */
	public function getRandom()
	{
		return $this->bannerManager->fetchRandom();
	}

	/**
	 * Returns banner's entity by its associated id, or false on failure
	 * 
	 * @param string $id Banner id
	 * @return \Krystal\Stdlib\VirtualEntity|boolean
	 */
	public function getById($id)
	{
		return $this->bannerManager->fetchById($id);
	}
}
