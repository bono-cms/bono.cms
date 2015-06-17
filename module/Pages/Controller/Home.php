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

/**
 * This controller contains all home-page related logic
 */
final class Home extends AbstractController
{
	/**
	 * Appends variables to home page
	 * You should get provide them here if needed
	 * 
	 * @return array
	 */
	private function getAdditionalVars()
	{
		$shop = $this->moduleManager->getModule('Shop');
		$productManager = $shop->getService('productManager');

		$config = $shop->getService('configManager')->getEntity();

		return array(
			'newest' => $productManager->fetchLatestPublished($config->getShowCaseCount())
		);
	}

	/**
	 * Shows a home page
	 * 
	 * @return string
	 */
	public function indexAction()
	{
		$pages = $this->moduleManager->getModule('Pages');

		// Grab a service
		$pageManager = $pages->getService('pageManager');

		$page = $pageManager->fetchDefault();

		if ($page !== false) {
			$this->loadSitePlugins();

			// Clear all breadcrumbs
			$this->view->getBreadcrumbBag()->clear();

			//@TODO
			return $this->view->render('pages-home', array_merge($this->getAdditionalVars(), array(
				'page' => $page
			)));

		} else {
			
			// Returning false from a controller's action triggers 404 error automatically
			return false;
		}
	}
}
