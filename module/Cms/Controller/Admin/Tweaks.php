<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Controller\Admin;

use Cms\Controller\Admin\AbstractController;
use Krystal\Validate\Pattern;

final class Tweaks extends AbstractController
{
	/**
	 * Shows tweak form
	 * 
	 * @return string
	 */
	public function indexAction()
	{
		$this->loadPlugins();

		return $this->view->render('tweaks', array(
			'title' => 'Tweaks',
			'config' => $this->getConfigManager()->getEntity(),
		));
	}

	/**
	 * Saves tweaks
	 * 
	 * @return string
	 */
	public function saveAction()
	{
		$formValidator = $this->getValidator($this->request->getPost());

		if ($formValidator->isValid()) {

			$this->getConfigManager()->write($this->request->getPost());
			$this->flashMessenger->set('success', 'System settings have been updated successfully');

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
				   ->load($this->getWysiwygPluginName())
				   ->appendScript($this->getWithAssetPath('/admin/tweaks.js'));
		
		$this->view->getBreadcrumbBag()->add(array(
			array(
				'link' => '#',
				'name' => 'Tweaks'
			)
		));
	}

	/**
	 * Returns configuration manager
	 * 
	 * @return \Admin\Service\ConfigManager
	 */
	private function getConfigManager()
	{
		return $this->getCmsModule()->getService('configManager');
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
					'notification_email' => new Pattern\Email()
				)
			)
		));
	}
}
