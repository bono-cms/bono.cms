<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Announcement\Service;

use Announcement\Storage\CategoryMapperInterface;

final class SiteService implements SiteServiceInterface
{
	/**
	 * Announce manager
	 * 
	 * @var \Announcement\Service\AnnounceManagerInterface
	 */
	private $announceManager;

	/**
	 * Any compliant category mapper
	 * 
	 * @var \Announcement\Storage\CategoryMapperInterface
	 */
	private $categoryMapper;

	/**
	 * State initialization
	 * 
	 * @param \Announcement\Service\AnnounceManagerInterface $announceManager
	 * @param \Announcement\Storage\CategoryMapperInterface $categoryMapper
	 * @return void
	 */
	public function __construct(AnnounceManagerInterface $announceManager, CategoryMapperInterface $categoryMapper)
	{
		$this->announceManager = $announceManager;
		$this->categoryMapper = $categoryMapper;
	}

	/**
	 * Gets all announce entities associated with provided category class
	 * 
	 * @param string $class Category class
	 * @return array
	 */
	public function getAllByClass($class)
	{
		$id = $this->categoryMapper->fetchIdByClass($class);

		// Do the following query in case right id supplied
		if ($id) {
			return $this->announceManager->fetchAllPublishedByCategoryId($id);
		} else {
			return array();
		}
	}
}
