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

final class SitemapLinks extends AbstractController
{
    /**
     * Render links
     * 
     * @return string
     */
    public function indexAction()
    {
        $this->view->getBreadcrumbBag()
                   ->addOne('Sitemap links');

        return $this->view->render('sitemap-links', array(
            'links' => $this->createLinks()
        ));
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
