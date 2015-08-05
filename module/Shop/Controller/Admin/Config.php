<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Shop\Controller\Admin;

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
			'title' => 'Tweaks',
			'config' => $this->getConfigManager()->getEntity(),
		));
	}

	/**
	 * Saves the form
	 * 
	 * @return string
	 */
	public function saveAction()
	{
		$formValidator = $this->getValidator($this->request->getPost('config'));

		if ($formValidator->isValid()) {

			$this->getConfigManager()->write($this->request->getPost('config'));
			$this->flashBag->set('success', "Shop's settings have been updated successfully");

			return '1';

		} else {
			return $formValidator->getErrors();
		}
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
				'name' => 'Shop',
				'link' => 'Shop:Admin:Browser@indexAction'
			),
			array(
				'name' => 'Configuration',
				'link' => '#'
			)
		));
	}

	/**
	 * Returns configuration manager
	 * 
	 * @return \Shop\Service\ConfigManager
	 */
	private function getConfigManager()
	{
		return $this->getModuleService('configManager');
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
					// Grab these rules from framework's patterns
					'default_category_per_page_count' => new Pattern\PerPageCount(),
					'currency' => new Pattern\Currency(),
					'showcase_count' => new Pattern\PerPageCount(),
					'category_cover_height' => new Pattern\ImageHeight(),
					'category_cover_width' => new Pattern\ImageWidth(),
					'cover_width' => new Pattern\ImageWidth(),
					'cover_height' => new Pattern\ImageHeight(),
					'thumb_height' => new Pattern\ImageHeight(),
					'thumb_width' => new Pattern\ImageWidth(),
					
				)
			)
		));
	}
}
