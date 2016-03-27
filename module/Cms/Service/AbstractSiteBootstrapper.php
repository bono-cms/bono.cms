<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Service;

use Krystal\Application\Module\ModuleManagerInterface;
use Krystal\Application\View\ViewManagerInterface;

abstract class AbstractSiteBootstrapper
{
    /**
     * Module manager to grab data
     * 
     * @var \Krystal\Application\Module\ModuleManagerInterface
     */
    protected $moduleManager;

    /**
     * View manager whose state would be altered
     * 
     * @var \Krystal\Application\View\ViewManagerInterface
     */
    protected $view;

    /**
     * State initialization
     * 
     * @param \Krystal\Application\Module\ModuleManagerInterface $moduleManager
     * @param \Krystal\Application\View\ViewManagerInterface $view
     * @return void
     */
    public function __construct(ModuleManagerInterface $moduleManager, ViewManagerInterface $view)
    {
        $this->moduleManager = $moduleManager;
        $this->view = $view;
    }
}
