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

use Krystal\Seo\Robots;

final class RobotsService
{
    /**
     * Synchronizes robots file
     * 
     * @param string $sitemap Link to Sitemap
     * @return boolean
     */
    public static function syncRobots($sitemap)
    {
        // Path to the root
        $path = getcwd() . '/' . Robots::FILENAME;
        return file_put_contents($path, self::renderRobots($sitemap));
    }

    /**
     * Render robots
     * 
     * @param string $sitemap Link to Sitemap
     * @return string
     */
    public static function renderRobots($sitemap)
    {
        $robots = new Robots();
        $robots->addUserAgent('*')
               ->addDisallow([
                    '/config/',
                    '/data/',
                    '/module/',
                    '/vendor/'
               ])
               ->addBreak()
               ->addSitemap($sitemap);

        return $robots->render();
    }
}
