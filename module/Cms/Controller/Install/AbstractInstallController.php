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

use Krystal\Application\Controller\AbstractController;
use Krystal\Application\View\Resolver\Module as Resolver;

abstract class AbstractInstallController extends AbstractController
{
    /**
     * Checks whether the system has been already installed
     * 
     * @return void
     */
    final protected function checkIfInstalled()
    {
        $configManager = $this->getModuleService('configManager');
        $installed = $configManager->get('installed') == true;

        if ($installed) {
           die('Already installed');
        }
    }

    /**
     * {@inheritDoc}
     */
    final protected function bootstrap()
    {
        $this->view->getPartialBag()
                   ->addPartialDir($this->appConfig->getModulesDir() . '/Cms/View/Template/install/blocks/');

        $this->view->setLayout('__layout__', 'Cms')
                   ->setTheme('install');

        $this->view->getPluginBag()->load(array(
            'jquery',
            'bootstrap.core',
            'bootstrap.cosmo',
            'admin'
        ));
    }

    /**
     * Returns prepared form validator
     * 
     * @param array $input
     * @return \Krystal\Validate\ValidatorChain
     */
    final protected function getValidator(array $input)
    {
        return $this->createValidator(array(
            'input' => array(
                'source' => $input,
                'definition' => array(
                    'host' => array(
                        'required' => true,
                        'rules' => array(
                            'NotEmpty' => array(
                                'message' => 'Database host cannot be empty'
                            )
                        )
                    ),
                    'dbname' => array(
                        'required' => true,
                        'rules' => array(
                            'NotEmpty' => array(
                                'message' => 'Database name cannot be empty'
                            )
                        )
                    ),
                    'username' => array(
                        'required' => true,
                        'rules' => array(
                            'NotEmpty' => array(
                                'message' => 'Username cannot be empty'
                            )
                        )
                    ),
                    'password' => array(
                        'required' => false,
                        'rules' => array()
                    )
                )
            )
        ));
    }
}
