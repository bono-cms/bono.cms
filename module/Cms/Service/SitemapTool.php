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
use Krystal\Seo\Sitemap\Query;
use Cms\Collection\ChangeFreqCollection;

final class SitemapTool
{
    /**
     * Inform search engines about SiteMap location
     * 
     * @param string $url Front SiteMap URL
     * @return boolean
     */
    public static function ping($url)
    {
        $query = new Query($url);
        return $query->ping();
    }

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
}
