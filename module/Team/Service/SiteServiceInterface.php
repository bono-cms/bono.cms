<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Team\Service;

interface SiteServiceInterface
{
	/**
	 * Returns all member entities
	 * 
	 * @return array
	 */
	public function getAll();
}
