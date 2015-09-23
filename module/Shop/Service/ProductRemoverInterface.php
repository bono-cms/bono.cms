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

interface ProductRemoverInterface
{
	/**
	 * Completely removes a product by its associated id
	 * 
	 * @param string $id Product's id
	 * @return boolean
	 */
	public function removeAllById($id);

	/**
	 * Removes all associated product ids with given category id
	 * 
	 * @param string $id Category's id
	 * @return array
	 */
	public function removeAllProductsByCategoryId($id);	
}
