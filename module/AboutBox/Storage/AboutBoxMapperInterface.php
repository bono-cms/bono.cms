<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace AboutBox\Storage;

interface AboutBoxMapperInterface
{
	/**
	 * Fetches box's content
	 * 
	 * @return string
	 */
	public function fetch();

	/**
	 * Inserts box's text
	 * 
	 * @param string $content
	 * @return boolean
	 */
	public function insert($content);

	/**
	 * Updates box's content
	 * 
	 * @param string $content
	 * @return boolean
	 */
	public function update($content);

	/**
	 * Whether content exists associated with initial language id
	 * 
	 * @return boolean
	 */
	public function exists();	
}
