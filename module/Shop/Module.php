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
use Shop\Service\SiteService;

final class Module extends AbstractShopModule
{
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

		$config = $this->getConfigService();

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

		$siteService = new SiteService($productManager, $this->getRecentProduct($productManager), $config->getEntity());

		return array(

			'siteService' => $siteService,
			'configManager' => $config,
			'orderManager' => new OrderManager($orderInfoMapper, $orderProductMapper, $basketManager),
			'basketManager' => $basketManager,
			'taskManager' => new TaskManager($productMapper, $categoryManager),
			'productManager' => $productManager,
			'categoryManager' => $categoryManager
		);
	}
}
