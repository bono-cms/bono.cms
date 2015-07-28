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
use Krystal\Db\Filter\QueryContainer;

final class Order extends AbstractController
{
	/**
	 * Applies the filter
	 * 
	 * @return string
	 */
	public function filterAction()
	{
		$records = $this->getFilter($this->getOrderManager());

		if ($records !== false) {

			$this->loadPlugins();
			return $this->view->render($this->getTemplatePath(), $this->getRecordsWithSharedVars($records, $this->getOrderManager()->getPaginator()));

		} else {

			// None selected, so no need to apply a filter
			return $this->indexAction();
		}
	}

	/**
	 * Shows order's grid
	 * 
	 * @param string $page Current page number
	 * @return string
	 */
	public function indexAction($page = 1)
	{
		$orderManager = $this->getOrderManager();

		$paginator = $orderManager->getPaginator();
		$paginator->setUrl('/admin/module/shop/orders/page/%s');

		// Grab all order entities
		$orders = $orderManager->fetchAllByPage($page, $this->getSharedPerPageCount());

		$this->loadPlugins();
		return $this->view->render($this->getTemplatePath(), $this->getRecordsWithSharedVars($orders, $paginator));
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

				$this->flashBag->set('success', 'Selected order marked as approved now');
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

				$this->flashBag->set('success', 'Selected order has been removed successfully');
				return '1';
			}
		}
	}

	/**
	 * Shows details for a given order id
	 * 
	 * @param string $id Order id
	 * @return string
	 */
	public function detailsAction($id)
	{
		$details = $this->getOrderManager()->fetchAllDetailsByOrderId($id);

		return $this->view->disableLayout()->render('order-details', array(

			'id' => $id,
			'currency' => $this->getConfig()->getCurrency(),
			'details' => $details
		));
	}

	/**
	 * Returns template path
	 * 
	 * @return string
	 */
	private function getTemplatePath()
	{
		return 'orders';
	}

	/**
	 * Returns shared variables for the template to display
	 * 
	 * @param array $records
	 * @param $paginator
	 * @return array
	 */
	private function getRecordsWithSharedVars(array $records, $paginator)
	{
		return array(
			'orders' => $records,
			'paginator' => $paginator,
			'config' => $this->getConfig(),
			'title' => 'Orders',
			'filter' => new QueryContainer($this->request->getQuery('filter'))
		);
	}

	/**
	 * Loads required view plugins
	 * 
	 * @return void
	 */
	private function loadPlugins()
	{
		$this->view->getPluginBag()
				   ->load('datepicker')
				   ->appendScript($this->getWithAssetPath('/admin/orders.js'));
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
