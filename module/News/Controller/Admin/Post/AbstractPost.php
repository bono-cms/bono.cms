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

use Cms\Controller\Admin\AbstractController;
use Krystal\Validate\Pattern;

abstract class AbstractPost extends AbstractController
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
	final protected function getSharedVars(array $overrides)
	{
		$news = $this->moduleManager->getModule('News');
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
			'categories' => $news->getService('categoryManager')->fetchAll(),
		);

		return array_replace_recursive($vars, $overrides);
	}

	/**
	 * Returns post manager
	 * 
	 * @return \News\Post\PostManager
	 */
	final protected function getPostManager()
	{
		return $this->moduleManager->getModule('News')->getService('postManager');
	}
}
