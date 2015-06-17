<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Slider\Service;

use Slider\Storage\ImageMapperInterface;

final class TaskManager implements TaskManagerInterface
{
	/**
	 * Any compliant image mapper
	 * 
	 * @var \Slider\Storage\ImageMapperInterface
	 */
	private $imageMapper;

	/**
	 * State initialization
	 * 
	 * @param \Slider\Storage\ImageMapperInterface $imageMapper
	 * @return void
	 */
	public function __construct(ImageMapperInterface $imageMapper)
	{
		$this->imageMapper = $imageMapper;
	}

	/**
	 * Counts amount of slides in given category
	 * 
	 * @param string $categoryId
	 * @return integer
	 */
	public function getSlidesCountByCategoryId($categoryId)
	{
		return $this->imageMapper->countAllByCategoryId($categoryId);
	}
}
