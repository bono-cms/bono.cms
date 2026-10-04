<?php

/**
 * Engine configuration container
 */

return [
    'menu' => [
        'name' => 'Engine',
        'icon' => 'fas fa-truck-pickup',
        'items' => [
            [
                'route' => 'Cms:Admin:History@indexAction',
                'name' => 'History',
                'description' => 'History of latest actions'
            ],
            [
                'route' => 'Cms:Admin:Notifications@indexAction',
                'name' => 'Notifications',
                'description' => 'All system notifications'
            ],
            [
                'route' => 'Cms:Admin:Users@indexAction',
                'name' => 'Users',
                'description' => 'Edit users and their privileges'
            ],
            [
                'route' => 'Cms:Admin:Tweaks@indexAction',
                'name' => 'Tweaks',
                'description' => 'Tweaks of basic system configuration'
            ],
            [
                'route' => 'Cms:Admin:ModuleManager@indexAction',
                'name' => 'Module manager',
                'description' => 'Install or drop system modules'
            ],
            [
                'route' => 'Cms:Admin:Languages@indexAction',
                'name' => 'Languages',
                'description' => 'Tweak system languages'
            ],
            [
                'route' => 'Cms:Admin:Themes@indexAction',
                'name' => 'Themes',
                'description' => 'View and manage installed themes'
            ],
            [
                'route' => 'Cms:Admin:SitemapLinks@indexAction',
                'name' => 'Sitemap links',
                'description' => 'View site map links that can be used to submit your site to search engines'
            ],
            [
                'route' => 'Cms:Admin:Info@indexAction',
                'name' => 'System info',
                'description' => 'View server configuration'
            ]
        ]
    ]
];