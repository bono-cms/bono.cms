<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Service;

interface ModeInterface
{
	/**
	 * Checks whether provided id belongs to current active mode
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function isCurrent($id);

	/**
	 * Returns available modes
	 * 
	 * @return array
	 */
	public function getModes();

	/**
	 * Prepares mode manager
	 * 
	 * @return void
	 */
	public function prepare();

	/**
	 * Fetches mode value from a storage
	 * 
	 * @return string
	 */
	public function fetch();

	/**
	 * Checks whether mode is advanced
	 * 
	 * @return boolean
	 */
	public function isAdvanced();

	/**
	 * Checks whether mode is simple
	 * 
	 * @return boolean
	 */
	public function isSimple();

	/**
	 * Activates advanced mode
	 * 
	 * @param string $value
	 * @return void
	 */
	public function setAdvanced();

	/**
	 * Activates simple mode
	 * 
	 * @return void
	 */
	public function setSimple();
}
