<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Menu\Controller\Admin\Category;

use Cms\Controller\Admin\AbstractController;
use Krystal\Validate\Pattern;

abstract class AbstractCategory extends AbstractController
{
	/**
	 * Returns prepared validator
	 * 
	 * @param array $post Raw input data
	 * @return \Krystal\Validate\ValidatorChain
	 */
	final protected function getValidator(array $input)
	{
		return $this->validatorFactory->build(array(
			'input' => array(
				'source' => $input,
				'definition' => array(
					'name' => new Pattern\Name(),
					'class' => new Pattern\ClassName()
				)
			)
		));
	}

	/**
	 * Loads shared plugins
	 * 
	 * @return void
	 */
	final protected function loadSharedPlugins()
	{
		$this->view->getPluginBag()
				   ->appendScript($this->getWithAssetPath('/admin/category.form.js'));
	}

	/**
	 * Returns template path
	 * 
	 * @return string
	 */
	final protected function getTemplatePath()
	{
		return 'category.form';
	}

	/**
	 * Returns category manager
	 * 
	 * @return \Menu\Service\CategoryManager
	 */
	final protected function getCategoryManager()
	{
		return $this->moduleManager->getModule('Menu')->getService('categoryManager');
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
				'link' => 'Menu:Admin:Item@indexAction',
				'name' => 'Menu'
			),
			array(
				'link' => '#',
				'name' => $overrides['title']
			)
		));

		$vars = array();
		return array_replace_recursive($vars, $overrides);
	}
}
