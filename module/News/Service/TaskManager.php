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

use News\Storage\PostMapperInterface;

final class TaskManager implements TaskManagerInterface
{
	/**
	 * Any compliant post mapper
	 * 
	 * @var \News\Storage\PostMapperInterface
	 */
	private $postMapper;

	/**
	 * State initialization
	 * 
	 * @param \News\Storage\PostMapperInterface $postMapper
	 * @return void
	 */
	public function __construct(PostMapperInterface $postMapper)
	{
		$this->postMapper = $postMapper;
	}

	/**
	 * Count amount of posts in provided category id
	 * 
	 * @param string $categoryId
	 * @return integer
	 */
	public function getPostCountByCategoryId($categoryId)
	{
		return $this->postMapper->countAllByCategoryId($categoryId);
	}
}
