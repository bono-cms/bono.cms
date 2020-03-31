<?php

return array(

    '/install' => array(
        'controller' => 'Install:Install@indexAction'
    ),
    
    '/install.ajax' => array(
        'controller' => 'Install:Install@installAction'
    ),
    
    '/install/ready' => array(
        'controller' => 'Install:Install@readyAction'
    ),
    
    '/%s'=> array(
        'controller' => 'Admin:Dashboard@indexAction',
    ),
    
    '/%s/kernel/install-module.ajax' => array(
        'controller' => 'Admin:Dashboard@installModuleAction'
    ),
    
    '/%s/kernel/generate-slug' => array(
        'controller' => 'Admin:Dashboard@slugAction'
    ),
    
    '/%s/kernel/mode-change' => array(
        'controller' => 'Admin:Dashboard@changeModeAction'
    ),
    
    '/%s/kernel/theme-change' => array(
        'controller' => 'Admin:Dashboard@changeThemeAction'
    ),
    
    '/%s/kernel/items-per-page'  => array(
        'controller' => 'Admin:Dashboard@itemsPerPageChangeAction'
    ),
    
    '/%s/login' => array(
        'controller' => 'Admin:Auth@indexAction'
    ),
    
    '/%s/login.ajax' => array(
        'controller' => 'Admin:Auth@loginAction'
    ),
    
    '/%s/logout' =>  array(
        'controller' => 'Admin:Auth@logoutAction'
    ),
    
    // Tweaks
    '/%s/tweaks' => array(
        'controller' => 'Admin:Tweaks@indexAction',
        'disallow' => array('guest', 'user')
    ),
    
    '/%s/tweaks.ajax' => array(
        'controller' => 'Admin:Tweaks@saveAction',
        'disallow' => array('guest', 'user')
    ),
    
    
    // Users
    '/%s/users' => array(
        'controller' => 'Admin:Users@indexAction',
        'disallow' => array('guest', 'user')
    ),
    
    '/%s/users/add' => array(
        'controller' => 'Admin:Users@addAction',
        'disallow' => array('guest', 'user')
    ),
    
    '/%s/users/edit/(:var)' => array(
        'controller'    => 'Admin:Users@editAction',
        'disallow' => array('guest')
    ),
    
    '/%s/users/save' => array(
        'controller'    => 'Admin:Users@saveAction',
        'disallow' => array('guest', 'user')
    ),
    
    '/%s/users/delete/(:var)' => array(
        'controller' => 'Admin:Users@deleteAction',
        'ajax' => true,
        'method' => 'POST'
    ),

    '/%s/users/wipe' => array(
        'controller' => 'Admin:Users@wipeAction',
    ),

    // Languages
    '/%s/languages' => array(
        'controller' => 'Admin:Languages@indexAction',
        'disallow' => array('guest', 'user')
    ),
    
    '/%s/languages/add' => array(
        'controller' => 'Admin:Languages@addAction',
        'disallow' => array('guest', 'user')
    ),

    '/%s/languages/edit/(:var)' => array(
        'controller' => 'Admin:Languages@editAction',
        'disallow' => array('guest', 'user')
    ),

    '/%s/languages/save' => array(
        'controller' => 'Admin:Languages@saveAction',
        'disallow' => array('guest', 'user')
    ),
    
    '/%s/languages/delete/(:var)' => array(
        'controller' => 'Admin:Languages@deleteAction',
        'disallow' => array('guest', 'user')
    ),
    
    '/%s/languages/tweak' => array(
        'controller' => 'Admin:Languages@tweakAction',
        'disallow' => array('guest', 'user')
    ),
    
    '/%s/languages/change.ajax' => array(
        'controller' => 'Admin:Languages@changeAction'
    ),
    
    // Notifications
    '/%s/notifications' => array(
        'controller' => 'Admin:Notifications@indexAction'
    ),
    
    '/%s/notifications/page/(:var)' => array(
        'controller' => 'Admin:Notifications@indexAction'
    ),
    
    '/%s/notifications/delete/(:var)' => array(
        'controller' => 'Admin:Notifications@deleteAction'
    ),
    
    '/%s/notifications/clear' => array(
        'controller' => 'Admin:Notifications@clearAction'
    ),

    // Info
    '/%s/sitemap-links' => array(
        'controller' => 'Admin:SitemapLinks@indexAction',
        'disallow' => array('guest', 'user')
    ),

    '/%s/sitemap-links/ping' => array(
        'controller' => 'Admin:SitemapLinks@pingAction',
        'disallow' => array('guest')
    ),

    '/%s/sitemap-links/save' => array(
        'controller' => 'Admin:SitemapLinks@saveAction',
        'disallow' => array('guest')
    ),
    
    // Info
    '/%s/info' => array(
        'controller' => 'Admin:Info@indexAction',
        'disallow' => array('guest', 'user')
    ),
    
    // History
    '/%s/history/clear' => array(
        'controller' => 'Admin:History@clearAction',
        'disallow' => array('user')
    ),
    
    '/%s/history' => array(
        'controller' => 'Admin:History@indexAction'
    ),
    
    '/%s/history/view/page/(:var)' => array(
        'controller' => 'Admin:History@indexAction'
    ),
    
    // Notepad
    '/%s/notepad' => array(
        'controller' => 'Admin:Notepad@indexAction'
    ),
    
    '/%s/notepad/save' => array(
        'controller' => 'Admin:Notepad@saveAction',
        'disallow' => array('guest')
    ),
    
    // Module manager
    '/%s/module-manager' => array(
        'controller' => 'Admin:ModuleManager@indexAction',
        'disallow' => array('guest')
    ),
    
    '/%s/module-manager/delete/(:var)' => array(
        'controller' => 'Admin:ModuleManager@deleteAction',
        'disallow' => array('guest', 'user')
    ),

    '/%s/module-manager/delete-many' => array(
        'controller' => 'Admin:ModuleManager@deleteManyAction',
        'disallow' => array('guest', 'user')
    ),
    
    // Themes
    '/%s/themes' => array(
        'controller' => 'Admin:Themes@indexAction',
        'disallow' => array('guest', 'user')
    ),
    
    '/%s/themes/tweak' => array(
        'controller' => 'Admin:Themes@tweakAction',
        'disallow' => array('guest', 'user')
    ),
    
    '/%s/themes/delete/(:var)' => array(
        'controller' => 'Admin:Themes@deleteAction',
        'disallow' => array('guest', 'user')
    ),
    
    '/%s/themes/delete-many' => array(
        'controller' => 'Admin:Themes@deleteManyAction',
        'disallow' => array('guest', 'user')
    )
);
