<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace MailForm\Controller\Admin;

use Cms\Controller\Admin\AbstractController;
use Krystal\Validate\Pattern;

abstract class AbstractForm extends AbstractController
{
	/**
	 * Returns prepared and configured form validator
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
	 * Loads shared plugins
	 * 
	 * @return void
	 */
	final protected function loadSharedPlugins()
	{
		$this->loadMenuWidget();
		$this->view->getPluginBag()->load($this->getWysiwygPluginName())
					->appendScript($this->getWithAssetPath('/admin/form.js'));
	}

	/**
	 * Returns form manager
	 * 
	 * @return \MailForm\Service\FormManager
	 */
	final protected function getFormManager()
	{
		return $this->getModuleService('formManager');
	}

	/**
	 * Loads shared plugins
	 * 
	 * @param array $overrides
	 * @return void
	 */
	final protected function getWithSharedVars(array $overrides)
	{
		$this->view->getBreadcrumbBag()->add(array(
			array(
				'name' => 'Mail forms',
				'link' => 'MailForm:Admin:Browser@indexAction'
			),
			array(
				'name' => $overrides['title'],
				'link' => '#'
			)
		));
		
		$vars = array();
		return array_replace_recursive($vars, $overrides);
	}
}
