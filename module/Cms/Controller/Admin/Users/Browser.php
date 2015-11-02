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

final class Browser extends AbstractController
{
    /**
     * Shows a table
     * 
     * @return string
     */
    public function indexAction()
    {
        $this->loadPlugins();

        return $this->view->render('users/browser', array(
            'users' => $this->getService('Cms', 'userManager')->fetchAll(),
            'title' => 'Users',
        ));
    }

    /**
     * Loads required plugins for view
     * 
     * @return void
     */
    private function loadPlugins()
    {
        $this->view->getPluginBag()
                   ->appendScript('@Cms/admin/users/browser.js');

        $this->view->getBreadcrumbBag()->addOne('Users');
    }

    /**
     * Removes selected user
     * 
     * @return string
     */
    public function deleteAction()
    {
        if ($this->request->hasPost('id')) {
            $id = $this->request->getPost('id');

            if ($this->getService('Cms', 'userManager')->deleteById($id)) {
                $this->flashBag->set('success', 'Selected user has been removed successfully');
                return '1';
            }
        }
    }
}
