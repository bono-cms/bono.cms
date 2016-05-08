<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
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
            $this->redirectToRoute('Cms:Admin:Dashboard@indexAction');

        } else {
            $this->view->getPluginBag()->appendStylesheet('@Cms/css/login.css')
                                       ->appendScript('@Cms/admin/login.js');

            $vars = array(
                'captcha' => $this->authAttemptLimit->isReachedLimit(),
                'login' => $this->authAttemptLimit->getLastLogin()
            );

            return $this->view->disableLayout()
                              ->render('login', $vars);
        }
    }

    /**
     * Performs a login 
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
                $this->authAttemptLimit->reset();
                return '1';
            } else {
                $this->authAttemptLimit->incrementFailAttempt()
                                       ->persistLastLogin($login);

                if ($this->authAttemptLimit->isReachedLimit()) {
                    return '-1';
                }

                // Return raw string indicating failure
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
        $this->redirectToRoute('Cms:Admin:Auth@indexAction');
    }

    /**
     * Returns prepared form validator
     * 
     * @param array $input Raw input data
     * @return \Krystal\Validate\ValidatorChain
     */
    private function getValidator(array $input)
    {
        // Default rules
        $rules = array(
            'login' => new Pattern\Login(),
            'password' => new Pattern\Password()
        );

        // Append CAPTCHA rule in case received more than defined failure attempt
        if ($this->authAttemptLimit->isReachedLimit()) {
            $rules['captcha'] = new Pattern\Captcha($this->captcha);
        }

        return $this->createValidator(array(
            'input' => array(
                'source' => $input,
                'definition' => $rules
            )
        ));
    }
}
