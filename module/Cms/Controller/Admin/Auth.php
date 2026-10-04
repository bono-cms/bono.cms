<?php

/**
 * This file is part of the Bono CMS
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Controller\Admin;

use Cms\Controller\Admin\AbstractController;

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

            $this->view->getPluginBag()
                       ->appendScript('@Cms/admin/login.js');

            $vars = [
                'captcha' => $this->authAttemptLimit->isReachedLimit(),
                'login' => $this->authAttemptLimit->getLastLogin()
            ];

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
        $validator = $this->createValidation();

        $login = $this->request->getPost('login');
        $password = $this->request->getPost('password');
        $remember = (bool) $this->request->getPost('remember');

        // Register a custom field rule to verify credentials during validation
        $validator->setFieldRule('auth', function($password, array $options, $field, array $data) use ($login, $remember) {
            return $this->getAuthService()->authenticate($login, $password, $remember);
        }, $this->translator->translate('Invalid login or password'));

        $validator->field('login')
                  ->required();

        $validator->field('password')
                  ->required()
                  ->addRule('auth');

        // Append CAPTCHA rule in case received more than defined failure attempt
        if ($this->authAttemptLimit->isReachedLimit()) {
            $validator->field('captcha')
                      ->required()
                      ->addRule('captcha', null, ['expected' => $this->captcha->getAnswer()]);
        }

        if ($validator->isPassed()) {
            $this->authAttemptLimit->reset();

            return $this->json([
                'redirect' => $this->createUrl('Cms:Admin:Dashboard@indexAction')
            ]);
        } else {
            $this->response->setStatusCode(403);

            $this->authAttemptLimit->incrementFailAttempt()
                                   ->persistLastLogin($login);

            if ($this->authAttemptLimit->isReachedLimit()) {
                return $this->json([
                    'refresh' => true
                ]);
            }

            return $this->json([
                'errors' => $validator->getErrors()
            ]);
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
}
