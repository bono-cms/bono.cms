<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Pages\Controller;

use Site\Controller\AbstractController;

abstract class AbstractPagesController extends AbstractController
{
	/**
	 * Returns page module
	 * 
	 * @return \Pages\Module
	 */
	final protected function getPagesModule()
	{
		return $this->moduleManager->getModule('Pages');
	}

	/**
	 * Returns configuration entity
	 * 
	 * @return \Krystal\Stdlib\VirtualEntity
	 */
	final protected function getConfig()
	{
		return $this->getPagesModule()->getService('configManager')->getEntity();
	}

	/**
	 * 
	 * @return \Pages\Service\Page
	 */
	final protected function getPageManager()
	{
		return $this->getPagesModule()->getService('pageManager');
	}
}
