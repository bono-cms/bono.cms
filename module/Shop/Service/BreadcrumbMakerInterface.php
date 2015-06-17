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

interface BreadcrumbMakerInterface
{
	/**
	 * Gets breadcrumbs with appends
	 * 
	 * @param string $id Category's id
	 * @param array $appends Additional appends
	 * @return array
	 */
	public function getWithCategoryId($id, array $appends);

	/**
	 * Gets all breadcrumbs by associated id
	 * 
	 * @param string $id Category id
	 * @return array
	 */
	public function getBreadcrumbsById($id);
}
