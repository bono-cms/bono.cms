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

final class ModuleInstaller implements ModuleInstallerInterface
{
    /**
     * Target directory where all modules installed into
     * 
     * @var string
     */
    private $dir;

    /**
     * A name of installed module
     * 
     * @var string
     */
    private $module;

    /**
     * State initialization
     * 
     * @param string $dir
     * @return void
     */
    public function __construct($dir)
    {
        $this->dir = $dir;
    }

    /**
     * Returns contents of module's schema
     * 
     * @throws \LogicException If the target module doesn't have a database schema
     * @throws \RuntimeException If the target module wasn't installed properly
     * @return string
     */
    public function getSchemaContents($mapper, $schema)
    {
        if ($this->module !== null) {
            // The path to the schematic
            $schema = $this->dir.$this->module.'/Storage/'.$mapper.'/'.$schema;

            if (is_file($schema)) {
                return file_get_contents($schema);
            } else {
                throw new LogicException(sprintf('The module "%s" is missing its database schema file called "%s"', $this->module, $schema));
            }

        } else {
            throw new RuntimeException('A module was not installed properly');
        }
    }

    /**
     * Installs a module from ZIP archive
     * 
     * @param string $file
     * @return boolean
     */
    public function installFromZipFile($file)
    {
        if (!$this->isAlreadyInstalled($file)) {
            if ($this->extractFromZip($file) && $this->renameFreshFolder($file)){
                $this->module = $this->sanitizeFolderName($this->extractFileName($file));
                return true;
            } else {
                return false;
            }
        } else {
            // Can't install twice
            return false;
        }
    }

    /**
     * Determines whether module is already installed
     * 
     * @param string $file
     * @return boolean
     */
    private function isAlreadyInstalled($file)
    {
        $module = $this->sanitizeFolderName($this->extractFileName($file));
        return is_dir($this->dir.$module);
    }

    /**
     * Renames a folder keeping its required name
     * 
     * @param string $file
     * @return boolean
     */
    private function renameFreshFolder($file)
    {
        $old = $this->dir.$this->extractFileName($file);
        $new = $this->dir.$this->sanitizeFolderName($file);

        if ($old !== $new) {
            return rename($old, $new);
        } else {
            return true;
        }
    }

    /**
     * Extract file name from a path
     * 
     * @param string $file
     * @return string
     */
    private function extractFileName($file)
    {
        return pathinfo($file, PATHINFO_FILENAME);
    }

    /**
     * Sanitizes module name removing anything but letters
     * 
     * @param string $file
     * @return string
     */
    private function sanitizeFolderName($file)
    {
        $file = $this->extractFileName($file);
        $file = preg_replace('~[0-9-.]~', '', $file);

        return $file;
    }

    /**
     * Extracts from a zipped file
     * 
     * @param string $file
     * @return boolean
     */
    private function extractFromZip($file)
    {
        $zip = new ZipArchive;

        if ($zip->open($file)) {

            $zip->extractTo($this->dir);
            $zip->close();
            return true;
        } else {
            return false;
        }
    }
}
