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
use Krystal\Validate\Pattern;

final class Languages extends AbstractController
{
    /**
     * {@inheritDoc}
     */
    protected function bootstrap()
    {
        $this->disableLanguageCheck();
        parent::bootstrap();
    }

    /**
     * Returns language manager
     * 
     * @return \Admin\Service\LanguageManager
     */
    private function getLanguageManager()
    {
        return $this->getService('Cms', 'languageManager');
    }

    /**
     * Creates a form
     * 
     * @param \Krystal\Stdlib\VirtualEntity $language
     * @param string $title
     * @return string
     */
    private function createForm(VirtualEntity $language, $title)
    {
        // Load view plugins
        $this->view->getPluginBag()
                   ->appendScript('@Cms/admin/language/language.form.js');

        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Languages', 'Cms:Admin:Languages@gridAction')
                                       ->addOne($title);

        return $this->view->render('languages/language.form', array(
            'countries' => $this->getModuleService('languageManager')->getCountries(),
            'language' => $language
        ));
    }

    /**
     * Renders empty form
     * 
     * @return string
     */
    public function addAction()
    {
        $language = new VirtualEntity();
        $language->setPublished(true)
                 ->setOrder(0);

        return $this->createForm($language, 'Add a language');
    }

    /**
     * Renders edit form
     * 
     * @param string $id
     * @return string
     */
    public function editAction($id)
    {
        $language = $this->getModuleService('languageManager')->fetchById($id);

        if ($language !== false) {
            return $this->createForm($language, 'Edit the language');
        } else {
            return false;
        }
    }

    /**
     * Deletes a language by its associated id
     * 
     * @return string
     */
    public function deleteAction()
    {
        return $this->invokeRemoval('languageManager');
    }

    /**
     * Changes a language
     * 
     * @return string
     */
    public function changeAction()
    {
        if ($this->request->hasPost('id') && $this->request->isAjax()) {
            $id = $this->request->getPost('id');

            $this->getLanguageManager()->setCurrentId($id);
            return '1';
        }
    }

    /**
     * Saves the data
     * 
     * @return string
     */
    public function tweakAction()
    {
        if ($this->request->hasPost('default', 'published', 'order')) {

            // Grab request data
            $default    = $this->request->getPost('default');
            $published  = $this->request->getPost('published');
            $orders     = $this->request->getPost('order');

            // Grab a service
            $languageManager = $this->getLanguageManager();

            // Mark language id as a default
            $languageManager->makeDefault($default);
            $languageManager->updatePublished($published);
            $languageManager->updateOrders($orders);

            $this->flashBag->set('success', 'Settings have been updated successfully');
            return '1';
        }
    }

    /**
     * Renders the grid
     * 
     * @return string
     */
    public function gridAction()
    {
        $this->view->getPluginBag()
                   ->appendScript('@Cms/admin/language/browser.js');

        $this->view->getBreadcrumbBag()
                   ->addOne('Languages');

        return $this->view->render('languages/browser', array(
            // We can't define an array which is called "languages", because that name is already in template's global scope
            'langs' => $this->getLanguageManager()->fetchAll()
        ));
    }

    /**
     * Persists a language
     * 
     * @return string
     */
    public function saveAction()
    {
        $input = $this->request->getPost('language');

        return $this->invokeSave('languageManager', $input['id'], $input, array(
            'input' => array(
                'source' => $input,
                'definition' => array(
                    'name' => new Pattern\Name(),
                    'code' => new Pattern\LanguageCode(),
                    'order' => new Pattern\Order()
                )
            )
        ));
    }
}
