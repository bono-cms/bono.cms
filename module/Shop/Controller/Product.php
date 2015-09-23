<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Shop\Controller;

final class Product extends AbstractShopController
{
	/**
	 * Fetches a product by its associated id
	 * 
	 * @param string $id Product id
	 * @return string
	 */
	public function indexAction($id)
	{
		// Grab a service
		$productManager = $this->getModuleService('productManager');
		$product = $productManager->fetchById($id);

		// If $product isn't false, then its an entity
		if ($product !== false) {

			// Load required plugins for view
			$this->loadPlugins($productManager->getBreadcrumbs($product));

			$response = $this->view->render('shop-product', array(
				// Image bags of current product
				'images' => $productManager->fetchAllPublishedImagesById($id),
				'page' => $product,
				'product' => $product,
			));

			// After product is viewed, it's time to increment its view count
			$productManager->incrementViewCount($id);

			return $response;

		} else {

			// Returning false will trigger 404 error automatically
			return false;
		}
	}

	/**
	 * Loads required plugins
	 * 
	 * @param array $breadcrumbs
	 * @return void
	 */
	private function loadPlugins(array $breadcrumbs)
	{
		$this->loadSitePlugins();

		// Load zoom plugin
		$this->view->getPluginBag()
				   ->load(array('zoom'));

		// Alter breadcrumbs in view
		$this->view->getBreadcrumbBag()
				   ->add($breadcrumbs);
	}
}
