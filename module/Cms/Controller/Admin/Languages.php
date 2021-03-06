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
     * Renders the grid
     * 
     * @return string
     */
    public function indexAction()
    {
        $this->view->getBreadcrumbBag()
                   ->addOne('Languages');

        return $this->view->render('languages/index', array(
            // We can't define an array which is called "languages", because that name is already in template's global scope
            'langs' => $this->getService('Cms', 'languageManager')->fetchAll(false)
        ));
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
                   ->appendScript('@Cms/admin/language.form.js');

        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Languages', 'Cms:Admin:Languages@indexAction')
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
            return $this->createForm($language, $this->translator->translate('Edit the language "%s"', $language->getName()));
        } else {
            return false;
        }
    }

    /**
     * Deletes a language by its associated id
     * 
     * @param string $id
     * @return string
     */
    public function deleteAction($id)
    {
        $service = $this->getModuleService('languageManager');
        $service->deleteById($id);

        $this->flashBag->set('success', 'Selected element has been removed successfully');
        return '1';
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

            $this->getService('Cms', 'languageManager')->setCurrentId($id);
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
            $languageManager = $this->getService('Cms', 'languageManager');

            // Mark language id as a default
            $languageManager->makeDefault($default);
            $languageManager->updatePublished($published);
            $languageManager->updateOrders($orders);

            // Extra check. If we have only one published language, make sure its id is set
            if ($languageManager->getCount(true) == 1) {
                $id = $languageManager->getDefaultId();
                $languageManager->setCurrentId($id);
            }

            $this->flashBag->set('success', 'Settings have been updated successfully');
            return '1';
        }
    }

    /**
     * Persists a language
     * 
     * @return string
     */
    public function saveAction()
    {
        $input = $this->request->getPost('language');

        $formValidator = $this->createValidator(array(
            'input' => array(
                'source' => $input,
                'definition' => array(
                    'name' => new Pattern\Name(),
                    'code' => new Pattern\LanguageCode(),
                    'order' => new Pattern\Order()
                )
            )
        ));

        if ($formValidator->isValid()) {
            $service = $this->getModuleService('languageManager');

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
