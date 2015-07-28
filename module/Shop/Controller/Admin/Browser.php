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
use Krystal\Db\Filter\QueryContainer;

final class Browser extends AbstractController
{
	/**
	 * Applies the filter
	 * 
	 * @return string
	 */
	public function filterAction()
	{
		$records = $this->getFilter($this->getProductManager());

		if ($records !== false) {
			$this->loadSharedPlugins();

			return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(
				'paginator' => $this->getProductManager()->getPaginator(),
				'products' => $records
			)));

		} else {

			// None selected, so no need to apply a filter
			return $this->indexAction();
		}
	}

	/**
	 * Shows a table
	 * 
	 * @param integer $page Current page
	 * @return string
	 */
	public function indexAction($page = 1)
	{
		$products = $this->getProductManager()->fetchAllByPage($page, $this->getSharedPerPageCount());

		$paginator = $this->getProductManager()->getPaginator();
		$paginator->setUrl('/admin/module/shop/page/%s');

		$this->loadSharedPlugins();

		return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(

			'paginator' => $paginator,
			'products' => $products,
		)));
	}

	/**
	 * Displays products by category id
	 * 
	 * @param string $id Category id
	 * @param integer $page
	 * @return string
	 */
	public function categoryAction($id, $page = 1)
	{
		$this->loadSharedPlugins();

		$paginator = $this->getProductManager()->getPaginator();
		$paginator->setUrl('/admin/module/shop/category/'.$id. '/page/%s');

		return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(
			'currentCategoryId' => $id,
			'paginator' => $paginator,
			'products' => $this->getProductManager()->fetchAllByCategoryIdAndPage($id, $page, $this->getSharedPerPageCount()),
		)));
	}

	/**
	 * Save updates from the table
	 * 
	 * @return string
	 */
	public function saveAction()
	{
		if ($this->request->hasPost('price', 'published', 'seo')) {

			// Grab request data
			$prices = $this->request->getPost('price');
			$published = $this->request->getPost('published');
			$seo = $this->request->getPost('seo');

			// Grab a manager
			$productManager = $this->getProductManager();

			$this->flashBag->set('success', 'Settings have been updated successfully');

			$productManager->updatePrices($prices);
			$productManager->updatePublished($published);
			$productManager->updateSeo($seo);

			return '1';
		}
	}

	/**
	 * Deletes a product
	 * 
	 * @return string
	 */
	public function deleteAction()
	{
		if ($this->request->hasPost('id')) {
			$id = $this->request->getPost('id');

			if ($this->getProductManager()->removeById($id)) {

				$this->flashBag->set('success', 'Selected product has been removed successfully');
				return '1';
			}
		}
	}

	/**
	 * Deletes selected products
	 * 
	 * @return string
	 */
	public function deleteSelectedAction()
	{
		if ($this->request->hasPost('toDelete')) {

			$ids = array_keys($this->request->getPost('toDelete'));
			$this->getProductManager()->removeByIds($ids);

			$flashKey = 'success';
			$flashMessage = 'Selected products have been removed successfully';

		} else {

			$flashKey = 'warning';
			$flashMessage = 'You should select at least one product to remove';
		}

		$this->flashBag->set($flashKey, $flashMessage);
		return '1';
	}

	/**
	 * Deletes a category by its associated id
	 * 
	 * @return string
	 */
	public function deleteCategoryAction()
	{
		if ($this->request->hasPost('id')) {

			$id = $this->request->getPost('id');

			$categoryManager = $this->moduleManager->getModule('Shop')->getService('categoryManager');

			if ($categoryManager->removeById($id)) {
				$this->flashBag->set('success', 'The category has been removed successfully');
				return '1';
			}
		}
	}
	
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
		$treeBuilder = new TreeBuilder($this->getModuleService('categoryManager')->fetchAll());
		$this->view->getBreadcrumbBag()->add(array(
			array(
				'name' => 'Shop',
				'link' => '#'
			)
		));

		$vars = array(
			'title' => 'Shop',
			'taskManager' => $this->getModuleService('taskManager'),
			'categories' => $treeBuilder->render(new PhpArray('title')),
			'filter' => new QueryContainer($this->request->getQuery('filter'))
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
		return $this->getModuleService('productManager');
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
