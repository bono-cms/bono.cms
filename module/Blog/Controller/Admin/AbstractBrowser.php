<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Blog\Controller\Admin;

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
	 * Returns blog module
	 * 
	 * @return \Blog\Module
	 */
	final protected function getBlogModule()
	{
		return $this->moduleManager->getModule('Blog');
	}

	/**
	 * Returns PostManager
	 * 
	 * @return \Blog\Service\PostManager
	 */
	final protected function getPostManager()
	{
		return $this->getBlogModule()->getService('postManager');
	}

	/**
	 * Returns CategoryManager
	 * 
	 * @return \Blog\Service\CategoryManager
	 */
	final protected function getCategoryManager()
	{
		return $this->getBlogModule()->getService('categoryManager');
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
				'name' => 'Blog',
				'link' => '#'
			)
		));

		$vars = array(
			'title' => 'Blog',
			'taskManager' => $this->getBlogModule()->getService('taskManager'),
			'categories' => $this->getBlogModule()->getService('categoryManager')->fetchAll()
		);

		return array_replace_recursive($vars, $overrides);
	}
}
