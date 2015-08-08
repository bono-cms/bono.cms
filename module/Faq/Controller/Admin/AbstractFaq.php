<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Faq\Controller\Admin;

use Krystal\Validate\Pattern;

abstract class AbstractFaq extends AbstractAdminController
{
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
					'question' => new Pattern\Title(),
					'answer' => new Pattern\Content()
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
				'name' => 'FAQ',
				'link' => 'Faq:Admin:Browser@indexAction'
			),
			
			array(
				'name' => $overrides['title'],
				'link' => '#'
			)
		));
		
		return $overrides;
	}

	/**
	 * Loads shared plugins
	 * 
	 * @return void
	 */
	final protected function loadSharedPlugins()
	{
		$this->view->getPluginBag()
				   ->appendScript($this->getWithAssetPath('/admin/faq.form.js'))
				   ->load($this->getWysiwygPluginName());
	}

	/**
	 * Returns template path
	 * 
	 * @return string
	 */
	final protected function getTemplatePath()
	{
		return 'faq.form';
	}
}
