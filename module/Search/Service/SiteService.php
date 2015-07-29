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

final class SiteService implements SiteServiceInterface
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
	 * Returns element name that must contain query data
	 * 
	 * @return string
	 */
	public function getElementName()
	{
		return 'query';
	}
}
