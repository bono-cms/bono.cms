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

interface ThemeServiceInterface
{
    /**
     * Drop multiple themes at once
     * 
     * @param array $themes
     * @return boolean
     */
    public function dropThemes(array $themes);

    /**
     * Removes a theme from file system
     * 
     * @param string $theme Theme directory name
     * @return boolean
     */
    public function dropTheme($theme);

    /**
     * Returns all themes 
     * 
     * @return array
     */
    public function getThemes();
}
