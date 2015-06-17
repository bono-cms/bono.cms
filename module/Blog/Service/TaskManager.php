<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Blog\Service;

use Blog\Storage\PostMapperInterface;

final class TaskManager implements TaskManagerInterface
{
	/**
	 * Any compliant post mapper
	 * 
	 * @var \Blog\Storage\PostMapperInterface
	 */
	private $postMapper;

	/**
	 * State initialization
	 * 
	 * @param \Blog\Storage\PostMapperInterface $postMapper
	 * @return void
	 */
	public function __construct(PostMapperInterface $postMapper)
	{
		$this->postMapper = $postMapper;
	}

	/**
	 * Count amount of posts associated with given category id
	 * 
	 * @param string $id
	 * @return integer
	 */
	public function getCountByCategory($id)
	{
		return $this->postMapper->countAllByCategoryId($id);
	}
}
