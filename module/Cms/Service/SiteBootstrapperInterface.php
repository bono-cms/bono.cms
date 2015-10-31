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

interface SiteBootstrapperInterface
{
    /**
     * Bootstraps a module for the site
     * 
     * @return void
     */
    public function bootstrap();
}
