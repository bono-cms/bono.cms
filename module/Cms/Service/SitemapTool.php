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
     * Returns a collection of all possible and valid change frequences
     * 
     * @return array
     */
    public static function getChangefreqs()
    {
        return ArrayUtils::valuefy(array(
            'always',
            'hourly',
            'daily',
            'weekly',
            'monthly',
            'yearly',
            'never'
        ));
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
     * Ping sitemaps
     * 
     * @param array $urls
     * @return boolean
     */
    public static function ping(array $urls)
    {
        foreach (self::$engines as $engine) {
            foreach ($urls as $url) {
                $target = urlencode(sprintf($engine, $url));
                // @TODO
            }
        }

        // Assume success
        return true;
    }
}
