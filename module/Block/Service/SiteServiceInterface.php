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

interface SiteServiceInterface
{
	/**
	 * Returns block name by its associated class name
	 * 
	 * @param string $class
	 * @return string
	 */
	public function getNameByClass($class);

	/**
	 * Renders a block
	 * 
	 * @param string $class Block's class name
	 * @return string
	 */
	public function render($class);
}
