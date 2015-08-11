<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Slider\Controller\Admin\Image;

use Cms\Controller\Admin\AbstractController;
use Krystal\Validate\Pattern;

abstract class AbstractImage extends AbstractController
{
	/**
	 * Returns prepared form validator
	 * 
	 * @param array $post
	 * @param array $files
	 * @param boolean $edit
	 * @return \Krystal\Validate\ValidatorChain
	 */
	final protected function getValidator(array $post, array $files, $edit = false)
	{
		return $this->validatorFactory->build(array(
			'input' => array(
				'source' => $post,
				'definition' => array(
					'order' => new Pattern\Order(),
					'link' => new Pattern\Url()
				)
			),
			'file' => array(
				'source' => $files,
				'definition' => array(
					'file' => new Pattern\ImageFile(array(
						'required' => !$edit
					))
				)
			)
		));
	}

	/**
	 * Returns shared variables for Add and Edit controllers
	 * 
	 * @param array $overrides
	 * @return array
	 */
	final protected function getWithSharedVars(array $overrides)
	{
		$this->view->getBreadcrumbBag()->add(array(
			array(
				'name' => 'Slider',
				'link' => 'Slider:Admin:Browser@indexAction'
			),
			array(
				'name' => $overrides['title'],
				'link' => '#'
			)
		));

		$vars = array(
			'categories' => $this->getModuleService('categoryManager')->fetchList()
		);

		return array_replace_recursive($vars, $overrides);
	}

	/**
	 * Loads shared view plugins
	 * 
	 * @param boolean $preview Whether to load preview plugin
	 * @return void
	 */
	final protected function loadSharedPlugins($preview = true)
	{
		$pb = $this->view->getPluginBag();

		if ($preview) {
			$pb->load('preview');
		}

		$pb->appendScript($this->getWithAssetPath('/admin/image.form.js'));
	}

	/**
	 * Returns image manager
	 * 
	 * @return \Slider\Service\ImageManager
	 */
	final protected function getImageManager()
	{
		return $this->getModuleService('imageManager');
	}

	/**
	 * Returns template path
	 * 
	 * @return string
	 */
	final protected function getTemplatePath()
	{
		return 'image.form';
	}
}
