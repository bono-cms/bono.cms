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

use Krystal\Db\Sql\TableBuilder;
use Cms\Install\ModuleInstaller;

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
        $this->view->getBreadcrumbBag()
                   ->clear();

        return $this->view->render('dashboard', array(
            'utilites' => $this->getItems(),
            'title' => 'Control panel',
            'notifications' => $this->getService('Cms', 'notificationManager')->getUnviewedCount(),
        ));
    }

    /**
     * Returns items for the dashboard
     * 
     * @return array
     */
    private function getItems()
    {
        $utilites = array();
        $path = dirname(dirname(__DIR__));
        $basicItems = include($path . '/Config/dashboard.items.php');

        if ($this->extendedMode) {
            $extendeditems = include($path . '/Config/dashboard.items.rest.php');
            $utilites = array_merge($basicItems, $extendeditems);
        } else {
            $utilites = $basicItems;
        }

        return $utilites;
    }

    /**
     * Installs a module from ZIP archive
     * 
     * @return string
     */
    public function installModuleAction()
    {
        if ($this->request->hasFiles('module')) {
            // Get uploaded file
            $files = $this->request->getFiles('module');
            $file = $files[0];

            $installer = new ModuleInstaller('module/');

            if ($installer->installFromZipFile($file->getTmpName(), $file->getName())) {
                $schema = $installer->getSchemaContents('MySQL', 'schema.sql');
                $pdo = $this->db['mysql']->getPdo();

                $builder = new TableBuilder($pdo);
                $builder->loadFromString($schema);
                $builder->run();

                $this->flashBag->set('success', 'A module has been installed successfully');
            } else {
                $this->flashBag->set('warning', 'Failed to install a module');
            }

            return '1';
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
        
        $this->getPerPageCountGadget()->setPerPageCount($count);
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
