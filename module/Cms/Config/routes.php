<?php

return [

    '/install' => [
        'controller' => 'Install:Install@indexAction'
    ],
    
    '/install.ajax' => [
        'controller' => 'Install:Install@installAction'
    ],
    
    '/install/ready' => [
        'controller' => 'Install:Install@readyAction'
    ],
    
    '/%s'=> [
        'controller' => 'Admin:Dashboard@indexAction',
    ],
    
    '/%s/kernel/install-module.ajax' => [
        'controller' => 'Admin:Dashboard@installModuleAction'
    ],
    
    '/%s/kernel/generate-slug' => [
        'controller' => 'Admin:Dashboard@slugAction'
    ],
    
    '/%s/kernel/mode-change' => [
        'controller' => 'Admin:Dashboard@changeModeAction'
    ],
    
    '/%s/kernel/theme-change' => [
        'controller' => 'Admin:Dashboard@changeThemeAction'
    ],
    
    '/%s/kernel/items-per-page'  => [
        'controller' => 'Admin:Dashboard@itemsPerPageChangeAction'
    ],
    
    '/%s/login' => [
        'controller' => 'Admin:Auth@indexAction'
    ],
    
    '/%s/login.ajax' => [
        'controller' => 'Admin:Auth@loginAction'
    ],
    
    '/%s/logout' =>  [
        'controller' => 'Admin:Auth@logoutAction'
    ],
    
    // Tweaks
    '/%s/tweaks' => [
        'controller' => 'Admin:Tweaks@indexAction',
        'disallow' => ['guest', 'user']
    ],
    
    '/%s/tweaks.ajax' => [
        'controller' => 'Admin:Tweaks@saveAction',
        'disallow' => ['guest', 'user']
    ],
    
    
    // Users
    '/%s/users' => [
        'controller' => 'Admin:Users@indexAction',
        'disallow' => ['guest', 'user']
    ],
    
    '/%s/users/add' => [
        'controller' => 'Admin:Users@addAction',
        'disallow' => ['guest', 'user']
    ],
    
    '/%s/users/edit/(:var)' => [
        'controller'    => 'Admin:Users@editAction',
        'disallow' => ['guest']
    ],
    
    '/%s/users/save' => [
        'controller'    => 'Admin:Users@saveAction',
        'disallow' => ['guest', 'user']
    ],
    
    '/%s/users/delete/(:var)' => [
        'controller' => 'Admin:Users@deleteAction',
        'ajax' => true,
        'method' => 'POST'
    ],

    '/%s/users/wipe' => [
        'controller' => 'Admin:Users@wipeAction',
    ],

    // Languages
    '/%s/languages' => [
        'controller' => 'Admin:Languages@indexAction',
        'disallow' => ['guest', 'user']
    ],
    
    '/%s/languages/add' => [
        'controller' => 'Admin:Languages@addAction',
        'disallow' => ['guest', 'user']
    ],

    '/%s/languages/edit/(:var)' => [
        'controller' => 'Admin:Languages@editAction',
        'disallow' => ['guest', 'user']
    ],

    '/%s/languages/save' => [
        'controller' => 'Admin:Languages@saveAction',
        'disallow' => ['guest', 'user']
    ],
    
    '/%s/languages/delete/(:var)' => [
        'controller' => 'Admin:Languages@deleteAction',
        'disallow' => ['guest', 'user']
    ],
    
    '/%s/languages/tweak' => [
        'controller' => 'Admin:Languages@tweakAction',
        'disallow' => ['guest', 'user']
    ],
    
    '/%s/languages/change.ajax' => [
        'controller' => 'Admin:Languages@changeAction'
    ],
    
    // Notifications
    '/%s/notifications' => [
        'controller' => 'Admin:Notifications@indexAction'
    ],
    
    '/%s/notifications/page/(:var)' => [
        'controller' => 'Admin:Notifications@indexAction'
    ],
    
    '/%s/notifications/delete/(:var)' => [
        'controller' => 'Admin:Notifications@deleteAction'
    ],
    
    '/%s/notifications/clear' => [
        'controller' => 'Admin:Notifications@clearAction'
    ],

    // Info
    '/%s/sitemap-links' => [
        'controller' => 'Admin:SitemapLinks@indexAction',
        'disallow' => ['guest', 'user']
    ],

    '/%s/sitemap-links/save' => [
        'controller' => 'Admin:SitemapLinks@saveAction',
        'disallow' => ['guest']
    ],
    
    '/%s/sitemap-links/robots' => [
        'controller' => 'Admin:SitemapLinks@robotsAction',
        'disallow' => ['guest']
    ],
    
    // Info
    '/%s/info' => [
        'controller' => 'Admin:Info@indexAction',
        'disallow' => ['guest', 'user']
    ],
    
    // History
    '/%s/history/clear' => [
        'controller' => 'Admin:History@clearAction',
        'disallow' => ['user']
    ],
    
    '/%s/history' => [
        'controller' => 'Admin:History@indexAction'
    ],
    
    '/%s/history/view/page/(:var)' => [
        'controller' => 'Admin:History@indexAction'
    ],
    
    // Notepad
    '/%s/notepad' => [
        'controller' => 'Admin:Notepad@indexAction'
    ],
    
    '/%s/notepad/save' => [
        'controller' => 'Admin:Notepad@saveAction',
        'disallow' => ['guest']
    ],
    
    // Module manager
    '/%s/module-manager' => [
        'controller' => 'Admin:ModuleManager@indexAction',
        'disallow' => ['guest']
    ],
    
    '/%s/module-manager/delete/(:var)' => [
        'controller' => 'Admin:ModuleManager@deleteAction',
        'disallow' => ['guest', 'user']
    ],

    '/%s/module-manager/delete-many' => [
        'controller' => 'Admin:ModuleManager@deleteManyAction',
        'disallow' => ['guest', 'user']
    ],
    
    // Themes
    '/%s/themes' => [
        'controller' => 'Admin:Themes@indexAction',
        'disallow' => ['guest', 'user']
    ],
    
    '/%s/themes/tweak' => [
        'controller' => 'Admin:Themes@tweakAction',
        'disallow' => ['guest', 'user']
    ],
    
    '/%s/themes/delete/(:var)' => [
        'controller' => 'Admin:Themes@deleteAction',
        'disallow' => ['guest', 'user']
    ],
    
    '/%s/themes/delete-many' => [
        'controller' => 'Admin:Themes@deleteManyAction',
        'disallow' => ['guest', 'user']
    ]
];