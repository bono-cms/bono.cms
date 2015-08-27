<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Qa\Controller\Admin;

use Cms\Controller\Admin\AbstractController;
use Krystal\Validate\Pattern;

final class Config extends AbstractController
{
	/**
	 * Shows configuration form
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
	 * Saves configuration
	 * 
	 * @return string
	 */
	public function updateAction()
	{
		$formValidator = $this->getValidator($this->request->getPost('config'));

		if ($formValidator->isValid()) {

			$this->getConfigManager()->write($this->request->getPost('config'));
			$this->flashBag->set('success', 'Configuration has been updated successfully');

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
		$this->view->getPluginBag()
				   ->appendScript($this->getWithAssetPath('/admin/config.js'));

		$this->view->getBreadcrumbBag()->add(array(
			array(
				'link' => 'Qa:Admin:Browser@indexAction',
				'name' => 'Questions and Answers'
			),
			array(
				'link' => '#',
				'name' => 'Configuration'
			)
		));
	}

	/**
	 * Returns configuration manager
	 * 
	 * @return \Photogallery\Service\ConfigManager
	 */
	private function getConfigManager()
	{
		return $this->getModuleService('configManager');
	}

	/**
	 * Returns prepared and configured form validator
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
