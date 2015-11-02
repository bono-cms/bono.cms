<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Controller\Admin\Users;

use Cms\Controller\Admin\AbstractController;
use Krystal\Validate\Pattern;

abstract class AbstractUser extends AbstractController
{
    /**
     * Returns configured validator instance
     * 
     * @param array $input Raw input data
     * @param boolean $edit Whether we're on edit form
     * @return \Krystal\Validate\ValidatorChain
     */
    final protected function getValidator(array $input, $edit = false)
    {
        return $this->validatorFactory->build(array(
            'input' => array(
                'source' => $input,
                'definition' => array(
                    'login' => new Pattern\Login(),
                    'password' => new Pattern\Password(),
                    'password_confirm' => new Pattern\PasswordConfirmation($input['password'], array('required' => !$edit)),
                    'email' => new Pattern\Email(),
                    'name' => new Pattern\Name()
                )
            )
        ));
    }

    /**
     * Returns user manager
     * 
     * @return \Cms\Service\UserManager
     */
    final protected function getUserManager()
    {
        return $this->getService('Cms', 'userManager');
    }

    /**
     * Returns breadcrumbs for current user
     * 
     * @param string $title Tile for the last breadcrumb
     * @return array
     */
    private function getBreadcrumbs($title)
    {
        $breadcrumbs = array();

        // Only developers can see the link to the grid
        if ($this->getAuthService()->getRole() == 'dev') {
            array_unshift($breadcrumbs, array(
                'link' => 'Cms:Admin:Users:Browser@indexAction',
                'name' => 'Users'
            ));
        }

        array_push($breadcrumbs, array(
            'link' => '#',
            'name' => $title
        ));

        return $breadcrumbs;
    }

    /**
     * Returns shared variables
     * 
     * @param array $overrides
     * @return array
     */
    final protected function getWithSharedVars(array $overrides)
    {
        $this->view->getBreadcrumbBag()->add($this->getBreadcrumbs($overrides['title']));

        $vars = array(
            'roles' => array(
                'user'  => 'User',
                'dev'   => 'Developer',
                'guest' => 'Guest'
            )
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
        $this->view->getPluginBag()
                   ->appendScript('@Cms/admin/users/user.form.js');
    }

    /**
     * Returns template path
     * 
     * @return string
     */
    final protected function getTemplatePath()
    {
        return 'users/user.form';
    }
}
