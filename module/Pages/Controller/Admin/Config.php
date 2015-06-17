<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Pages\Controller\Admin;

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
	 * Saves data
	 * 
	 * @return string
	 */
	public function saveAction()
	{
		$formValidator = $this->getValidator($this->request->getPost());

		if ($formValidator->isValid()) {

			$this->getConfigManager()->write($this->request->getPost());
			$this->flashMessenger->set('success', 'Settings have been updated successfully');

		} else {
			return $formValidator->getErrors();
		}

		return '1';
	}

	/**
	 * Loads required plugins
	 * 
	 * @return void
	 */
	private function loadPlugins()
	{
		$this->view->getPluginBag()
				   ->appendScript($this->getWithAssetPath('/admin/config.js'));
		
		$this->view->getBreadcrumbBag()->add(array(
			array(
				'link' => 'Pages:Admin:Browser@indexAction',
				'name' => 'Pages'
			),
			array(
				'link' => '#',
				'name' => 'Configuration'
			)
		));
	}

	/**
	 * Returns prepared and configured validator
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
					'page_template' => new Pattern\TemplateName(array(
						'rules' => array(
							'NotEmpty' => array(
								'message' => 'Page template name can not be empty'
							),
						)
					)),
					
					'home_template' => new Pattern\TemplateName(array(
						'rules' => array(
							'NotEmpty' => array(
								'message' => 'Home page template name can not be empty'
							),
						)
					)),
				)
			)
		));
	}

	/**
	 * Returns configuration manager
	 * 
	 * @return \Pages\Service\ConfigManager
	 */
	private function getConfigManager()
	{
		return $this->moduleManager->getModule('Pages')->getService('configManager');
	}
}
