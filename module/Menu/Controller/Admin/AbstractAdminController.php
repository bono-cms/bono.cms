<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Menu\Controller\Admin;

use Cms\Controller\Admin\AbstractController;

abstract class AbstractAdminController extends AbstractController
{
	/**
	 * Returns MenuWidget service
	 * 
	 * @return \Menu\Service\MenuWidget
	 */
	final protected function getMenuWidget()
	{
		return $this->getModuleService('menuWidget');
	}

	/**
	 * Returns category manager
	 * 
	 * @return \Menu\Service\CategoryManager
	 */
	final protected function getCategoryManager()
	{
		return $this->getModuleService('categoryManager');
	}

	/**
	 * Returns item manager
	 * 
	 * @return \Menu\Service\ItemManager
	 */
	final protected function getItemManager()
	{
		return $this->getModuleService('itemManager');
	}

	/**
	 * Returns prepared menu link builder
	 * 
	 * @return \Menu\Service\LinkBuilder
	 */
	final protected function getLinkBuilder()
	{
		$menu = $this->moduleManager->getModule('Menu');

		// Menu link builder is just prepared, but now configured yet. I.e it has no data yet
		// So we'll be adding it here. If adding it in Module definition, then that would be extra overhead
		$linkBuilder = $menu->getService('linkBuilder');
		$linkBuilder->loadFromDefiniton($menu->getLinkDefinitions(), $this->moduleManager);

		return $linkBuilder;
	}
}
