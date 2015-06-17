<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Block\Service;

interface SiteServiceInterface
{
	/**
	 * Renders a block
	 * 
	 * @param string $class Block's class name
	 * @return string
	 */
	public function render($class);
}
