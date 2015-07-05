<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Shop\Service;

use Cms\Service\SiteBootstrapperInterface;
use Shop\Service\BasketEntity;
use Krystal\Application\Module\ModuleManagerInterface;
use Krystal\Application\View\ViewManagerInterface;

final class SiteBootstrapper implements SiteBootstrapperInterface
{
	/**
	 * Module manager to grab data
	 * 
	 * @var \Krystal\Application\Module\ModuleManagerInterface
	 */
	private $moduleManager;

	/**
	 * View manager whose state would be altered
	 * 
	 * @var \Krystal\Application\View\ViewManagerInterface
	 */
	private $view;

	/**
	 * State initialization
	 * 
	 * @param \Krystal\Application\Module\ModuleManagerInterface $moduleManager
	 * @param \Krystal\Application\View\ViewManagerInterface $view
	 * @return void
	 */
	public function __construct(ModuleManagerInterface $moduleManager, ViewManagerInterface $view)
	{
		$this->moduleManager = $moduleManager;
		$this->view = $view;
	}

	/**
	 * Loads basket service
	 * 
	 * @return void
	 */
	private function loadBasket()
	{
		// For brevity
		$mm = $this->moduleManager;

		$webPageManager = $mm->getModule('Cms')->getService('webPageManager');
		$pageManager = $mm->getModule('Pages')->getService('pageManager');

		$shop = $mm->getModule('Shop');

		// Grab basket manager and load data from a storage
		$basketManager = $shop->getService('basketManager');
		$config = $shop->getService('configManager')->getEntity();

		$basketWebPageId = $pageManager->fetchWebPageIdById($config->getBasketPageId());
		$basketUrl = $webPageManager->getUrlByWebPageId($basketWebPageId);

		// Now tweak basket's entity
		$basket = new BasketEntity();
		$basket->setUrl($basketUrl);
		$basket->setTotalPrice($basketManager->getTotalPrice());
		$basket->setTotalQty($basketManager->getTotalQuantity());
		$basket->setCurrency($config->getCurrency());

		// Finally add $basket entity and append a script which handles a basket
		$this->view->addVariable('basket', $basket)
				   ->getPluginBag()
				   ->appendScript('/module/Shop/Assets/basket.module.js');
		
	}

	/**
	 * Loads shop service
	 * 
	 * @return void
	 */
	private function loadShop()
	{
		$siteService = $this->moduleManager->getModule('Shop')->getService('siteService');
		$this->view->addVariable('shop', $siteService);
	}

	/**
	 * {@inheritDoc}
	 */
	public function bootstrap()
	{
		$this->loadBasket();
		$this->loadShop();
	}
}
