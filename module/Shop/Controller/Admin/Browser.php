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

use Shop\View\Form\FilterForm;

final class Browser extends AbstractBrowser
{
	/**
	 * Shows a table
	 * 
	 * @param integer $page Current page
	 * @return string
	 */
	public function indexAction($page = 1)
	{
		// Filtering form
		$form = new FilterForm($this->request->getQuery());

		if ($this->request->hasQuery('filter')) {

			$data = $this->request->getQuery('filter');
			$products = $this->getProductManager()->filter($data, 1, $this->getSharedPerPageCount());

		} else {

			// By default we show latest
			$products = $this->getProductManager()->fetchAllByPage($page, $this->getSharedPerPageCount());
		}

		$paginator = $this->getProductManager()->getPaginator();
		$paginator->setUrl('/admin/module/shop/page/%s');

		$this->loadSharedPlugins();

		return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(

			'paginator' => $paginator,
			'products' => $products,
			
			'form' => $form
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
		// Grab request data
		$prices = $this->request->getPost('price');
		$published = $this->request->getPost('published');
		$seo = $this->request->getPost('seo');

		// Grab a manager
		$productManager = $this->getProductManager();

		$this->flashMessenger->set('success', 'Settings have been updated successfully');

		$productManager->updatePrices($prices);
		$productManager->updatePublished($published);
		$productManager->updateSeo($seo);

		return '1';
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
				$this->flashMessenger->set('success', 'Selected product has been removed successfully');
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

		$this->flashMessenger->set($flashKey, $flashMessage);
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
				$this->flashMessenger->set('success', 'The category has been removed successfully');
				return '1';
			}
		}
	}
}
