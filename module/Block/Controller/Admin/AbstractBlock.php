<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Block\Controller\Admin;

use Krystal\Validate\Pattern;

abstract class AbstractBlock extends AbstractAdminController
{
	/**
	 * Returns prepared and configured validator's instance
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
					'name' => new Pattern\Name(),
					'content' => new Pattern\Content()
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
		return 'block.form';
	}

	/**
	 * Loads shared plugins
	 * 
	 * @return void
	 */
	final protected function loadSharedPlugins()
	{
		$this->view->getPluginBag()
				   ->appendScript($this->getWithAssetPath('/admin/block.form.js'));
	}

	/**
	 * Returns shared variables for Edit and Add controllers
	 * 
	 * @param array $overrides
	 * @return array
	 */
	final protected function getSharedVars(array $overrides)
	{
		$this->view->getBreadcrumbBag()->add(array(
			array(
				'link' => 'Block:Admin:Browser@indexAction',
				'name' => 'HTML Blocks'
			),
			array(
				'link' => '#',
				'name' => $overrides['title']
			)
		));

		$vars = array(
		);

		return array_replace_recursive($vars, $overrides);
	}
}
