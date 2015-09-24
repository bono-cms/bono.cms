<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Block\Service;

use Block\Storage\BlockMapperInterface;

final class SiteService implements SiteServiceInterface
{
	/**
	 * Any compliant block mapper
	 * 
	 * @var \Block\Storage\BlockMapperInterface
	 */
	private $blockMapper;

	/**
	 * State initialization
	 * 
	 * @param \Block\Storage\BlockMapperInterface $blockMapper
	 * @return void
	 */
	public function __construct(BlockMapperInterface $blockMapper)
	{
		$this->blockMapper = $blockMapper;
	}

	/**
	 * Returns block name by its associated class name
	 * 
	 * @param string $class
	 * @return string
	 */
	public function getNameByClass($class)
	{
		return $this->blockMapper->fetchNameByClass($class);
	}

	/**
	 * Renders a block
	 * 
	 * @param string $class Block's class name
	 * @return string
	 */
	public function render($class)
	{
		return $this->blockMapper->fetchContentByClass($class);
	}
}
