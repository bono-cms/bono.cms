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

final class SearchSiteService
{
	/**
	 * Returns URL where requests should be send to
	 * 
	 * @return string
	 */
	public function getUrl()
	{
		return '/search/';
	}

	/**
	 * Returns element's name which must contain query's data
	 * 
	 * @return string
	 */
	public function getElementName()
	{
		return 'query';
	}
}
