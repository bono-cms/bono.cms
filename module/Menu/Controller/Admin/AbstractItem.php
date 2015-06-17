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
use Krystal\Tree\AdjacencyList\TreeBuilder;
use Krystal\Tree\AdjacencyList\Render\PhpArray;
use Menu\View\Nestedable;

abstract class AbstractItem extends AbstractController
{
	/**
	 * Returns prepared menu link builder
	 * 
	 * @return \Menu\Service\LinkBuilder
	 */
	final protected function getLinkBuilder()
	{
		$menu = $this->getMenuModule();

		// Menu link builder is just prepared, but now configured yet. I.e it has no data yet
		// So we'll be adding it here. If adding it in Module definition, then that would be extra overhead
		$linkBuilder = $menu->getService('linkBuilder');
		$linkBuilder->loadFromDefiniton($menu->getLinkDefinitions(), $this->moduleManager);

		return $linkBuilder;
	}

	/**
	 * Fetches maximal allowed nested level depth
	 * 
	 * @param string $id Category id
	 * @return integer
	 */
	final protected function getMaxNestedDepth($id)
	{
		return $this->getCategoryManager()->fetchMaxDepthById($id);
	}

	/**
	 * Returns template path
	 * 
	 * @return string
	 */
	final protected function getTemplatePath()
	{
		return 'browser';
	}

	/**
	 * Loads shared plugins
	 * 
	 * @return void
	 */
	final protected function loadSharedPlugins()
	{
		$this->view->getPluginBag()
					->appendScripts(array(
						$this->getWithAssetPath('/nestable/jquery.nestable.js'),
						$this->getWithAssetPath('/plugins/chosen/chosen.jquery.min.js', 'Cms'),
						$this->getWithAssetPath('/admin/module.menu.js')
					))
					->appendStylesheets(array(
						$this->getWithAssetPath('/nestable/jquery.nestable.css'),
						$this->getWithAssetPath('/plugins/chosen/chosen.css', 'Cms'),
						$this->getWithAssetPath('/plugins/chosen/chosen-bootstrap.css', 'Cms')
					));
	}

	/**
	 * Returns prepared tree builder
	 * 
	 * @param string $categoryId
	 * @return \Krystal\Tree\AdjacencyList\TreeBuilder
	 */
	final protected function getTreeBuilder($categoryId)
	{
		return new TreeBuilder($this->getItemManager()->fetchAllByCategoryId($categoryId));
	}

	/**
	 * Returns shared variables
	 * 
	 * @param string $categoryId
	 * @param array $overrides
	 * @param string $active Active item
	 * @return array
	 */
	final protected function getSharedVars($categoryId, array $overrides, $active = null)
	{
		$treeBuilder = $this->getTreeBuilder($categoryId);

		$this->view->getBreadcrumbBag()->add(array(
			array(
				'link' => '#',
				'name' => 'Menu'
			)
		));

		$vars = array(
			'title' => 'Menu',

			// Collect link from services now
			'links'		 =>	$this->getLinkBuilder()->collect(),
			'itemsBlock' => $treeBuilder->render(new Nestedable(), $active),
			'items'		 =>	$treeBuilder->render(new PhpArray('name'), $active),
			'categories' => $this->getCategoryManager()->fetchAll(),
		);

		return array_replace_recursive($vars, $overrides);
	}

	/**
	 * Returns menu module definition
	 * 
	 * @return \Menu\Module
	 */
	final protected function getMenuModule()
	{
		return $this->moduleManager->getModule('Menu');
	}

	/**
	 * Returns category manager
	 * 
	 * @return \Menu\Service\CategoryManager
	 */
	final protected function getCategoryManager()
	{
		return $this->getMenuModule()->getService('categoryManager');
	}

	/**
	 * Returns item manager
	 * 
	 * @return \Menu\Service\ItemManager
	 */
	final protected function getItemManager()
	{
		return $this->getMenuModule()->getService('itemManager');
	}

	/**
	 * Fetches last category id
	 * 
	 * @return integer
	 */
	final protected function getLastCategoryId()
	{
		return $this->getMenuModule()->getService('categoryManager')->fetchLastId();
	}

	/**
	 * Fetches dummy item bag
	 * 
	 * @param string $categoryId
	 * @return \ItemBag
	 */
	final protected function getDummyItemBag($categoryId, $parentId = null)
	{
		return $this->getItemManager()->fetchDummy($categoryId, $parentId);
	}
}
