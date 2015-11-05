<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Install;

interface StorageInstallerInterface
{
    /**
     * Installs storage-relevant data
     * 
     * @param string $path The path to database configuration file
     * @param array $details
     * @return boolean
     */
    public function install($path, array $details);
}
