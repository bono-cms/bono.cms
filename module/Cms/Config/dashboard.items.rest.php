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
        'icon' => 'icons/users.svg',
        'name' => 'Users',
        'description' => 'Edit users and their privileges'
    ),
    array(
        'route' => 'Cms:Admin:Tweaks@indexAction',
        'icon' => 'icons/configuration.svg',
        'name' => 'Tweaks',
        'description' => 'Tweaks of basic system configuration'
    ),
    array(
        'route' => 'Cms:Admin:ModuleManager@indexAction',
        'icon' => 'icons/module-manager.svg',
        'name' => 'Module manager',
        'description' => 'Install or drop system modules'
    ),
    array(
        'route' => 'Cms:Admin:Languages@gridAction',
        'icon' => 'icons/languages.svg',
        'name' => 'Languages',
        'description' => 'Tweak system languages'
    ),
    array(
        'route' => 'Cms:Admin:Themes@indexAction',
        'icon' => 'icons/themes.svg',
        'name' => 'Themes',
        'description' => 'View and manage installed themes'
    ),
    array(
        'route' => 'Cms:Admin:SitemapLinks@indexAction',
        'icon' => 'icons/xml.svg',
        'name' => 'Sitemap links',
        'description' => 'View site map links that can be used to submit your site to search engines'
    ),
    array(
        'route' => 'Cms:Admin:Info@indexAction',
        'icon' => 'icons/info.svg',
        'name' => 'System info',
        'description' => 'View server configuration'
    )
);
