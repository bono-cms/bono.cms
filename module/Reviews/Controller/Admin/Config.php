<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Reviews\Controller\Admin;

use Cms\Controller\Admin\AbstractController;
use Krystal\Validate\Pattern;

final class Config extends AbstractController
{
	/**
	 * Shows a form
	 * 
	 * @return string
	 */
	public function indexAction()
	{
		$this->loadPlugins();

		return $this->view->render('config', array(
			'title' => 'Configuration',
			'config' => $this->getConfigManager()->getEntity()
		));
	}

	/**
	 * Saves the data
	 * 
	 * @return string The response
	 */
	public function saveAction()
	{
		$formValidator = $this->getValidator($this->request->getPost());

		if ($formValidator->isValid()) {

			$this->getConfigManager()->write($this->request->getPost());
			$this->flashMessenger->set('success', 'Settings have been saved');

			return '1';

		} else {

			return $formValidator->getErrors();
		}
	}

	/**
	 * Loads required plugins for view
	 * 
	 * @return void
	 */
	private function loadPlugins()
	{
		$this->view->getBreadcrumbBag()->add(array(
			array(
				'name' => 'Reviews',
				'link' => 'Reviews:Admin:Browser@indexAction'
			),
			array(
				'name' => 'Configuration',
				'link' => '#'
			)
		));

		$this->view->getPluginBag()
				   ->appendScript($this->getWithAssetPath('/admin/config.js'));
	}

	/**
	 * Returns configuration manager
	 * 
	 * @return \Reviews\Service\ConfigManager
	 */
	private function getConfigManager()
	{
		return $this->moduleManager->getModule('Reviews')->getService('configManager');
	}

	/**
	 * Returns prepared validator
	 * 
	 * @param array $input Raw input data
	 * @return \Krystal\Validate\ValidatorChain
	 */
	private function getValidator(array $input)
	{
		return $this->validatorFactory->build(array(
			'input' => array(
				'source' => $input,
				'definition' => array(
					'per_page_count' => new Pattern\PerPageCount()
				)
			)
		));
	}
}
