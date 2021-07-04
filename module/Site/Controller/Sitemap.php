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
     * @param string $locale Optional language code
     * @return string
     */
    private function renderSingle($locale)
    {
        if (is_null($locale)) {
            $locale = $this->getService('Cms', 'languageManager')->getDefaultCode();
        }

        // Grab all URLs
        $urls = $this->getService('Cms', 'webPageManager')
                     ->fetchURLs($locale, $this->moduleManager);

        if ($urls !== false) {
            // Grab configuration entity
            $config = $this->getService('Cms', 'configManager')->getEntity();
            $urls = $this->normalizeUrls($urls, $config->getSitemapPriority(), SitemapTool::createChangeFreq($config->getSitemapFrequency()));

            // Push dedicated home URL first
            array_unshift($urls, $this->createHomeUrl($this->request->getBaseUrl(), $locale));

            // Done with URLs. Now, it's time to render them in XML-format
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
     * Create home URL
     * 
     * @param string $baseUrl
     * @param string $locale Current locale
     * @return array
     */
    private function createHomeUrl($baseUrl, $locale)
    {
        // Language service
        $languageManager = $this->getService('Cms', 'languageManager');

        // Total count of published languages
        $languageCount = $languageManager->getCount(true);

        // Is this URL for default page being generated?
        if ($languageManager->getDefaultCode() == $locale || $languageCount == 1) {
            // If so, we don't need to direct it to switch URL
            return array(
                'loc' => sprintf('%s/', $baseUrl)
            );
        }

        // For another cases
        if ($languageCount > 1) {
            return array(
                'loc' => $baseUrl . $this->createUrl('Site:Main@changeLanguageAction', array($locale))
            );
        }
    }

    /**
     * Normalize URLs
     * 
     * @param array $urls
     * @param string $priority Default priority
     * @param string $changefreq Default changefreq
     * @return array Normalized array
     */
    private function normalizeUrls(array $urls, $priority, $changefreq)
    {
        $page = $this->getService('Pages', 'pageManager')->fetchDefault();

        // Stop, if home page is not defined
        if ($page == false) {
            return array();
        }

        // Process URLs
        foreach ($urls as $index => $url) {
            // Remove home page URL from indexing
            if ($url['module'] == 'Pages' && $url['id'] == $page->getId()) {
                unset($urls[$index]);
            }

            // Append defaults on absence
            if (!isset($url['priority'])) {
                $urls[$index]['priority'] = $priority;
            }

            if (!isset($url['changefreq'])) {
                $urls[$index]['changefreq'] = $changefreq;
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
