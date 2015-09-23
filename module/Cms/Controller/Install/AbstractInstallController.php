<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Controller\Install;

use Krystal\Application\Controller\AbstractController;
use Krystal\Application\View\Resolver\Module as Resolver;

abstract class AbstractInstallController extends AbstractController
{
	/**
	 * {@inheritDoc}
	 */
	protected function getResolverThemeName()
	{
		return 'install';
	}

	/**
	 * {@inheritDoc}
	 */
	final protected function bootstrap()
	{
		$this->view->getBlockBag()->setBlocksDir($this->appConfig->getModulesDir() . '/Cms/View/Template/install/blocks/');
		$this->view->setLayout('layout', 'Cms');

		$this->view->getPluginBag()->load(array(
			'jquery',
			'bootstrap.core',
			'bootstrap.cosmo',
			'admin'
		));
	}

	/**
	 * Connects and creates required tables
	 * 
	 * @return boolean
	 */
	final protected function processAll()
	{
		//@TODO
		return false;
	}

	/**
	 * Returns prepared form validator
	 * 
	 * @param array $input
	 * @return \Krystal\Validate\ValidatorChain
	 */
	final protected function getValidator(array $input)
	{
		return $this->validatorFactory->build(array(
			'input' => array(
				'source' => $input,
				'definition' => array(
					'host' => array(
						'required' => true,
						'rules' => array(
							'NotEmpty' => array(
								'message' => 'Database host cannot be empty'
							)
						)
					),
					
					'name' => array(
						'required' => true,
						'rules' => array(
							'NotEmpty' => array(
								'message' => 'Database name cannot be empty'
							)
						)
					),
					
					'user' => array(
						'required' => true,
						'rules' => array(
							'NotEmpty' => array(
								'message' => 'Username cannot be empty'
							)
						)
					),
					
					'password' => array(
						'required' => false,
						'rules' => array()
					)
				)
			)
		));
	}	
}
