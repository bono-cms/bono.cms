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

use Krystal\Validate\Pattern;
use Krystal\Stdlib\VirtualEntity;

final class Users extends AbstractController
{
    /**
     * Returns user manager
     * 
     * @return \Cms\Service\UserManager
     */
    private function getUserManager()
    {
        return $this->getService('Cms', 'userManager');
    }

    /**
     * Creates a form
     * 
     * @param \Krystal\Stdlib\VirtualEntity $user
     * @param string $title
     * @return string
     */
    private function createForm(VirtualEntity $user, $title)
    {
        // Load view plugins
        $this->view->getPluginBag()
                   ->appendScript('@Cms/admin/user.form.js');

        // Only developers can see the link to the grid
        if ($this->getAuthService()->getRole() == 'dev') {
            $this->view->getBreadcrumbBag()
                       ->addOne('Users', 'Cms:Admin:Users@gridAction');
        }

        $this->view->getBreadcrumbBag()
                   ->addOne($title);

        return $this->view->render('users/user.form', array(
            'user' => $user,
            'roles' => array(
                'user' => 'User',
                'dev' => 'Developer',
                'guest' => 'Guest'
            )
        ));
    }

    /**
     * Renders empty form
     * 
     * @return string
     */
    public function addAction()
    {
        return $this->createForm(new VirtualEntity(), 'Add a user');
    }

    /**
     * Renders edit form
     * 
     * @param string $id
     * @return string
     */
    public function editAction($id)
    {
        $user = $this->getUserManager()->fetchById($id);

        if ($user !== false) {
            // Save old attributes
            $this->formAttribute->setOldAttributes(array(
                'email' => $user->getEmail(),
                'login' => $user->getLogin()
            ));

            return $this->createForm($user, 'Edit the user');
        } else {
            return false;
        }
    }

    /**
     * Renders the grid
     * 
     * @return string
     */
    public function gridAction()
    {
        $this->view->getBreadcrumbBag()
                   ->addOne('Users');

        return $this->view->render('users/browser', array(
            'users' => $this->getUserManager()->fetchAll()
        ));
    }

    /**
     * Removes selected user
     * 
     * @param string $id
     * @return string
     */
    public function deleteAction($id)
    {
        $service = $this->getModuleService('userManager');
        $service->deleteById($id);

        $this->flashBag->set('success', 'Selected element has been removed successfully');
        return '1';
    }

    /**
     * Persists a user
     * 
     * @return string
     */
    public function saveAction()
    {
        $input = $this->request->getPost('user');

        // Set new attributes
        $this->formAttribute->setNewAttributes($input);

        // Check attributes for change
        $emailChanged = $this->formAttribute->hasChanged('email') ? $this->getUserManager()->emailExists($input['email']) : false;
        $loginChanged = $this->formAttribute->hasChanged('login') ? $this->getUserManager()->loginExists($input['login']) : false;

        $formValidator = $this->createValidator(array(
            'input' => array(
                'source' => $input,
                'definition' => array(
                    'login' => new Pattern\Login($loginChanged),
                    'password' => new Pattern\Password(),
                    'password_confirm' => new Pattern\PasswordConfirmation($input['password'], array('required' => !$input['id'])),
                    'email' => new Pattern\Email($emailChanged),
                    'name' => new Pattern\Name()
                )
            )
        ));

        if ($formValidator->isValid()) {
            $service = $this->getModuleService('userManager');

            if (!empty($input['id'])) {
                if ($service->update($input)) {
                    $this->flashBag->set('success', 'The element has been updated successfully');
                    return '1';
                }

            } else {
                if ($service->add($input)) {
                    $this->flashBag->set('success', 'The element has been created successfully');
                    return $service->getLastId();
                }
            }

        } else {
            return $formValidator->getErrors();
        }
    }
}
