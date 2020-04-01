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
     * Renders site map
     * 
     * @param string $language Optional language code
     * @return string
     */
    public function sitemapAction($language = null)
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

            // Define response as XML 
            $this->response->respondAsXml();

            // Render sitemap.pthml located under Cms module inside administration template
            return $this->view->renderRaw('Cms', 'admin', 'sitemap', array(
                'urls' => $urls,
                'priority' => $config->getSitemapPriority(),
                'changefreq' => SitemapTool::createChangeFreq($config->getSitemapFrequency())
            ));

        } else {
            // Invalid language code supplied, so simply trigger 404
            return false;
        }
    }
}
