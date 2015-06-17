<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace News\Service;

interface BreadcrumbMakerInterface
{
	/**
	 * Returns category breadcrumbs with additional appends
	 * 
	 * @param string $id Category's id
	 * @param array $appends
	 * @return array
	 */
	public function getWithCategoryBreadcrumbs($id, array $appends);
}
