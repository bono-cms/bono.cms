<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Search\Storage;

use Search\Storage\MySQL\AbstractSearchProvider;

interface SearchMapperInterface
{
	/**
	 * Appends a searchable mapper
	 * 
	 * @param \Search\Storage\MySQL\AbstractSearchProvider
	 * @return void
	 */
	public function append(AbstractSearchProvider $mapper);

	/**
	 * Queries by a keyword in all attached mappers
	 * 
	 * @param string $keyword
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function queryAll($keyword, $page, $itemsPerPage);
}
