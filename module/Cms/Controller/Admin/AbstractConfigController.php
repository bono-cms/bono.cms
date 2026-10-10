<?php

/**
 * This file is part of the Bono CMS
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Controller\Admin;

use Krystal\Validation\Validator;

/**
 * Configuration controllers in modules aren't different, 
 * the only part that differs is grabbing services and validation rules.
 * 
 * So in order to reduce duplication, it would be nice to wrap all related functionality
 * into one abstract configuration controller.
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
     * Applies validation rules to the supplied validator
     * 
     * Each module must implement this to declare the rules for its
     * configuration form. The validator is pre-bound to the module's
     * `config` POST payload, so only rule declarations are needed here.
     * 
     * @param \Krystal\Validation\Validator $validator
     * @return void
     */
    abstract protected function configureValidator(Validator $validator);

    /**
     * Shows configuration form
     * 
     * @return string
     */
    public function indexAction()
    {
        $this->loadPlugins();

        return $this->view->render('config', [
            'title' => 'Configuration',
            'config' => $this->getConfigManager()->getEntity()
        ]);
    }

    /**
     * Saves data from the configuration form
     * 
     * @return string
     */
    public function saveAction()
    {
        $validator = $this->createValidation();

        $this->configureValidator($validator);

        if ($validator->isPassed()) {
            // Grab history manager service
            $historyManager = $this->getService('Cms', 'historyManager');
            $config = $this->request->getPost('config');

            if ($this->getConfigManager()->storeMany($config) && $historyManager->write($this->moduleName, 'Configuration has been updated', '')) {
                $this->flashBag->set('success', 'Configuration has been updated successfully');
            }

            return $this->json([
                'refresh' => true
            ]);
        }

        return $this->json([
            'errors' => $validator->getErrors()
        ]);
    }

    /**
     * Returns configuration for the module being executed
     * 
     * @return \Krystal\Config\ConfigManager
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