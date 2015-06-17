<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Shop\Controller\Admin;

use Cms\Controller\Admin\AbstractController;
use Krystal\Tree\AdjacencyList\TreeBuilder;
use Krystal\Tree\AdjacencyList\Render\PhpArray;

abstract class AbstractBrowser extends AbstractController
{
	/**
	 * Loads shared plugins
	 * 
	 * @return void
	 */
	final protected function loadSharedPlugins()
	{
		$this->view->getPluginBag()
				   ->load('datepicker')
				   ->load('lightbox')
				   ->appendScript($this->getWithAssetPath('/admin/browser.js'));
	}

	/**
	 * Returns shared variables
	 * 
	 * @param array $vars
	 * @return array
	 */
	final protected function getSharedVars(array $overrides)
	{
		$shop = $this->moduleManager->getModule('Shop');
		$treeBuilder = new TreeBuilder($shop->getService('categoryManager')->fetchAll());

		$this->view->getBreadcrumbBag()->add(array(
			array(
				'name' => 'Shop',
				'link' => '#'
			)
		));
		
		$vars = array(
			'currency' => $shop->getService('configManager')->getEntity()->getCurrency(),
			'orders' => $shop->getService('orderManager')->fetchLatest(4),
			'title' => 'Shop',
			'taskManager' => $shop->getService('taskManager'),
			'categories' => $treeBuilder->render(new PhpArray('title'))
		);

		return array_replace_recursive($vars, $overrides);
	}

	/**
	 * Returns prepared product manager
	 * 
	 * @return \Shop\Service\ProductManager
	 */
	final protected function getProductManager()
	{
		return $this->moduleManager->getModule('Shop')->getService('productManager');
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
}
