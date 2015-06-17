<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace AboutBox\Service;

interface AboutBoxManagerInterface
{
	/**
	 * Fetches box's content
	 * 
	 * @return string
	 */
	public function fetch();

	/**
	 * Updates box's data
	 * 
	 * @param string $content New content
	 * @return boolean
	 */
	public function update($content);
}
