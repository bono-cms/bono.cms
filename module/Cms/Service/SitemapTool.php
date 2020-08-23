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

use Krystal\Stdlib\ArrayUtils;
use Cms\Collection\ChangeFreqCollection;

final class SitemapTool
{
    /**
     * Search engines Ping URLs
     * 
     * @var array
     */
    private static $engines = array(
        'http://www.google.com/webmasters/sitemaps/ping?sitemap=%s',
        'http://www.bing.com/ping?sitemap=%s',
        'http://blogs.yandex.ru/pings/?status=success&url=%s'
    );

    /**
     * Synchronizes robots file
     * 
     * @param string $sitemap Link to Sitemap
     * @return boolean
     */
    public static function syncRobots($sitemap)
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

        return $robots->save(getcwd());
    }

    /**
     * Creates change frequency value from a constant
     * 
     * @param string $const
     * @return string
     */
    public static function createChangeFreq($const)
    {
        $col = new ChangeFreqCollection();
        return strtolower($col->findByKey($const));
    }

    /**
     * Returns a collection of all possible and valid change frequencies
     * 
     * @return array
     */
    public static function getChangefreqs()
    {
        $col = new ChangeFreqCollection();
        return $col->getAll();
    }

    /**
     * Returns a collection of priorities
     * 
     * @return array
     */
    public static function getPriorities()
    {
        return ArrayUtils::valuefy(array(
            '0.0',
            '0.1',
            '0.2',
            '0.3',
            '0.4',
            '0.5',
            '0.6',
            '0.7',
            '0.8',
            '0.9',
            '1.0'
        ));
    }

    /**
     * Inform search engines about SiteMap location
     * 
     * @param string $url Front SiteMap URL
     * @return boolean
     */
    public static function ping($url)
    {
        foreach (self::$engines as $engine) {
            $target = sprintf($engine, urlencode($url));

            // Issue a GET request
            $hasError = @file_get_contents($target) !== false;
        }

        // Assume success
        return $hasError;
    }
}
