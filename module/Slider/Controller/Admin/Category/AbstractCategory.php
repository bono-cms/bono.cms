<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Slider\Controller\Admin\Category;

use Cms\Controller\Admin\AbstractController;
use Krystal\Validate\Pattern;

abstract class AbstractCategory extends AbstractController
{
	/**
	 * Returns form validator
	 * 
	 * @param array $post Raw post data
	 * @return \Krystal\Validate\ValidatorChain
	 */
	final protected function getValidator(array $post)
	{
		return $this->validatorFactory->build(array(
			'input' => array(
				'source' => $post,
				'definition' => array(
					'name' => new Pattern\Name(),
					'width' => new Pattern\Width(),
					'height' => new Pattern\Height(),
					'class' => new Pattern\ClassName()
				)
			)
		));
	}

	/**
	 * Returns shared variables
	 * 
	 * @param array $overrides
	 * @return array
	 */
	final protected function getWithSharedVars(array $overrides)
	{
		$this->view->getBreadcrumbBag()->add(array(
			array(
				'name' => 'Slider',
				'link' => 'Slider:Admin:Browser@indexAction'
			),
			array(
				'name' => $overrides['title'],
				'link' => '#'
			)
		));

		$vars = array(
		);

		return array_replace_recursive($vars, $overrides);
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
	 * Returns category manager
	 * 
	 * @return \Slider\Service\CategoryManager
	 */
	final protected function getCategoryManager()
	{
		return $this->getModuleService('categoryManager');
	}
}
