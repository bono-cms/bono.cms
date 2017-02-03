<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Controller\Admin;

use Cms\Service\SitemapTool;

final class SitemapLinks extends AbstractController
{
    /**
     * Render links
     * 
     * @return string
     */
    public function indexAction()
    {
        $config = $this->getModuleService('configManager')->getEntity();

        $this->view->getBreadcrumbBag()
                   ->addOne('Sitemap links');

        return $this->view->render('sitemap-links', array(
            'links' => $this->createLinks(),
            'priorities' => SitemapTool::getPriorities(),
            'changefreqs' => SitemapTool::getChangefreqs(),            
            'priority' => $config->getSitemapPriority(),
            'changefreq' => $config->getSitemapFrequency()
        ));
    }

    /**
     * Save sitemap confuguration
     * 
     * @return string
     */
    public function saveAction()
    {
        $this->flashBag->set('success', 'Sitemap confuguration has been successfully updated!');
        $this->getModuleService('configManager')->storeMany($this->request->getPost());

        return '1';
    }

    /**
     * Ping sitemaps
     * 
     * @return void
     */
    public function pingAction()
    {
        if (SitemapTool::ping($this->createLinks())) {
            $this->flashBag->set('success', 'Search engines have been informed about new version of your sitemap!');
        } else {
            $this->flashBag->set('warning', 'There was a connection error while submitting the sitemap');
        }

        return '1';
    }

    /**
     * Create sitemap URL links
     * 
     * @return array
     */
    private function createLinks()
    {
        $links = array();
        $languages = $this->getModuleService('languageManager')->fetchAll(true);

        if (count($languages) > 1) {
            foreach ($languages as $language) {
                $url = $this->request->getBaseUrl().$this->createUrl('Site:Main@sitemapAction', array($language->getCode()), 1);
                $links[$language->getName()]= $url;
            }

        } else {
            $links[$languages[0]->getName()] = $this->request->getBaseUrl().$this->createUrl('Site:Main@sitemapAction');
        }

        return $links;
    }
}
