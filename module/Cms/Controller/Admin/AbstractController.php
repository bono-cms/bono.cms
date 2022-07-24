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
use Cms\Collection\ChangeFreqCollection;
use Cms\Collection\PriorityCollection;

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

    /** Defaults **/
    const FIELD_SERVICE_NAME = 'blockFieldService';

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
     * Returns current language-dependent property
     * 
     * @param array $entities
     * @param string $property
     * @return mixed
     */
    final protected function getCurrentProperty(array $entities, $property)
    {
        // Get current language id
        $langId = $this->getService('Cms', 'languageManager')->getCurrentId();

        foreach ($entities as $entity) {
            if ($entity['lang_id'] == $langId || $entity['lang_id'] == 0) {
                return $entity[$property];
            }
        }

        return null;
    }

    /**
     * Extract bookmarks from modules configuration
     * 
     * @return array
     */
    protected function createBookmarks()
    {
        // Prepared output to be returned
        $output = array();

        $bookmarks = $this->getParamFromModules('bookmarks');

        foreach ($bookmarks as $index => $container) {
            foreach ($container as $item) {
                $output[] = $item;
            }
        }

        return $output;
    }

    /**
     * Extract menus configuration from loaded modules
     * 
     * @return array
     */
    private function createSidebarMenu()
    {
        return $this->getParamFromModules('menu');
    }

    /**
     * Returns a parameter from all loaded modules
     * 
     * @param string $key Shared configuration key
     * @return mixed
     */
    private function getParamFromModules($key)
    {
        $output = array();

        foreach ($this->getModulesConfiguration() as $module) {
            if (isset($module[$key])) {
                $output[] = $module[$key];
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
        static $configurations = null;

        // Cache method calls
        if (is_null($configurations)) {
            $modules = array();
            $current = $this->moduleManager->getLoadedModules();

            foreach ($current as $module) {
                if ($module->hasConfig()) {
                    $modules[] = $module->getConfig();
                }
            }

            array_multisort($modules, \SORT_NUMERIC);
            $configurations = $modules;
        }

        return $configurations;
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
     * @param string $route Optional route override
     * @return array
     */
    final protected function getFilter(FilterableServiceInterface $service, $route = '')
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
     * Load fields in view template
     * 
     * @param int $id Entity id
     * @return void
     */
    final protected function loadFields($id)
    {
        if ($this->moduleManager->isLoaded('Block')) {
            // Existing id is numeric, while new one isn't
            $new = !is_numeric($id);

            // Inform view about module partials
            $this->view->getPartialBag()->addPartialDir($this->view->createThemePath('Block', 'partials'));

            $this->view->addVariables(array(
                // Extra fields
                'blockCategories' => $this->getService('Block', 'categoryService')->fetchList(),
                'activeBlockCategoryIds' => !$new ? $this->getModuleService(self::FIELD_SERVICE_NAME)->getAttachedCategories($id) : array(),
                'fields' => !$new ? $this->getModuleService(self::FIELD_SERVICE_NAME)->getFields($id) : array()
            ));
        }
    }

    /**
     * Save dynamic field values
     * 
     * @param string $group Group name
     * @return void
     */
    final protected function saveFields($group)
    {
        if ($this->moduleManager->isLoaded('Block')) {
            $this->getModuleService(self::FIELD_SERVICE_NAME)->persist($group, $this->request->getAll());
        }
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

        $changeFreqCol = new ChangeFreqCollection();
        $priorCol = new PriorityCollection();

        // Shared variables for all templates
        $this->view->addVariables(array(
            'changeFreqs' => $changeFreqCol->getAll(),
            'priorities' => $priorCol->getAll(),
            'appConfig' => $this->appConfig,
            'extendedMode' => !$mode->isSimple(),
            'mode' => $mode,
            'paramBag' => $this->paramBag,
            'languages' => $contentLanguages,
            'currentLanguage' => $languageManager->fetchByCurrentId(),
            'ppc' => $this->getPerPageCountGadget(),
            'queryLogger' => $this->db['mysql']->getQueryLogger(),
            'sidebar' => $this->createSidebarMenu(),
            'loadedModules' => $this->moduleManager->getLoadedModuleNames()
        ));

        $this->view->getPluginBag()->load(array(
            'jquery',
            'bootstrap',
            'famfam-flag',
            'font-awesome-5',
            'datetimepicker',
            'jquery.mCustomScrollbar',
            'admin',
            'to-top'
        ));

        // Make sure application handler is always last
        $this->view->getPluginBag()->appendLastScript('@Cms/admin/app.js');

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
        }
    }
}
