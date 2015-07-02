<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Shop\Controller;

use Site\Controller\AbstractController;

abstract class AbstractShopController extends AbstractController
{
	/**
	 * Returns Shop module
	 * 
	 * @return \Shop\Module
	 */
	final protected function getShopModule()
	{
		return $this->moduleManager->getModule('Shop');
	}

	/**
	 * Returns configuration entity
	 * 
	 * @return \Krystal\Stdlib\VirtualEntity
	 */
	final protected function getConfig()
	{
		return $this->getModuleService('configManager')->getEntity();
	}
}
