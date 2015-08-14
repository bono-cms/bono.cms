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
	 * Target keyword
	 * 
	 * @var string
	 */
	private $keyword;

	/**
	 * Defines search keyword
	 * 
	 * @param string $keyword
	 * @return void
	 */
	public function setKeyword($keyword)
	{
		$this->keyword = $keyword;
	}

	/**
	 * Returns search keyword
	 * 
	 * @return string
	 */
	public function getKeyword()
	{
		return $this->keyword;
	}

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
