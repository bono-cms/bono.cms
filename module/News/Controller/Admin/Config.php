<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace News\Controller\Admin;

use Krystal\Validate\Pattern;

final class Config extends AbstractAdminController
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
	 * Save options
	 * 
	 * @return string
	 */
	public function saveAction()
	{
		$formValidator = $this->getValidator($this->request->getPost());

		if ($formValidator->isValid()) {

			if ($this->getConfigManager()->write($this->request->getPost())) {
				$this->flashMessenger->set('success', 'Configuration has been updated successfully');
				return '1';
			}

		} else {
			return $formValidator->getErrors();
		}
	}

	/**
	 * Returns prepared and configured form validation
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
					'time_format_in_list' => new Pattern\DateFormat(),
					'time_format_in_post' => new Pattern\DateFormat(),
					'per_page_count' => new Pattern\PerPageCount(),
					'block_per_page_count' => new Pattern\PerPageCount(),
					'cover_quality' => new Pattern\ImageQuality(),
					'cover_height' => new Pattern\ImageHeight(),
					'cover_width' => new Pattern\ImageWidth(),
					'thumb_height' => new Pattern\ImageHeight(),
					'thumb_width' => new Pattern\ImageWidth()
				)
			)
		));
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
				'link' => 'News:Admin:Browser@indexAction',
				'name' => 'News'
			),
			array(
				'link' => '#',
				'name' => 'Configuration'
			)
		));
	}
}
