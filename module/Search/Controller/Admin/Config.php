<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Search\Controller\Admin;

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
			'title' => 'Search',
			'config' => $this->getConfigManager()->getEntity()
		));
	}

	/**
	 * Saves settings
	 * 
	 * @return string
	 */
	public function saveAction()
	{
		$formValdator = $this->getValidator($this->request->getPost());

		if ($formValdator->isValid()) {

			$this->getConfigManager()->write($this->request->getPost());
			$this->flashMessenger->set('success', 'Settings have been saved');

			return '1';

		} else {
			return $formValdator->getErrors();
		}
	}

	/**
	 * Loads required plugins
	 * 
	 * @return void
	 */
	private function loadPlugins()
	{
		$this->view->getBreadcrumbBag()->add(array(
			array(
				'name' => 'Search',
				'link' => '#'
			)
		));

		$this->view->getPluginBag()->appendScript($this->getWithAssetPath('/admin/config.js'));
	}

	/**
	 * Returns configuration manager
	 * 
	 * @return \Search\Service\ConfigManager
	 */
	private function getConfigManager()
	{
		return $this->moduleManager->getModule('Search')->getService('configManager');
	}
	
	/**
	 * Returns prepared form validator
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
					'per_page_count' => new Pattern\PerPageCount(),
					'template' => new Pattern\TemplateName()
				)
			)
		));
	}
}
