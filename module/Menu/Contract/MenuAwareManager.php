<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Menu\Contract;

/**
 * All services that deal with web pages, need to implement this interface
 */
interface MenuAwareManager
{
	/**
	 * This is only used in browser, when we need to show slug=> title pair is select-box
	 * 
	 * @param integer $webPageId
	 * @return string
	 */
	public function fetchTitleByWebPageId($webPageId);
}
