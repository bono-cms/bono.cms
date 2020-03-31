<?php

/**
 * Engine configuration container
 */

return array(
    'menu' => array(
        'name' => 'Engine',
        'icon' => 'fas fa-truck-pickup',
        'items' => array(
            array(
                'route' => 'Cms:Admin:History@indexAction',
                'name' => 'History',
                'description' => 'History of latest actions'
            ),
            array(
                'route' => 'Cms:Admin:Notepad@indexAction',
                'name' => 'Notepad',
                'description' => 'Keep your notes using notepad'
            ),
            array(
                'route' => 'Cms:Admin:Notifications@indexAction',
                'name' => 'Notifications',
                'description' => 'All system notifications'
            ),
            array(
                'route' => 'Cms:Admin:Users@gridAction',
                'name' => 'Users',
                'description' => 'Edit users and their privileges'
            ),
            array(
                'route' => 'Cms:Admin:Tweaks@indexAction',
                'name' => 'Tweaks',
                'description' => 'Tweaks of basic system configuration'
            ),
            array(
                'route' => 'Cms:Admin:ModuleManager@indexAction',
                'name' => 'Module manager',
                'description' => 'Install or drop system modules'
            ),
            array(
                'route' => 'Cms:Admin:Languages@indexAction',
                'name' => 'Languages',
                'description' => 'Tweak system languages'
            ),
            array(
                'route' => 'Cms:Admin:Themes@indexAction',
                'name' => 'Themes',
                'description' => 'View and manage installed themes'
            ),
            array(
                'route' => 'Cms:Admin:SitemapLinks@indexAction',
                'name' => 'Sitemap links',
                'description' => 'View site map links that can be used to submit your site to search engines'
            ),
            array(
                'route' => 'Cms:Admin:Info@indexAction',
                'name' => 'System info',
                'description' => 'View server configuration'
            )
        )
    )
);