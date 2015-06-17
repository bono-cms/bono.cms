<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Shop;

use Shop\Service\ProductManager;
use Shop\Service\CategoryManager;
use Shop\Service\TaskManager;
use Shop\Service\OrderManager;
use Shop\Service\ProductRemover;

final class Module extends AbstractShopModule
{
	/**
	 * {@inheritDoc}
	 */
	public function getRoutes()
	{
		return include(__DIR__ . '/Config/routes.php');
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTranslations($language)
	{
		return $this->loadArray(__DIR__ . '/Translations/'.$language.'/messages.php');
	}

	/**
	 * {@inheritDoc}
	 */
	public function getConfigData()
	{
		return include(__DIR__ . '/Config/module.config.php');
	}

	/**
	 * {@inheritDoc}
	 */
	public function getServiceProviders()
	{
		// Build required mappers
		$imageMapper = $this->getMapper('/Shop/Storage/MySQL/ImageMapper', false);
		$productMapper = $this->getMapper('/Shop/Storage/MySQL/ProductMapper');
		$categoryMapper = $this->getMapper('/Shop/Storage/MySQL/CategoryMapper');
		$orderInfoMapper = $this->getMapper('/Shop/Storage/MySQL/OrderInfoMapper', false);
		$orderProductMapper = $this->getMapper('/Shop/Storage/MySQL/OrderProductMapper', false);

		// Now build required services
		$productImageManager = $this->getProductImageManager();
		$webPageManager = $this->getWebPageManager();
		$historyManager = $this->getHistoryManager();

		$basketManager = $this->getBasketManager($productMapper, $productImageManager->getImageBag());
		$basketManager->load();

		$productRemover = new ProductRemover($productMapper, $imageMapper, $webPageManager, $productImageManager);
		
		// Build category manager
		$categoryManager = new CategoryManager(
			$categoryMapper, 
			$productMapper, 
			$webPageManager, 
			$this->getCategoryImageManager(), 
			$historyManager, 
			$productRemover,
			$this->getMenuWidget()
		);
		
		$productManager = new ProductManager(
			$productMapper, 
			$imageMapper, 
			$categoryMapper, 
			$webPageManager, 
			$productImageManager, 
			$historyManager,
			$productRemover
		);

		return array(

			'configManager' => $this->getConfigManager(),
			'orderManager' => new OrderManager($orderInfoMapper, $orderProductMapper, $basketManager, $this->getNotificationManager(), $this->getMailer()),
			'recentProduct' => $this->getRecentProduct($productManager),
			'basketManager' => $basketManager,
			'taskManager' => new TaskManager($productMapper, $categoryManager),
			'productManager' => $productManager,
			'categoryManager' => $categoryManager
		);
	}
}
