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

final class ModuleManager extends AbstractController
{
    /**
     * Renders a grid of modules
     * 
     * @return string
     */
    public function indexAction()
    {
        $modules = array();
        $current = $this->moduleManager->getLoadedModules();

        foreach ($current as $module) {
            if ($module->hasConfig('module')) {
                $modules[] = $module->getConfig('module');
            }
        }

        $this->view->getBreadcrumbBag()
                   ->addOne('Module manager');

        return $this->view->render('module-manager', array(
            'modules' => $modules,
            'moduleManager' => $this->moduleManager
        ));
    }

    /**
     * Deletes a module by its associated name
     * 
     * @param string $module
     * @return string
     */
    public function deleteAction($module)
    {
        return $module;
    }
}
