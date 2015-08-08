<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace News\Controller\Admin\Category;

use Krystal\Validate\Pattern;
use News\Controller\Admin\AbstractAdminController;

abstract class AbstractCategory extends AbstractAdminController
{
	/**
	 * Returns prepared validator instance
	 * 
	 * @param array $input Raw form data
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
		$this->loadMenuWidget();
		$this->view->getPluginBag()
					->load($this->getWysiwygPluginName())
					->appendScript($this->getWithAssetPath('/admin/category.form.js'));
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
				'link' => 'News:Admin:Browser@indexAction',
				'name' => 'News'
			),
			
			array(
				'link' => '#',
				'name' => $overrides['title']
			)
		));
		
		// Nothing for now
		$vars = array();
		
		return array_replace_recursive($vars, $overrides);
	}
}
