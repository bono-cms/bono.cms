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
        $this->view->getBreadcrumbBag()
                   ->addOne('Module manager');

        return $this->view->render('module-manager', array(
            'modules' => $this->getModules(),
            'moduleManager' => $this->moduleManager
        ));
    }

    /**
     * Return modules
     * 
     * @return boolean
     */
    private function getModules()
    {
        $modules = array();
        $current = $this->moduleManager->getLoadedModules();

        foreach ($current as $module) {
            if ($module->hasConfig()) {
                $modules[] = $module->getConfig();
            }
        }

        return $modules;
    }

    /**
     * Drops module related data from the storage
     * 
     * @param string $module Module name
     * @return boolean
     */
    private function dropFromStorage($module)
    {
        $ns = sprintf('\%s\Storage\MySQL\Dropper', $module);

        if (class_exists($ns)) {
            $dropper = $this->createMapper($ns);
            $dropper->dropAll();
        } else {
            return false;
        }
    }

    /**
     * Drops a module from the file system
     * 
     * @param string $module
     * @return boolean
     */
    private function dropFromFileSystem($module)
    {
        // Remove from cache directory if present
        $this->moduleManager->removeFromCacheDir($module);

        // Remove from uploading directory if present
        $this->moduleManager->removeFromUploadsDir($module);

        // And finally remove the module itself from the file system
        $this->moduleManager->removeFromFileSysem($module);

        return true;
    }

    /**
     * Drop all slugs associated with a module
     * 
     * @param string $module Module name
     * @return boolean
     */
    private function dropSlugs($module)
    {
        return $this->getModuleService('webPageManager')->removePksByModule($module);
    }

    /**
     * Removes a module completely
     * 
     * @param string $module
     * @return boolean
     */
    private function removeModule($module)
    {
        $this->dropFromStorage($module);
        $this->dropSlugs($module);
        $this->dropFromFileSystem($module);

        return true;
    }
    
    /**
     * Deletes a module by its associated name
     * 
     * @param string $module
     * @return string
     */
    public function deleteAction($module)
    {
        $this->removeModule($module);

        // Always assume success
        $this->flashBag->set('success', 'Selected module has been successfully removed');
        return '1';
    }

    /**
     * Delete many modules at once
     * 
     * @return string
     */
    public function deleteManyAction()
    {
        $modules = array_keys($this->request->getPost('toDelete', array()));

        foreach ($modules as $module) {
            $this->removeModule($module);
        }

        $this->flashBag->set('success', 'Selected modules have been successfully removed');
        return '1';
    }
}
