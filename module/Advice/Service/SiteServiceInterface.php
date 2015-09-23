<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Advice\Service;

interface SiteServiceInterface
{
	/**
	 * Returns random advice entity
	 * 
	 * @return \Krystal\Stdlib\VirtualEntity
	 */
	public function getRandom();

	/**
	 * Finds an advice by its associated id and returns its entity
	 * 
	 * @param string $id
	 * @return \Krystal\Stdlib\VirtualEntity|boolean
	 */
	public function getById($id);
}
