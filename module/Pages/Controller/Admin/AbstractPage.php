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

use Krystal\Application\Route\MapManager;
use Krystal\Validate\Pattern;
use Cms\Controller\Admin\AbstractController;

abstract class AbstractPage extends AbstractController
{
	/**
	 * Returns a list of all loaded controllers
	 * 
	 * @return array
	 */
	protected function getControllers()
	{
		// Return all loaded routes
		$routes = $this->moduleManager->getRoutes();
		$mapManager = new MapManager($routes);

		return $mapManager->getControllers();
	}

	/**
	 * Returns prepared form
	 */
	protected function getForm($page = null)
	{
		$form = $this->makeForm('\Pages\View\Form\PageForm');
		$form->setData('controllers', $this->getControllers());

		if ($page !== null) {
			$form->populate(function() use ($page){
				return array(
					'id' => $page->getId(),
					'web_page_id' => $page->getWebPageId(),
					'title' => $page->getTitle(),
					'content' => $page->getContent(),
					'controller' => $page->getController(),
					'template' => $page->getTemplate(),
					'protected' => $page->getProtected(),
					'makeDefault' => $page->getDefault(),
					'seo' => $page->getSeo(),
					'slug' => $page->getSlug(),
					'keywords' => $page->getKeywords(),
					'meta_description' => $page->getMetaDescription(),
				);
			});
		}

		return $form;
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
	final protected function getSharedVars(array $overrides)
	{
		// Return all loaded routes
		$routes = $this->moduleManager->getRoutes();
		$mapManager = new MapManager($routes);

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

		$current = array(
			'controllers' => $mapManager->getControllers(),
			'page' => $this->moduleManager->getModule('Pages')->getService('pageManager')->fetchDummy()
		);

		return array_replace_recursive($current, $overrides);
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
		return $this->moduleManager->getModule('Pages')->getService('pageManager');
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
