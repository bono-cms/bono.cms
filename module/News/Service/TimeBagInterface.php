<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace News\Service;

interface TimeBagInterface
{
	/**
	 * Sets current timestamp
	 * 
	 * @param integer $timestamp
	 * @return void
	 */
	public function setTimestamp($timestamp);

	/**
	 * Returns formatted time for category's page
	 * 
	 * @return string
	 */
	public function getListFormat();

	/**
	 * Returns formatted time for post's page
	 * 
	 * @return string
	 */
	public function getPostFormat();

	/**
	 * Returns formatted time for announce block
	 * 
	 * @return string
	 */
	public function getAnnounceFormat();

	/**
	 * Returns formatted time for administration panel
	 * 
	 * @return string
	 */
	public function getPanelFormat();
}
