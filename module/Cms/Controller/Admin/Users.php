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

use Krystal\Stdlib\VirtualEntity;
use Krystal\Validation\Validator;

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
                       ->addOne('Users', 'Cms:Admin:Users@indexAction');
        }

        $this->view->getBreadcrumbBag()
                   ->addOne($title);

        return $this->view->render('users/user.form', [
            'user' => $user,
            'roles' => [
                'user' => 'User',
                'dev' => 'Developer',
                'guest' => 'Guest'
            ]
        ]);
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
            $this->formAttribute->setOldAttributes([
                'email' => $user->getEmail(),
                'login' => $user->getLogin()
            ]);

            return $this->createForm($user, $this->translator->translate('Edit the user "%s"', $user->getLogin()));
        } else {
            return false;
        }
    }

    /**
     * Renders the grid
     * 
     * @return string
     */
    public function indexAction()
    {
        $this->view->getBreadcrumbBag()
                   ->addOne('Users');

        return $this->view->render('users/index', [
            'users' => $this->getUserManager()->fetchAll(),
            'currentUserId' => $this->getAuthService()->getId() // Current ID of logged in user
        ]);
    }

    /**
     * Removes selected user
     * 
     * @param string $id
     * @return string
     */
    public function deleteAction($id)
    {
        // Prevent removing oneself
        if ($this->getAuthService()->getId() != $id) {
            $service = $this->getModuleService('userManager');
            $service->deleteById($id);

            $this->flashBag->set('success', 'Selected element has been removed successfully');

            return $this->json([
                'refresh' => true
            ]);
        }
    }

    /**
     * Delete all users but current logged in one
     * 
     * @return mixed
     */
    public function wipeAction()
    {
        // ID of current logged in user
        $id = $this->getAuthService()->getId();

        // Try removing...
        if ($this->getModuleService('userManager')->wipe($id)) {
            $this->flashBag->set('success', 'All users except yourself have been removed permanently');

            return $this->json([
                'refresh' => true
            ]);
        }
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
        $isEdit = !empty($input['id']);

        $emailChanged = $isEdit && $this->formAttribute->hasChanged('email');
        $loginChanged = $isEdit && $this->formAttribute->hasChanged('login');

        $validator = new Validator($this->request->getPost());

        $validator->field('user.login', 'Login')
                  ->required(null, !$isEdit || ($loginChanged && $this->getUserManager()->loginExists($input['login'])));

        $validator->field('user.password', 'Password')
                  ->required(null, !$isEdit);

        $validator->field('user.password_confirm', 'Confirm Password')
                  ->required(null, !$isEdit)
                  ->addRule('identity', null, ['value' => $input['password']]);

        $validator->field('user.email', 'Email')
                  ->required(null, !$isEdit || ($emailChanged && $this->getUserManager()->emailExists($input['email'])))
                  ->addRule('email');

        $validator->field('user.name', 'Name')
                  ->required();

        if ($validator->isPassed()) {
            $service = $this->getModuleService('userManager');

            if (!empty($input['id'])) {
                if ($service->update($input)) {
                    $this->flashBag->set('success', 'The element has been updated successfully');

                    return $this->json([
                        'refresh' => true
                    ]);
                }

            } else {
                if ($service->add($input)) {
                    $this->flashBag->set('success', 'The element has been created successfully');

                    return $this->json([
                        'redirect' => $this->createUrl('Cms:Admin:Users@editAction', [$service->getLastId()]),
                    ]);
                }
            }

        } else {
            return $this->json([
                'errors' => $validator->getErrors()
            ]);
        }
    }
}
