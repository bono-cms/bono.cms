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
use Cms\Service\RobotsService;

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
     * Generates robots file
     * 
     * @return int
     */
    public function robotsAction()
    {
        $url = $this->request->getBaseUrl() . $this->createUrl('Site:Sitemap@indexAction', array(), 0);

        if (SitemapTool::syncRobots($url)) {
            $this->flashBag->set('success', 'Robots file has been synchronized successfully');
        } else {
            $this->flashBag->set('warning', 'An error occurred during synchronization');
        }

        return 1;
    }
}
