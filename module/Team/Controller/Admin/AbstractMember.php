<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Team\Controller\Admin;

use Cms\Controller\Admin\AbstractController;
use Krystal\Validate\Pattern;

abstract class AbstractMember extends AbstractController
{
	/**
	 * Returns configured validator's instance
	 * 
	 * @param array $input Raw input data
	 * @param array $files
	 * @param boolean $edit
	 * @return \Krystal\Validate\ValidatorChain
	 */
	final protected function getValidator(array $input, array $files, $edit = false)
	{
		return $this->validatorFactory->build(array(
			'input' => array(
				'source' => $input,
				'definition' => array(
					'name' => new Pattern\Name(),
					'description' => new Pattern\Description()
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
	final protected function getSharedVars(array $overrides)
	{
		$this->view->getBreadcrumbBag()->add(array(
			array(
				'name' => 'Team',
				'link' => 'Team:Admin:Browser@indexAction'
			),

			array(
				'name' => $overrides['title'],
				'link' => '#'
			)
		));

		$vars = array(
		);

		return array_replace_recursive($vars, $overrides);
	}

	/**
	 * Loads shared plugins
	 * 
	 * @return void
	 */
	final protected function loadSharedPlugins()
	{
		$this->view->getPluginBag()->appendScript($this->getWithAssetPath('/admin/member.form.js'))
				   ->load(array($this->getWysiwygPluginName(), 'preview'));
	}

	/**
	 * Returns template path
	 * 
	 * @return string
	 */
	final protected function getTemplatePath()
	{
		return 'member.form';
	}

	/**
	 * Returns team manager
	 * 
	 * @return \Team\Service\TeamManager
	 */
	final protected function getTeamManager()
	{
		return $this->moduleManager->getModule('Team')->getService('teamManager');
	}
}
