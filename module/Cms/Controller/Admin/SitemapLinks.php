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
        // Front SiteMap URL
        $url = $this->request->getBaseUrl().$this->createUrl('Site:Sitemap@indexAction');

        if (SitemapTool::ping($url)) {
            $this->flashBag->set('success', 'Search engines have been informed about new version of your sitemap!');
        } else {
            $this->flashBag->set('warning', 'There was a connection error while submitting the sitemap');
        }

        return '1';
    }
}
