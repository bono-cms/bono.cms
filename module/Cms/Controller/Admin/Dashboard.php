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

final class Dashboard extends AbstractController
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
     * Displays a dashboard
     * 
     * @return string
     */
    public function indexAction()
    {
        // We don't need breadcrumbs on the dashboard
        $this->view->getBreadcrumbBag()->clear();

        return $this->view->render('dashboard', array_merge($this->getItems(), array(

            'title' => 'Control panel',
            'notifications' => $this->getService('Cms', 'notificationManager')->getUnviewedCount(),
        )));
    }

    /**
     * Returns items for the dashboard
     * 
     * @return array
     */
    private function getItems()
    {
        $modules = array();
        $utilites = array();

        $current = $this->moduleManager->getLoadedModules();

        foreach ($current as $module) {
            if ($module->hasConfig('module')) {
                $modules[] = $module->getConfig('module');
            }
        }

        array_multisort($modules, \SORT_NUMERIC);

        $path = dirname(dirname(__DIR__));

        $basicItems = include($path . '/Config/dashboard.items.php');

        if ($this->extendedMode) {
            $extendeditems = include($path . '/Config/dashboard.items.rest.php');
            $utilites = array_merge($basicItems, $extendeditems);

        } else {
            $utilites = $basicItems;
        }

        return array(

            'modules' => $modules,
            'utilites' => $utilites
        );
    }

    /**
     * Installs a module from ZIP archive
     * 
     * @return string
     */
    public function installModuleAction()
    {
        if ($this->request->hasFiles('module')) {

            $files = $this->request->getFiles('module');
            $module = $files[0];

            // Logic to install a module ....
        }
    }

    /**
     * Changes a mode
     * 
     * @return string
     */
    public function changeModeAction()
    {
        $key = 'mode';

        $target = $this->request->getPost($key);
        $mode = $this->getService('Cms', 'mode');

        switch ($target) {
            case 'simple':
                $mode->setSimple();
            break;

            case 'advanced':
                $mode->setAdvanced();
            break;
        }

        return '1';
    }

    /**
     * Changes items per page count
     * 
     * @return string
     */
    public function itemsPerPageChangeAction()
    {
        $count = $this->request->getPost('count');
        
        $this->getPerPageCountProvider()->setPerPageCount($count);
        return '1';
    }

    /**
     * Generates a slug from a title
     * 
     * @return string
     */
    public function slugAction()
    {
        $title = $this->request->getPost('title');
        return $this->getService('Cms', 'webPageManager')->sluggify($title);
    }
}
