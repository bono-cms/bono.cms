<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Slider\Controller\Admin;

use Cms\Controller\Admin\AbstractController;

abstract class AbstractBrowser extends AbstractController
{
	/**
	 * Returns template path
	 * 
	 * @return string
	 */
	final protected function getTemplatePath()
	{
		return 'browser';
	}

	/**
	 * Loads shared plugins
	 * 
	 * @return void
	 */
	final protected function loadSharedPlugins()
	{
		$this->view->getPluginBag()
				   ->appendScript($this->getWithAssetPath('/admin/browser.js'));
	}

	/**
	 * Returns image manager
	 * 
	 * @return \Slider\Service\ImageManager
	 */
	final protected function getImageManager()
	{
		return $this->getSliderModule()->getService('imageManager');
	}

	/**
	 * Returns category manager
	 * 
	 * @return \Slider\Service\CategoryManager
	 */
	final protected function getCategoryManager()
	{
		return $this->getSliderModule()->getService('categoryManager');
	}

	/**
	 * Returns slider's module definition
	 * 
	 * @return \Slider\Module
	 */
	final protected function getSliderModule()
	{
		return $this->moduleManager->getModule('Slider');
	}

	/**
	 * Returns shared variables
	 * 
	 * @param array $overrides
	 * @return array
	 */
	final protected function getSharedVars(array $overrides)
	{
		$this->view->getBreadcrumbBag()->add(array(
			array(
				'name' => 'Slider',
				'link' => '#'
			)
		));

		$vars = array(
			'title' => 'Slider',
			'taskManager' => $this->getSliderModule()->getService('taskManager'),
			'categories' => $this->getCategoryManager()->fetchAll(),
		);
		
		return array_replace_recursive($vars, $overrides);
	}
}
