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
        $this->response->redirect($this->createUrl('Cms:Admin:Auth@indexAction'));
    }

    /**
     * {@inheritDoc}
     */
    protected function onNoRights()
    {
        die($this->translator->translate('You do not have enough rights to perform this action!'));
    }

    /**
     * Extra bookmarks from modules configuration
     * 
     * @param array $modulesConfiguration
     * @return array
     */
    private function createBookmarks(array $modulesConfiguration)
    {
        $output = array();

        foreach ($modulesConfiguration as $module) {
            if (isset($module['bookmarks'])) {
                $output[$module['name']] = $module['bookmarks'];
            }
        }

        return $output;
    }

    /**
     * Returns a collection of modules configuration
     * 
     * @return array
     */
    private function getModulesConfiguration()
    {
        $modules = array();
        $current = $this->moduleManager->getLoadedModules();

        foreach ($current as $module) {
            if ($module->hasConfig()) {
                $modules[] = $module->getConfig();
            }
        }

        array_multisort($modules, \SORT_NUMERIC);
        return $modules;
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
    protected function bootstrap()
    {
        // Force to load specific administration language if defined
        if ($this->paramBag->has('admin_language')) {
            $this->loadTranslations($this->paramBag->get('admin_language'));
        }

        $this->validateRequest();

        $this->view->setTheme('admin');
        $this->view->getPartialBag()->addPartialDir($this->view->createThemePath('Cms', 'admin') . '/partials/')
                                    ->addStaticPartial($this->view->createThemePath('Menu', 'admin'), 'menu-widget');

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

        $modulesConfiguration = $this->getModulesConfiguration();

        // Shared variables for all templates
        $this->view->addVariables(array(
            'appConfig' => $this->appConfig,
            'extendedMode' => !$mode->isSimple(),
            'mode' => $mode,
            'paramBag' => $this->paramBag,
            'languages' => $contentLanguages,
            'currentLanguage' => $languageManager->fetchByCurrentId(),
            'ppc' => $this->getPerPageCountGadget(),
            'queryLogger' => $this->db['mysql']->getQueryLogger(),
            'bookmarks' => $this->createBookmarks($modulesConfiguration),
            'modulesConfiguration' => $modulesConfiguration,
            'loadedModules' => $this->moduleManager->getLoadedModuleNames()
        ));

        $this->view->getPluginBag()->load(array(
            'jquery',
            'bootstrap',
            'famfam-flag',
            'font-awesome',
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
