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

use Krystal\Db\Filter\FilterableServiceInterface;
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
		return $this->getService('Cms', 'userManager');
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
	 * Calls filter() method in provided service
	 * 
	 * @param \Krystal\Db\Filter\FilterableServiceInterface $service
	 * @param string $route
	 * @return array
	 */
	final protected function getFilter(FilterableServiceInterface $service, $route)
	{
		return $this->getQueryFilter($service, $this->getSharedPerPageCount(), $route);
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
	 * {@inheritDoc}
	 */
	protected function bootstrap()
	{
		$this->validateRequest();

		$this->view->getBlockBag()->setBlocksDir($this->getWithViewPath('/blocks/', 'Cms', 'admin'))
								  ->addStaticBlock($this->getViewPath('Menu', 'admin'), 'menu-widget');

		$this->view->setLayout('__layout__', 'Cms');
		$this->loadAllShared();
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
			$provider = new PerPageCount($this->sessionBag, 'admin.pgc', 5);
		}

		return $provider;
	}

	/**
	 * Checks for role
	 */
	private function roleCheck($mode)
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
	private function loadAllShared()
	{
		// Required services
		$mode = $this->getService('Cms', 'mode');
		$languageManager = $this->getService('Cms', 'languageManager');
		$this->roleCheck($mode);
		$this->extendedMode = !$mode->isSimple();
		
		$contentLanguages = $languageManager->fetchAllPublished();

		// If no published languages for now then, die
		if ($this->languageCheck === true && count($contentLanguages) == 0) {
			die($this->translator->translate("Error: You must have at least one published system's language for a content"));
		}

		// Shared variables for all templates
		$this->view->addVariables(array(
			'appConfig' => $this->appConfig,
			'extendedMode' => !$mode->isSimple(),
			'mode' => $mode,
			'paramBag' => $this->paramBag,
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

		$this->tweakInternalServices();
	}

	/**
	 * Validates the request
	 * 
	 * @return void
	 */
	private function validateRequest()
	{
		// Must support only POST and GET requests
		if (!$this->request->isIntended()) {
			$this->response->setStatusCode(400);
			die('Invalid request');
		}

		// Do validate only for POST requests for now
		if ($this->request->isPost()) {

			// This is general for all forms
			$valid = $this->csrfProtector->isValid($this->request->getMetaCsrfToken());

			if (!$valid) {
				$this->response->setStatusCode(400);
				die('Invalid CSRF token');
			}
		}
	}

	/**
	 * Tweaks CMS internal services
	 * 
	 * @return void
	 */
	private function tweakInternalServices()
	{
		// Do tweak in case user is logged in
		if ($this->getAuthService()->isLoggedIn()) {
			$userId = $this->getAuthService()->getId();

			// Grab administration configuration entity
			$config = $this->getService('Cms', 'configManager')->getEntity();

			$historyManager = $this->getService('Cms', 'historyManager');
			$historyManager->setUserId($userId);
			$historyManager->setEnabled((bool) $config->getKeepTrack());

			$this->getService('Cms', 'notepadManager')->setUserId($userId);
		}
	}

}
