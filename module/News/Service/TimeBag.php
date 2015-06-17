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

final class TimeBag implements TimeBagInterface
{
	/**
	 * Current timestamp
	 * 
	 * @var integer
	 */
	private $timestamp;

	/**
	 * Time format in category's list
	 * 
	 * @var string
	 */
	private $listFormat;

	/**
	 * Time format in post's page
	 * 
	 * @var string
	 */
	private $postFormat;

	/**
	 * Time format in announce block
	 * 
	 * @var string
	 */
	private $announceFormat;

	/**
	 * Time format in administration panel
	 * 
	 * @var string
	 */
	private $panelFormat;

	/**
	 * State initialization
	 * 
	 * @param string $listFormat Time format in list
	 * @param string $postFormat Time format in post
	 * @param string $announceFormat Time format in announce block
	 * @param string $panelFormat Time format in administration panel
	 * @return void
	 */
	public function __construct($listFormat, $postFormat, $announceFormat, $panelFormat)
	{
		$this->listFormat = $listFormat;
		$this->postFormat = $postFormat;
		$this->announceFormat = $announceFormat;
		$this->panelFormat = $panelFormat;
	}

	/**
	 * Returns formatted time according to proved format
	 * 
	 * @param string $format Any compliant time format
	 * @return string
	 */
	private function getFormattedTime($format)
	{
		return date($format, $this->timestamp);
	}

	/**
	 * Sets current timestamp
	 * 
	 * @param integer $timestamp
	 * @return void
	 */
	public function setTimestamp($timestamp)
	{
		$this->timestamp = (int) $timestamp;
	}

	/**
	 * Returns formatted time for category's page
	 * 
	 * @return string
	 */
	public function getListFormat()
	{
		return $this->getFormattedTime($this->listFormat);
	}

	/**
	 * Returns formatted time for post's page
	 * 
	 * @return string
	 */
	public function getPostFormat()
	{
		return $this->getFormattedTime($this->postFormat);
	}

	/**
	 * Returns formatted time for announce block
	 * 
	 * @return string
	 */
	public function getAnnounceFormat()
	{
		return $this->getFormattedTime($this->announceFormat);
	}

	/**
	 * Returns formatted time for administration panel
	 * 
	 * @return string
	 */
	public function getPanelFormat()
	{
		return $this->getFormattedTime($this->panelFormat);
	}
}
