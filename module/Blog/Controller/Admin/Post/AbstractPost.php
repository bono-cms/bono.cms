<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Blog\Controller\Admin\Post;

use Blog\Controller\Admin\AbstractAdminController;
use Krystal\Validate\Pattern;

abstract class AbstractPost extends AbstractAdminController
{
	/**
	 * Returns prepared and configured validator
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
					'title' => new Pattern\Title(),
					'introduction' => new Pattern\IntroText(),
					'full' => new Pattern\FullText()
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
		return 'post.form';
	}

	/**
	 * Return shared variables
	 * 
	 * @param array $overrides
	 * @return array
	 */
	final protected function getSharedVars(array $overrides)
	{
		$this->view->getBreadcrumbBag()->add(array(
			array(
				'name' => 'Blog',
				'link' => 'Blog:Admin:Browser@indexAction'
			),
			array(
				'name' => $overrides['title'],
				'title' => '#'
			)
		));

		$vars = array(
			'categories' => $this->getCategoryManager()->fetchAll(),
		);

		return array_replace_recursive($vars, $overrides);
	}

	/**
	 * Loads shared plugins
	 * 
	 * @return void
	 */
	final protected function loadSharedPlugins()
	{
		$this->view->getPluginBag()->appendScript($this->getWithAssetPath('/admin/post.form.js'))
								   ->load(array($this->getWysiwygPluginName(), 'datepicker'));
	}
}
