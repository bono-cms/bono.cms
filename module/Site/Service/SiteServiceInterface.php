<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Site\Service;

interface SiteServiceInterface
{
    /**
     * Creates URL by target ID and module name
     * 
     * @param string $targetId
     * @param string $module
     * @return string
     */
    public function createUrl($targetId, $module);
}
