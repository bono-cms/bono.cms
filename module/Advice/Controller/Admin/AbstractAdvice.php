<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Advice\Controller\Admin;

use Cms\Controller\Admin\AbstractController;
use Krystal\Validate\Pattern;

abstract class AbstractAdvice extends AbstractController
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
					'content' => new Pattern\Content()
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
	final protected function getSharedVars(array $overrides)
	{
		$this->view->getBreadcrumbBag()->add(array(
			array(
				'link' => 'Advice:Admin:Browser@indexAction',
				'name' => 'Advice'
			),
			array(
				'link' => '#',
				'name' => $overrides['title']
			)
		));

		$vars = array();
		return array_replace_recursive($vars, $overrides);
	}

	/**
	 * Loads shared plugins
	 * 
	 * @return void
	 */
	final protected function loadSharedPlugins()
	{
		$this->view->getPluginBag()->load($this->getWysiwygPluginName())
								   ->appendScript($this->getWithAssetPath('/admin/advice.form.js'));
	}

	/**
	 * Returns template path
	 * 
	 * @return string
	 */
	final protected function getTemplatePath()
	{
		return 'advice.form';
	}

	/**
	 * Returns advice manager instance
	 * 
	 * @return \Adivce\Service\AdivceManager
	 */
	final protected function getAdviceManager()
	{
		return $this->moduleManager->getModule('Advice')->getService('adviceManager');
	}
}
