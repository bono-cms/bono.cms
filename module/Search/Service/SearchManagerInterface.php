<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Search\Service;

interface SearchManagerInterface
{
	/**
	 * Overrides maximal description length
	 * 
	 * @param integer $maxDescriptionLength
	 * @return void
	 */
	public function setMaxDescriptionLength($maxDescriptionLength);

	/**
	 * Queries all attached mappers
	 * 
	 * @param string $data Query data
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function findByKeyword($keyword, $page, $itemsPerPage);

	/**
	 * Returns prepared paginator's instance
	 * 
	 * @return \Krystal\Paginate\Paginator
	 */
	public function getPaginator();
}
