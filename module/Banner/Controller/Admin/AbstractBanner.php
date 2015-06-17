<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Banner\Controller\Admin;

use Cms\Controller\Admin\AbstractController;
use Krystal\Validate\Pattern;

abstract class AbstractBanner extends AbstractController
{
	/**
	 * Returns prepared validator
	 * 
	 * @param array $post Raw post data
	 * @param array $files Form files
	 * @param boolean $edit Whether this validator is meant for edit form
	 * @return \Krystal\Validate\ValidatorChain
	 */
	final protected function getValidator(array $post, array $files, $edit = false)
	{
		return $this->validatorFactory->build(array(
			'input' => array(
				'source' => $post,
				'definition' => array(
					'name' => new Pattern\Name(),
					'link' => new Pattern\Url(),
				)
			),
			'files' => array(
				'source' => $files,
				'definition' => array(
					'file' => new Pattern\File(array(
						'required' => !$edit
					))
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
		$this->view->getPluginBag()
				   ->appendScript($this->getWithAssetPath('/admin/banner.form.js'));
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
				'link' => 'Banner:Admin:Browser@indexAction',
				'name' => 'Banner'
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
	 * Just returns banner manager
	 * 
	 * @return \Banner\Service\BannerManager
	 */
	final protected function getBannerManager()
	{
		return $this->moduleManager->getModule('Banner')->getService('bannerManager');
	}

	/**
	 * Returns template path
	 * 
	 * @return string
	 */
	final protected function getTemplatePath()
	{
		return 'banner.form';
	}
}
