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

            return $this->renderSitemap('sitemap-single', array(
                'urls' => $urls,
                'priority' => $config->getSitemapPriority(),
                'changefreq' => SitemapTool::createChangeFreq($config->getSitemapFrequency())
            ));

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
        // SiteMap URLs
        $urls = $this->createGroupLinks($codes);

        return $this->renderSitemap('sitemap-group', array(
            'urls' => $urls
        ));
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
            $output[] = $this->request->getBaseUrl() . $this->createUrl('Site:Sitemap@indexAction', array($code), 1);
        }

        return $output;
    }

    /**
     * Renders SiteMap sending proper XML header
     * 
     * @param string $template Template name
     * @param array $vars Template variables
     * @return string
     */
    private function renderSitemap($template, array $vars)
    {
        // Force response to be XML 
        $this->response->respondAsXml();

        // Render sitemap.pthml located under Cms module inside administration template
        return $this->view->renderRaw('Cms', 'sitemap', $template, $vars);
    }    
}
