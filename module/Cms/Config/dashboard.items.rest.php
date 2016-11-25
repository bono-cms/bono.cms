<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

return array(
    array(
        'route' => 'Cms:Admin:Users@gridAction',
        'icon' => 'fa fa-users fa-5x',
        'name' => 'Users',
        'description' => 'Edit users and their privileges'
    ),
    array(
        'route' => 'Cms:Admin:Tweaks@indexAction',
        'icon' => 'fa fa-wrench fa-5x',
        'name' => 'Tweaks',
        'description' => 'Tweaks of basic system configuration'
    ),
    array(
        'route' => 'Cms:Admin:ModuleManager@indexAction',
        'icon' => 'fa fa-plug fa-5x',
        'name' => 'Module manager',
        'description' => 'Install or drop system modules'
    ),
    array(
        'route' => 'Cms:Admin:Languages@gridAction',
        'icon' => 'fa fa-language fa-5x',
        'name' => 'Languages',
        'description' => 'Tweak system languages'
    ),
    array(
        'route' => 'Cms:Admin:Themes@indexAction',
        'icon' => 'fa fa-paint-brush fa-5x',
        'name' => 'Themes',
        'description' => 'View and manage installed themes'
    ),
    array(
        'route' => 'Cms:Admin:SitemapLinks@indexAction',
        'icon' => 'fa fa-sitemap fa-5x',
        'name' => 'Sitemap links',
        'description' => 'View site map links that can be used to submit your site to search engines'
    ),
    array(
        'route' => 'Cms:Admin:Info@indexAction',
        'icon' => 'fa fa-info-circle fa-5x',
        'name' => 'System info',
        'description' => 'View server configuration'
    )
);
