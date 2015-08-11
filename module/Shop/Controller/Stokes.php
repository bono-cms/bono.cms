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

final class Stokes extends AbstractShopController
{
	/**
	 * Shows all available products that have stoke prices
	 * 
	 * @param string $id Page id
	 * @param integer $pageNumber Current page number
	 * @param string $code Optional language code
	 * @param string $slug Optional slug
	 * @return string
	 */
	public function indexAction($id, $pageNumber = 1, $code = null, $slug = null)
	{
		$pageManager = $this->getService('Pages', 'pageManager');
		$page = $pageManager->fetchById($id);

		// First of all, make sure the page exist
		if ($page !== false) {

			$productManager = $this->getModuleService('productManager');
			$products = $productManager->fetchAllPublishedStokesByPage($pageNumber, $this->getConfig()->getStokePerPageCount());

			$this->loadPlugins($page->getTitle());

			// Grab and configure pagination component
			$paginator = $productManager->getPaginator();

			// If $slug isn't null by type, then this controller is invoked manually from Site module
			if ($slug !== null) {
				$this->preparePaginator($paginator, $code, $slug, $pageNumber);
			}

			return $this->view->render('shop-stokes', array(
				'paginator' => $paginator,
				'products' => $products,
				'page' => $page,
			));

		} else {

			return false;
		}
	}

	/**
	 * Loads category plugins
	 * 
	 * @param string $title Page title
	 * @return void
	 */
	private function loadPlugins($title)
	{
		$this->loadSitePlugins();

		// Append breadcrumbs now
		$this->view->getBreadcrumbBag()->add(array(
			array(
				'link' => '#',
				'name' => $title
			)
		));
	}
}
