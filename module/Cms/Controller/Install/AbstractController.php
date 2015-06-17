<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Controller\Install;

use Krystal\Application\Controller\AbstractController as CoreController;
use Krystal\Application\View\Resolver\Module as Resolver;

abstract class AbstractController extends CoreController
{
	/**
	 * {@inheritDoc}
	 */
	protected function getResolverThemeName()
	{
		return 'install';
	}

	/**
	 * {@inheritDoc}
	 */
	final protected function bootstrap()
	{
		$this->view->getBlockBag()->setBlocksDir($this->appConfig->getModulesDir() . '/Admin/View/Template/install' . '/blocks/');
		$this->view->getPluginBag()->load(array(
			'jquery',
			'bootstrap.core',
			'bootstrap.cosmo',
			'admin'
		));
	}
}
