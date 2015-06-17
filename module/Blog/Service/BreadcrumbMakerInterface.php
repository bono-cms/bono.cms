<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Blog\Service;

interface BreadcrumbMakerInterface
{
	/**
	 * Gets category breadcrumbs with appends
	 * 
	 * @param string $id Category's id
	 * @param array $appends
	 * @return array
	 */
	public function getWithCategoryBreadcrumbsById($id, array $appends);

	/**
	 * Returns breadcrumbs for provided category id
	 * 
	 * @param string $id Category's id
	 * @return array
	 */
	public function getCategoryBreadcrumbsById($id);
}
