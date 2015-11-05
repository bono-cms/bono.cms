<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Controller\Install;

use Cms\Install\StorageInstaller;

final class Install extends AbstractInstallController
{
    /**
     * Renders wizard's page
     * 
     * @return string
     */
    public function indexAction()
    {
        $this->loadPlugins();

        return $this->view->render('install', array(
            'title' => 'Bono CMS: Installation wizard'
        ));
    }

    /**
     * Installs the system
     * 
     * @return string
     */
    public function installAction()
    {
        if ($this->request->hasPost('db')) {

            $input = $this->request->getPost('db');
            $formValidator = $this->getValidator($input);

            if ($formValidator->isValid()) {

                $installer = new StorageInstaller();
                $result = $installer->installFromDump('db.config.php', 'dump.sql', $input);

                if (!$result) {
                    return $this->translator->translate('Cannot connect to database server. Make sure the data is valid!');
                } else {
                    return '1';
                }

            } else {
                return $formValidator->getErrors();
            }
        }
    }

    /**
     * Renders congratulations
     * 
     * @return string
     */
    public function readyAction()
    {
        $this->loadPlugins();

        return $this->view->render('ready', array(
            'title' => 'You are arrived!'
        ));
    }

    /**
     * Loads required plugins for template view
     * 
     * @return void
     */
    private function loadPlugins()
    {
        $this->view->getPluginBag()
                   ->appendScript('@Cms/install.js');

        $this->view->getBlockBag()
                   ->setBlocksDir($this->getWithViewPath('/blocks/', 'Cms', 'admin'));
    }
}
