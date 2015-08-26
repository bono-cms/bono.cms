<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Pages\Controller\Admin;

use Krystal\Validate\Pattern;
use Cms\Controller\Admin\AbstractController;
use Pages\Service\ControllerProvider;

abstract class AbstractPage extends AbstractController
{
	/**
	 * Returns a list of all loaded controllers
	 * 
	 * @return array
	 */
	private function getControllers()
	{
		$provider = new ControllerProvider($this->moduleManager->getRoutes());
		return $provider->getControllers();
	}

	/**
	 * Returns configured form validator
	 * 
	 * @param array $input Raw input data
	 * @return \Krystal\Validate\ValidatorChain
	 */
	final protected function getValidator(array $input)
	{
		return $this->validatorFactory->build(array(
			'input' => array(
				'source' => $input,
				'definition' => array(
					'title' => new Pattern\Title()
				)
			)
		));
	}

	/**
	 * Returns shared variables for Edit and Add controllers
	 * 
	 * @param array $overrides
	 * @return array
	 */
	final protected function getWithSharedVars(array $overrides)
	{
		$this->view->getBreadcrumbBag()->add(array(
			array(
				'link' => 'Pages:Admin:Browser@indexAction',
				'name' => 'Pages'
			),

			array(
				'link' => '#',
				'name' => $overrides['title']
			)
		));

		return array_replace_recursive(array('controllers' => $this->getControllers()), $overrides);
	}

	/**
	 * Load shared plugins for Add and Edit controllers
	 * 
	 * @return void
	 */
	final protected function loadSharedPlugins()
	{
		$this->loadMenuWidget();
		$this->view->getPluginBag()->load($this->getWysiwygPluginName())
								   ->appendScript($this->getWithAssetPath('/admin/page.form.js'));
	}

	/**
	 * Returns page manager
	 * 
	 * @return \Pages\Service\PageManager
	 */
	final protected function getPageManager()
	{
		return $this->getModuleService('pageManager');
	}

	/**
	 * Returns template path
	 * 
	 * @return string
	 */
	final protected function getTemplatePath()
	{
		return 'page.form';
	}
}
