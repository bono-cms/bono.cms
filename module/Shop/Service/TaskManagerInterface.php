<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Shop\Service;

interface TaskManagerInterface
{
	/**
	 * Fetches category's entity by its associated id
	 * 
	 * @param string $id Category id
	 * @return \Krystal\Stdlib\VirtualEntity
	 */
	public function fetchByCategoryId($id);

	/**
	 * Counts amount of products associated with provided category id
	 * 
	 * @param string $id Category id
	 * @return integer
	 */
	public function getProductCountByCategoryId($id);
}
