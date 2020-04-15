<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Site\Controller;

use Cms\Service\SitemapTool;
use Krystal\Seo\Sitemap\SitemapGenerator;
use Krystal\Seo\Sitemap\SitemapIndexGenerator;

final class Sitemap extends AbstractController
{
    /**
     * Renders SiteMap
     * 
     * @param string $language Optional language code
     * @return string
     */
    public function indexAction($language = null)
    {
        // Does this request come for a single one?
        if ($language !== null) {
            return $this->renderSingle($language);
        }

        // Active language codes
        $codes = $this->getService('Cms', 'languageManager')->fetchCodes(true);

        // Gotta render group of SiteMaps?
        if (count($codes) > 1) {
            return $this->renderGroup($codes);
        } else {
            // Render just a single SiteMap
            return $this->renderSingle($language);
        }
    }

    /**
     * Renders single SiteMap
     * 
     * @param string $language Optional language code
     * @return string
     */
    private function renderSingle($language)
    {
        if (is_null($language)) {
            $language = $this->getService('Cms', 'languageManager')->getDefaultCode();
        }

        // Grab all URLs
        $urls = $this->getService('Cms', 'webPageManager')
                     ->fetchURLs($this->request->getBaseUrl(), $language, $this->moduleManager);

        if ($urls !== false) {
            // Grab configration entity
            $config = $this->getService('Cms', 'configManager')->getEntity();
            $urls = $this->normalizeUrls($urls, $config->getSitemapPriority(), SitemapTool::createChangeFreq($config->getSitemapFrequency()));

            $generator = new SitemapGenerator(false);
            $generator->addUrls($urls);

            // Force response to be XML 
            $this->response->respondAsXml();

            return $generator->render();

        } else {
            // Invalid language code supplied, so simply trigger 404
            return false;
        }
    }

    /**
     * Renders a group of SiteMaps
     * 
     * @param array $codes Language codes
     * @return string
     */
    private function renderGroup(array $codes)
    {
        $generator = new SitemapIndexGenerator();
        $generator->addSitemaps($this->createGroupLinks($codes));

        // Force response to be XML 
        $this->response->respondAsXml();

        return $generator->render();
    }

    /**
     * Normalize URLs
     * 
     * @param string $priority Default priority
     * @param string $changefreq Default changefreq
     * @return array Normalized array
     */
    private function normalizeUrls(array $urls, $priority, $changefreq)
    {
        foreach ($urls as &$url) {
            // Append defaults on absence
            if (!isset($url['priority'])) {
                $url['priority'] = $priority;
            }

            if (!isset($url['changefreq'])) {
                $url['changefreq'] = $changefreq;
            }
        }

        return $urls;
    }

    /**
     * Create links for all SiteMaps
     * 
     * @param array $codes Language codes
     * @return array
     */
    private function createGroupLinks(array $codes)
    {
        // To be returned
        $output = array();

        foreach ($codes as $code) {
            $output[] = array(
                'loc' => $this->request->getBaseUrl() . $this->createUrl('Site:Sitemap@indexAction', array($code), 1)
            );
        }

        return $output;
    }
}
