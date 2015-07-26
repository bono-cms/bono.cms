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

final class Order extends AbstractController
{
	/**
	 * Shows order's grid
	 * 
	 * @param string $page Current page number
	 * @return string
	 */
	public function indexAction($page = 1)
	{
		$this->view->getPluginBag()
				   ->appendScript($this->getWithAssetPath('/admin/orders.js'));

		$orderManager = $this->getOrderManager();

		$paginator = $orderManager->getPaginator();
		$paginator->setUrl('/admin/module/shop/orders/page/%s');

		// Grab all order entities
		$orders = $orderManager->fetchAllByPage($page, $this->getSharedPerPageCount());

		return $this->view->render('orders', array(
			
			'config' => $this->getConfig(),
			'title' => 'Orders',
			'paginator' => $paginator,
			'orders' => $orders,
		));
	}

	/**
	 * Approves an order by its id
	 * 
	 * @return string
	 */
	public function approveAction()
	{
		if ($this->request->hasPost('id')) {
			$id = $this->request->getPost('id');

			if ($this->getOrderManager()->approveById($id)) {

				$this->flashMessenger->set('success', 'Selected order marked as approved now');
				return '1';
			}
		}
	}

	/**
	 * Deletes an order by its associated id
	 * 
	 * @return string
	 */
	public function deleteAction()
	{
		if ($this->request->hasPost('id')) {
			$id = $this->request->getPost('id');

			if ($this->getOrderManager()->removeById($id)) {

				$this->flashMessenger->set('success', 'Selected order has been removed successfully');
				return '1';
			}
		}
	}

	/**
	 * Shows details for a given order id
	 * 
	 * @param string $id Order's id
	 * @return string
	 */
	public function detailsAction($id)
	{
		$details = $this->getOrderManager()->fetchAllDetailsByOrderId($id);

		return $this->view->render('order-details', array(

			'id' => $id,
			'currency' => $this->getConfig()->getCurrency(),
			'title' => 'Order products',
			'details' => $details
		));
	}

	/**
	 * Returns order manager
	 * 
	 * @return \Shop\Service\OrderManager
	 */
	private function getOrderManager()
	{
		return $this->getModuleService('orderManager');
	}

	/**
	 * Returns configuration entity
	 * 
	 * @return \Krystal\Stdlib\VirtualEntity
	 */
	private function getConfig()
	{
		return $this->getModuleService('configManager')->getEntity();
	}
}
