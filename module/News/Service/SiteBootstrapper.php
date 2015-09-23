<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace News\Service;

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
	 * {@inheritDoc}
	 */
	public function bootstrap()
	{
		$siteService = $this->moduleManager->getModule('News')->getService('siteService');
		$this->view->addVariable('news', $siteService);
	}
}
