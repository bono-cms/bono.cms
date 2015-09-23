<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Menu\Service;

use Cms\Service\SiteBootstrapperInterface;
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
	 * Theme's configuration
	 * 
	 * @var array
	 */
	private $config;

	/**
	 * State initialization
	 * 
	 * @param \Krystal\Application\Module\ModuleManagerInterface $moduleManager
	 * @param \Krystal\Application\View\ViewManagerInterface $view
	 * @param array $config
	 * @return void
	 */
	public function __construct(ModuleManagerInterface $moduleManager, ViewManagerInterface $view, array $config)
	{
		$this->moduleManager = $moduleManager;
		$this->view = $view;
		$this->config = $config;
	}

	/**
	 * {@inheritDoc}
	 */
	public function bootstrap()
	{
		$homeWebPageId = $this->moduleManager->getModule('Pages')->getService('pageManager')->getDefaultWebPageId();
		$this->view->addVariable('menu', $this->getSiteService($homeWebPageId));
	}

	/**
	 * Returns menu's block service
	 * 
	 * @param string $homeWebPageId $homeWebPageId
	 * @return \Menu\Service\Block
	 */
	private function getSiteService($homeWebPageId)
	{
		$block = $this->moduleManager->getModule('Menu')->getService('siteService');
		$block->setHomeWebPageId($homeWebPageId);

		// If we have menu configuration
		if (isset($this->config['menu']) && is_array($this->config['menu'])) {
			$block->register($this->config['menu']);
		}

		return $block;
	}
}
