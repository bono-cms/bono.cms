<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Reviews\Controller\Admin;

use Cms\Controller\Admin\AbstractController;
use Krystal\Validate\Pattern;

abstract class AbstractReview extends AbstractController
{
	/**
	 * Returns configured form validator
	 * 
	 * @param array $input Raw input data
	 * @return \Krystal\Validate\ValidatorChain
	 */
	final protected function getValidator(array $input)
	{
		return $this->validatorFactory->build(array(
			'input' => array(
				'source' => $input,
				'definition' => array(
					'name' => new Pattern\Name(),
					'email' => new Pattern\Email(),
					'content' => new Pattern\Content(),
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
		return 'review.form';
	}

	/**
	 * Loads shared plug-ins
	 * 
	 * @return void
	 */
	final protected function loadSharedPlugins()
	{
		$this->view->getPluginBag()
				   ->appendScript($this->getWithAssetPath('/admin/review.form.js'))
				   ->load(array($this->getWysiwygPluginName(), 'datepicker'));
	}

	/**
	 * Returns shared variables for Edit and Add controllers
	 * 
	 * @param array $overrides
	 * @return array
	 */
	final protected function getSharedVars(array $overrides)
	{
		$this->view->getBreadcrumbBag()->add(array(
			array(
				'name' => 'Reviews',
				'link' => 'Reviews:Admin:Browser@indexAction'
			),
			array(
				'name' => $overrides['title'],
				'link' => '#'
			)
		));

		$vars = array(
			'dateFormat' => $this->getReviewsManager()->getTimeFormat(),
		);
		
		return array_replace_recursive($vars, $overrides);
	}

	/**
	 * Returns request container
	 * 
	 * @return array
	 */
	final protected function getContainer()
	{
		return array_merge(array('ip' => $this->request->getClientIp()), $this->request->getPost());
	}

	/**
	 * Returns review manager
	 * 
	 * @return \Reviews\Service\ReviewsManager
	 */
	final protected function getReviewsManager()
	{
		return $this->moduleManager->getModule('Reviews')->getService('reviewsManager');
	}
}
