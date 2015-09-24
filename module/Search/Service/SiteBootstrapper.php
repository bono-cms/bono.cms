<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Search\Service;

use Cms\Service\SiteBootstrapperInterface;
use Krystal\Application\View\ViewManagerInterface;

final class SiteBootstrapper implements SiteBootstrapperInterface
{
	/**
	 * View manager whose state would be altered
	 * 
	 * @var \Krystal\Application\View\ViewManagerInterface
	 */
	private $view;

	/**
	 * State initialization
	 * 
	 * @param \Krystal\Application\View\ViewManagerInterface $view
	 * @return void
	 */
	public function __construct(ViewManagerInterface $view)
	{
		$this->view = $view;
	}

	/**
	 * {@inheritDoc}
	 */
	public function bootstrap()
	{
		$this->view->addVariable('search', new SiteService());
	}
}
