<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Search\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Krystal\Stdlib\ArrayUtils;

abstract class AbstractSearchProvider extends AbstractMapper
{
	/**
	 * Returns default columns which all tables must contain
	 * 
	 * @return array
	 */
	final protected function getDefaultColumns()
	{
		return array(
			'id',
			'web_page_id',
			'lang_id',
			'title'
		);
	}

	/**
	 * Returns selectable columns
	 * 
	 * @param array $additional
	 * @return array
	 */
	final protected function getWithDefaults(array $additional)
	{
		$columns = $this->getDefaultColumns();

		if (!ArrayUtils::isSequential($additional)) {
			array_push($columns, $additional);
		} else {
			$columns = array_merge($columns, $additional);
		}

		return $columns;
	}
}
