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

final class Basket extends AbstractShopController
{
	/**
	 * Shows a basket page
	 * 
	 * @param string $id Page id
	 * @return string
	 */
	public function indexAction($id)
	{
		$pageManager = $this->moduleManager->getModule('Pages')->getService('pageManager');

		$page = $pageManager->fetchById($id);

		if ($page !== false) {
			$this->loadPlugins($page);

			return $this->view->render($this->getConfig()->getBasketTemplate(), array(
				'products' => $this->getBasketManager()->getProducts(),
				'page' => $page,

				'deliveryTypes' => array(
					'I will take myself',
					'Via courier'
				),
			));

		} else {
			// Returning false will trigger 404 error
			return false;
		}
	}

	/**
	 * Loads required plugins for view
	 * 
	 * @param $page
	 * @return void
	 */
	private function loadPlugins($page)
	{
		$this->loadSitePlugins();
		$this->view->getBreadcrumbBag()->add(array(
			array(
				'name' => $page->getTItle(),
				'link' => '#'
			)
		));
		
		// Append handler for a site
		$this->view->getPluginBag()
				   ->appendScript($this->getWithAssetPath('/form.js', 'MailForm'));
	}

	/**
	 * Returns basket manager
	 * Just a shortcut
	 * 
	 * @return \Shop\Service\BasketManager
	 */
	private function getBasketManager()
	{
		return $this->moduleManager->getModule('Shop')->getService('basketManager');
	}

	/**
	 * Recounts the price with its new quantity for one product
	 * 
	 * @return string
	 */
	public function recountAction()
	{
		$id = $this->request->getPost('id');
		$qty = $this->request->getPost('qty');

		$basketManager = $this->getBasketManager();

		$basketManager->recount($id, $qty);
		$basketManager->save();

		return json_encode(array(
			'product' => $basketManager->getProductStat($id),
			'all' => $this->getBasketManager()->getAllStat()
		));
	}

	/**
	 * Returns common basket statistic as JSON string (so that we can easily read it on client-side)
	 * 
	 * @return string
	 */
	public function getStatAction()
	{
		return json_encode($this->getBasketManager()->getAllStat());
	}

	/**
	 * Adds a product id into a basket with its quantity
	 * 
	 * @return string
	 */
	public function addAction()
	{
		$id = $this->request->getPost('id');
		$qty = $this->request->getPost('qty');

		// Grab basket manager to add it
		$basketManager = $this->getBasketManager();
		$basketManager->add($id, $qty);
		$basketManager->save();

		return json_encode($basketManager->getAllStat());
	}

	/**
	 * Makes an order
	 * 
	 * @return string
	 */
	public function deleteAction()
	{
		$id = $this->request->getPost('id');

		$basketManager = $this->getBasketManager();
		$basketManager->removeById($id);
		$basketManager->save();

		return json_encode($basketManager->getAllStat());
	}

	/**
	 * Clears the basket
	 * 
	 * @return string
	 */
	public function clearAction()
	{
		$basketManager = $this->getBasketManager();
		$basketManager->clear();
		$basketManager->save();

		$this->flashMessenger->set('success', 'Your basket has been cleared successfully');

		return json_encode($basketManager->getAllStat());
	}
}
