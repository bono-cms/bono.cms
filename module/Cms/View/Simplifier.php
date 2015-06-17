<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\View;

/**
 * Helper class that determines whether some content should be shown or not
 * //@TODO Deprecate this
 */
final class Simplifier
{
	/**
	 * @var boolean
	 */
	private $extendedMode;

	/**
	 * State initialization
	 * 
	 * @param boolean $extendedMode
	 * @return void
	 */
	public function __construct($extendedMode)
	{
		$this->extendedMode = (bool) $extendedMode;
	}

	/**
	 * Whether it's worth to render
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function isWorth(array $data)
	{
		// When we are in extended mode, then we gotta always "worth" it
		if ($this->extendedMode === true) {
			return true;
		} else {
			// If in simple mode, then if we have more than one element in array, we "worth" it, otherwise no
			return count($data) > 1;
		}
	}
}
