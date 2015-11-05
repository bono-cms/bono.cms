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

interface ModuleInstallerInterface
{
    /**
     * Returns contents of module's schema
     * 
     * @throws \LogicException If the target module doesn't have a database schema
     * @throws \RuntimeException If the target module wasn't installed properly
     * @return string
     */
    public function getSchemaContents($mapper, $schema);

    /**
     * Installs a module from ZIP archive
     * 
     * @param string $file
     * @return boolean
     */
    public function installFromZipFile($file);
}
