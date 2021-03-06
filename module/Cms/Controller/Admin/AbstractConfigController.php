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

/**
 * Configuration controllers in modules aren't different, 
 * the only part that differs is grabbing services and validation rules.
 * 
 * So in order to reduce duplication, it would be nice to wrap all related functionality
 * into one abstract configuration controller.
 * 
 */
abstract class AbstractConfigController extends AbstractController
{
    /**
     * Parent breadcrumb
     * 
     * @var string
     */
    protected $parent = null;

    /**
     * Returns validation rules for target form input
     * 
     * @return array
     */
    abstract protected function getValidationRules();

    /**
     * Shows configuration form
     * 
     * @return string
     */
    public function indexAction()
    {
        $this->loadPlugins();

        return $this->view->render('config', array(
            'title' => 'Configuration',
            'config' => $this->getConfigManager()->getEntity()
        ));
    }

    /**
     * Saves data from the configuration form
     * 
     * @return string
     */
    public function saveAction()
    {
        // Grab POST request data
        $input = $this->request->getPost('config');
        $formValidator = $this->createValidator(array(
            'input' => array(
                'source' => $input,
                'definition' => $this->getValidationRules()
            )
        ));

        if ($formValidator->isValid()) {
            // Grab history manager service
            $historyManager = $this->getService('Cms', 'historyManager');

            if ($this->getConfigManager()->storeMany($input) && $historyManager->write($this->moduleName, 'Configuration has been updated', '')) {
                $this->flashBag->set('success', 'Configuration has been updated successfully');
            }

            return '1';

        } else {
            return $formValidator->getErrors();
        }
    }

    /**
     * Returns configuration for the module being executed
     * 
     * @return \Krystal\Config\ConfigMangaer
     */
    protected function getConfigManager()
    {
        return $this->getModuleService('configManager');
    }

    /**
     * Loads required plugins for view
     * 
     * @return void
     */
    protected function loadPlugins()
    {
        if (is_null($this->parent)) {
            $this->parent = sprintf('%s:Admin:Browser@indexAction', $this->moduleName);
        }

        $this->view->getBreadcrumbBag()->addOne($this->moduleName, $this->parent)
                                       ->addOne('Configuration');
    }
}
