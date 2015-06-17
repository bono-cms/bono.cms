<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Shop\Service;

use Krystal\Form\Providers\PerPageCount;

final class PerPageCountProvider extends PerPageCount
{
	/**
	 * State initialization
	 * 
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct('cat_pc', 5);
	}
}
