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

use Krystal\FileSystem\FileManager;
use Krystal\Application\AppConfigInterface;

final class ThemeService implements ThemeServiceInterface
{
    /**
     * Application configuration container
     * 
     * @var \Krystal\Application\AppConfigInterface
     */
    private $appConfig;

    const THEME_PARAM_CONFIG_FILE = 'theme.config.php';
    const THEME_PARAM_COVER_FILE = 'cover.jpg';
    const THEME_PARAM_META_SECTION = 'meta';
    const THEME_PARAM_MODULE = 'Site';

    /**
     * State initialization
     * 
     * @param \Krystal\Application\AppConfigInterface $appConfig
     * @return void
     */
    public function __construct(AppConfigInterface $appConfig)
    {
        $this->appConfig = $appConfig;
    }

    /**
     * Drop multiple themes at once
     * 
     * @param array $themes
     * @return boolean
     */
    public function dropThemes(array $themes)
    {
        foreach ($themes as $theme) {
            $this->dropTheme($theme);
        }

        return true;
    }

    /**
     * Removes a theme from file system
     * 
     * @param string $theme Theme directory name
     * @return boolean
     */
    public function dropTheme($theme)
    {
        $dir = $this->appConfig->getModuleThemeDir(self::THEME_PARAM_MODULE, $theme);

        if (is_dir($dir)) {
            return FileManager::rmdir($dir);
        } else {
            return false;
        }
    }

    /**
     * Returns all themes 
     * 
     * @return array
     */
    public function getThemes()
    {
        $result = array();

        foreach ($this->getThemeConfigData() as $theme => $data) {
            // Ignore themes that do not have meta section
            if (isset($data[self::THEME_PARAM_META_SECTION])) {
                // Append preview image
                $data[self::THEME_PARAM_META_SECTION]['cover'] = $this->createCoverImagePath($theme);
                // Append folder name as well
                $data[self::THEME_PARAM_META_SECTION]['theme'] = $theme;

                $result[] = $data[self::THEME_PARAM_META_SECTION];
            }
        }

        return $result;
    }

    /**
     * Returns default image for themes that do not have a cover image
     * 
     * @return string
     */
    private function getDefaultCoverImagePath()
    {
        return 'default'; //@TODO
    }

    /**
     * Creates a path to cover image
     * 
     * @param string $theme
     * @return boolean
     */
    private function createCoverImagePath($theme)
    {
        $file = sprintf('%s/%s', $this->appConfig->getModuleThemeDir(self::THEME_PARAM_MODULE, $theme), self::THEME_PARAM_COVER_FILE);

        if (is_file($file)) {
            // It only makes sense to create $url if the target file exists. Otherwise there's no need to invoke generation
            $url = sprintf('%s/%s', $this->appConfig->getModuleThemeUrl(self::THEME_PARAM_MODULE, $theme), self::THEME_PARAM_COVER_FILE);
            return $url;
        } else {
            return $this->getDefaultCoverImagePath();
        }
    }

    /**
     * Reads data from available theme directores, then reads their associated config files
     * 
     * @return array
     */
    private function getThemeConfigData()
    {
        $result = array();

        foreach ($this->getThemeDirs() as $theme) {
            // Create path to configuration file
            $configFile = sprintf('%s/%s', $this->appConfig->getModuleThemeDir(self::THEME_PARAM_MODULE, $theme), self::THEME_PARAM_CONFIG_FILE);

            if (is_file($configFile)) {
                $data = include($configFile);

                if (is_array($data)) {
                    $result[$theme] = $data;
                }
            }
        }

        return $result;
    }

    /**
     * Returns available theme directores
     * 
     * @return array
     */
    private function getThemeDirs()
    {
        $path = $this->appConfig->getModuleTemplateDir(self::THEME_PARAM_MODULE);
        return FileManager::getFirstLevelDirs($path);
    }
    
}