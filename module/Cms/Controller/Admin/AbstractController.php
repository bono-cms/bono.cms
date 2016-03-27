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

use Krystal\Db\Filter\FilterableServiceInterface;
use Krystal\Application\Controller\AbstractAuthAwareController;
use Krystal\Form\Gadget\PerPageCount;
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
     * Invokes a removal process on service object
     * That's a shared invoker for all module controllers
     * 
     * @param object $on Module service name
     * @return string
     */
    final protected function invokeRemoval($on)
    {
        $service = $this->getModuleService($on);

        // Batch removal
        if ($this->request->hasPost('toDelete') && method_exists($service, 'deleteByIds')) {
            $ids = array_keys($this->request->getPost('toDelete'));

            $service->deleteByIds($ids);
            $this->flashBag->set('success', 'Selected elements have been removed successfully');

        } else {
            $this->flashBag->set('warning', 'You should select at least one element to remove');
        }

        // Single removal
        if ($this->request->hasPost('id') && method_exists($service, 'deleteById')) {
            $id = $this->request->getPost('id');

            $service->deleteById($id);
            $this->flashBag->set('success', 'Selected element has been removed successfully');
        }

        return '1';
    }

    /**
     * Invokes save process
     * 
     * @param string $on
     * @param array $data
     * @param array $rules
     * @return string
     */
    final protected function invokeSave($on, $id, array $data, array $rules)
    {
        $formValidator = $this->createValidator($rules);

        if ($formValidator->isValid()) {
            $service = $this->getModuleService($on);

            if (!empty($id)) {
                if ($service->update($data)) {
                    $this->flashBag->set('success', 'The element has been updated successfully');
                    return '1';
                }

            } else {
                if ($service->add($data)) {
                    $this->flashBag->set('success', 'The element has been created successfully');
                    return $service->getLastId();
                }
            }

        } else {
            return $formValidator->getErrors();
        }
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
                       ->appendScript('@Menu/admin/menu.widget.js');
        }
    }

    /**
     * Returns shared per page count
     * 
     * @return integer
     */
    final protected function getSharedPerPageCount()
    {
        return $this->getPerPageCountGadget()->getPerPageCount();
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
        // Force to load specific administration language if defined
        if ($this->paramBag->exists('admin_language')) {
            $this->loadTranslations($this->paramBag->get('admin_language'));
        }

        $this->validateRequest();
        $this->view->getBlockBag()->setBlocksDir($this->getWithViewPath('/blocks/', 'Cms', 'admin'))
                                  ->addStaticBlock($this->getViewPath('Menu', 'admin'), 'menu-widget');

        $this->view->setLayout('__layout__', 'Cms');
        $this->loadAllShared();
    }

    /**
     * Returns per page count gadget
     * 
     * @return \Krystal\Form\Gadget\PerPageCount
     */
    final protected function getPerPageCountGadget()
    {
        static $gadget = null;

        if (is_null($gadget)) {
            $gadget = new PerPageCount($this->request->getCookieBag(), 'admin_pgc', 5);
        }

        return $gadget;
    }

    /**
     * Protected unwanted roles to access protected area
     * 
     * @param \Cms\Service\Mode $mode
     * @return void
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
        
        $contentLanguages = $languageManager->fetchAll(true);

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
            'ppc' => $this->getPerPageCountGadget()
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
