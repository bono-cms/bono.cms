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

use Krystal\Application\Controller\AbstractAuthAwareController;
use Krystal\Form\Providers\PerPageCount;
use Cms\View\RoleHelper;

abstract class AbstractController extends AbstractAuthAwareController
{
	/**
	 * Indicates whether user browses in extended mode
	 * 
	 * @var boolean
	 */
	protected $extendedMode;

	/**
	 * Defines whether language checking is invoked
	 * 
	 * @var boolean
	 */
	protected $languageCheck = true;

	/**
	 * {@inheritDoc}
	 */
	protected function getAuthService()
	{
		return $this->getCmsModule()->getService('userManager');
	}

	/**
	 * {@inheritDoc}
	 */
	protected function onSuccess()
	{
		$as = $this->getAuthService();

		$role = new RoleHelper($as->getRole());
		$role->setId($as->getId());

		$this->view->addVariable('role', $role);
	}

	/**
	 * {@inheritDoc}
	 */
	protected function onFailure()
	{
		$this->response->redirect('/admin/login');
	}

	/**
	 * {@inheritDoc}
	 */
	protected function onNoRights()
	{
		die($this->translator->translate('You do not have enough rights to perform this action!'));
	}

	/**
	 * Disables language checking
	 * 
	 * @return void
	 */
	final protected function disableLanguageCheck()
	{
		$this->languageCheck = false;
	}

	/**
	 * Loads menu widget on demand
	 * 
	 * @return void
	 */
	final protected function loadMenuWidget()
	{
		if ($this->moduleManager->isLoaded('Menu')) {
			$this->view->getPluginBag()
					   ->appendScript($this->getWithAssetPath('/admin/menu.widget.js', 'Menu'));
		}
	}

	/**
	 * Returns shared per page count
	 * 
	 * @return integer
	 */
	final protected function getSharedPerPageCount()
	{
		return $this->getPerPageCountProvider()->getPerPageCount();
	}

	/**
	 * Returns shared WYSIWYG name
	 * 
	 * @return string
	 */
	final protected function getWysiwygPluginName()
	{
		return $this->paramBag->get('wysiwyg');
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getResolverThemeName()
	{
		return 'admin';
	}

	/**
	 * Returns CMS module
	 * 
	 * @return \Cms\Module
	 */
	final protected function getCmsModule()
	{
		return $this->moduleManager->getModule('Cms');
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function bootstrap()
	{
		$this->view->getBlockBag()->setBlocksDir($this->appConfig->getModulesDir() . '/Cms/View/Template/admin/blocks/')
								  ->addStaticBlock($this->appConfig->getModulesDir() . '/Menu/View/Template/admin', 'menu-widget');

		$this->view->setLayout('_layout', 'Cms');
		$this->loadAllShared();
	}

	/**
	 * Checks for role
	 */
	protected function roleCheck($mode)
	{
		// Regular users that have no extra privileges
		$regular = array('guest', 'user');

		if (in_array($this->getAuthService()->getRole(), $regular)) {
			$mode->setSimple();
		}
	}

	/**
	 * Loads all shared data which is required for all descendants
	 * 
	 * @return void
	 */
	final protected function loadAllShared()
	{
		$cms = $this->getCmsModule();

		// Required services
		$mode = $cms->getService('mode');
		$languageManager = $cms->getService('languageManager');
		$this->roleCheck($mode);
		$this->extendedMode = !$mode->isSimple();
		
		$contentLanguages = $languageManager->fetchAllPublished();

		// If no published languages for now then, die
		if ($this->languageCheck === true && count($contentLanguages) == 0) {
			die($this->translator->translate("Error: You must have at least one published system's language for a content"));
		}

		$this->view->addVariables(array(
			// Application's language itself
			'appConfig' => $this->appConfig,
			
			'extendedMode' => !$mode->isSimple(),
			'mode' => $mode,
			
			'paramBag' => $this->paramBag,
			
			//'site' => $this->paramBag->get('site'),
			
			// Content languages
			'languages' => $contentLanguages,
			'currentLanguage' => $languageManager->fetchByCurrentId(),
			'perPageCounts' => $this->getPerPageCountProvider()->getPerPageCountValues(),
		));

		$this->view->getPluginBag()->load(array(
			'jquery',
			'bootstrap.core',
			'bootstrap.cosmo',
			'famfam-flag',
			'admin',
			'to-top'
		));

		$this->tweak();
	}

	/**
	 * Tweaks history manager
	 * 
	 * @return void
	 */
	protected function tweak()
	{
		// Do tweak in case user is logged in
		if ($this->sessionBag->has('user_id')) {
			$userId = $this->sessionBag->get('user_id');
			
			$cms = $this->getCmsModule();
			
			// Grab administration config entity
			$config = $cms->getService('configManager')->getEntity();
			
			$historyManager = $cms->getService('historyManager');
			$historyManager->setUserId($userId);
			$historyManager->setEnabled((bool) $config->getKeepTrack());

			$cms->getService('notepadManager')->setUserId($userId);
		}
	}

	/**
	 * Returns per page count provider
	 * 
	 * @return \Krystal\Form\Provider\PerPageCount
	 */
	final protected function getPerPageCountProvider()
	{
		static $provider = null;

		if (is_null($provider)) {
			$provider = new PerPageCount('admin.pgc', 5);
		}

		return $provider;
	}
}
