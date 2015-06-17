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

use Cms\Controller\Admin\AbstractController;
use Krystal\Validate\Pattern;

final class Auth extends AbstractController
{
	/**
	 * {@inheritDoc}
	 */
	protected $authActive = false;

	/**
	 * Shows login form or redirects to dashboard if already logged-in
	 * 
	 * @return string
	 */
	public function indexAction()
	{
		// If user is logged in already, then he should be redirected to a dashboard
		if ($this->getAuthService()->isLoggedIn()) {
			$this->response->redirect('/admin');
		} else {
			$this->view->getPluginBag()->appendStylesheet($this->getWithAssetPath('/css/login.css'))
									   ->appendScript($this->getWithAssetPath('/admin/login.js'));

			return $this->view->disableLayout()->render('login');
		}
	}

	/**
	 * Does log-in
	 * 
	 * @return string
	 */
	public function loginAction()
	{
		$formValidator = $this->getValidator($this->request->getPost());

		if ($formValidator->isValid()) {

			// Grab request data
			$login = $this->request->getPost('login');
			$password = $this->request->getPost('password');
			$remember = (bool) $this->request->getPost('remember');

			if ($this->getAuthService()->authenticate($login, $password, $remember)) {
				return '1';
			} else {
				return $this->translator->translate('Invalid login or password');
			}

		} else {

			return $formValidator->getErrors();
		}
	}

	/**
	 * Does log out
	 * 
	 * @return string
	 */
	public function logoutAction()
	{
		$this->getAuthService()->logout();
		$this->response->redirect('/admin/login');
	}

	/**
	 * Returns prepared form validator
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
					'login' => new Pattern\Login(),
					'password' => new Pattern\Password()
				)
			)
		));
	}
}
