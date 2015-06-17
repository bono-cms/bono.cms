<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace News\Controller\Admin;

use Cms\Controller\Admin\AbstractController;

abstract class AbstractBrowser extends AbstractController
{
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
	 * Return shared variables
	 * 
	 * @param array $overrides
	 * @return array
	 */
	final protected function getSharedVars(array $overrides)
	{
		$news = $this->moduleManager->getModule('News');
		
		$this->view->getBreadcrumbBag()->add(array(
			array(
				'link' => '#',
				'name' => 'News'
			)
		));
		
		$vars = array(
			'title' => 'News',
			'categories' => $news->getService('categoryManager')->fetchAll(),
			'taskManager' => $news->getService('taskManager'),
		);
		
		return array_replace_recursive($vars, $overrides);
	}

	/**
	 * Returns news module
	 * 
	 * @return \News\Module
	 */
	final protected function getNewsModule()
	{
		return $this->moduleManager->getModule('News');
	}
	
	/**
	 * Returns post manager
	 * 
	 * @return \News\Service\PostManager
	 */
	final protected function getPostManager()
	{
		return $this->getNewsModule()->getService('postManager');
	}

	/**
	 * Returns category manager
	 * 
	 * @return \News\Service\CategoryManager
	 */
	final protected function getCategoryManager()
	{
		return $this->getNewsModule()->getService('categoryManager');
	}

	/**
	 * Returns template path
	 * 
	 * @return string
	 */
	final protected function getTemplatePath()
	{
		return 'browser';
	}	
}
