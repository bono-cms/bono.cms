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

use Krystal\Form\Providers\DataSorter;

final class CategorySortProvider extends DataSorter
{
	const SORT_ORDER = 'order';
	const SORT_TITLE = 'title';
	const SORT_PRICE_DESC = 'price_desc';
	const SORT_PRICE_ASC = 'price_asc';
	const SORT_TIMESTAMP_DESC = 'timestamp_desc';
	const SORT_TIMESTAMP_ASC = 'timestamp_asc';

	/**
	 * State initialization
	 * 
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct('cat_sort', self::SORT_ORDER, array(
			self::SORT_ORDER => 'By position',
			self::SORT_TITLE => 'By title',
			self::SORT_PRICE_ASC => 'By price - from lower to higher',
			self::SORT_PRICE_DESC => 'By price - from higher to lower',
			self::SORT_TIMESTAMP_DESC => 'By date added - from newest to oldest',
			self::SORT_TIMESTAMP_ASC => 'By date added - from oldest to newest'
		));
	}
}
