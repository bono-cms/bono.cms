<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace News\Controller\Admin\Post;

use News\Controller\Admin\AbstractAdminController;
use Krystal\Validate\Pattern;

abstract class AbstractPost extends AbstractAdminController
{
	/**
	 * Returns configured form validator
	 * 
	 * @param array $post Raw post data
	 * @param array $files Files
	 * @return \Krystal\Validate\ValidatorChain
	 */
	final protected function getValidator(array $post, array $files)
	{
		return $this->validatorFactory->build(array(
			'input' => array(
				'source' => $post,
				'definition' => array(
					'title' => new Pattern\Title(),
					'intro' => new Pattern\IntroText(),
					'full'	=> new Pattern\FullText(),
					'date' => new Pattern\DateFormat('m/d/Y')
				)
			),

			'file' => array(
				'source' => $files,
				'definition' => array(
					'file' => new Pattern\ImageFile(array(
						'required' => false
					))
				)
			)
		));
	}

	/**
	 * Returns template path
	 * 
	 * @return string
	 */
	final protected function getTemplatePath()
	{
		return 'post.form';
	}

	/**
	 * Loads shared plugins
	 * 
	 * @return void
	 */
	final protected function loadSharedPlugins()
	{
		$this->view->getPluginBag()
				   ->appendScript($this->getWithAssetPath('/admin/post.form.js'))
				   ->load(array($this->getWysiwygPluginName(), 'datepicker'));
	}

	/**
	 * Returns shared variables
	 * 
	 * @param array $overrides
	 * @return array
	 */
	final protected function getWithSharedVars(array $overrides)
	{
		$this->view->getBreadcrumbBag()->add(array(
			array(
				'link' => 'News:Admin:Browser@indexAction',
				'name' => 'News'
			),
			array(
				'link' => '#',
				'name' => $overrides['title']
			)
		));

		$vars = array(
			'categories' => $this->getCategoryManager()->fetchList(),
		);

		return array_replace_recursive($vars, $overrides);
	}
}
