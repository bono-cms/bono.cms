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
    
    '/admin'=> array(
        'controller' => 'Admin:Dashboard@indexAction',
    ),
    
    '/admin/kernel/install-module.ajax' => array(
        'controller' => 'Admin:Dashboard@installModuleAction'
    ),
    
    '/admin/kernel/generate-slug' => array(
        'controller' => 'Admin:Dashboard@slugAction'
    ),
    
    '/admin/kernel/mode-change' => array(
        'controller' => 'Admin:Dashboard@changeModeAction'
    ),
    
    '/admin/kernel/theme-change' => array(
        'controller' => 'Admin:Dashboard@changeThemeAction'
    ),
    
    '/admin/kernel/items-per-page'  => array(
        'controller' => 'Admin:Dashboard@itemsPerPageChangeAction'
    ),
    
    '/admin/login' => array(
        'controller' => 'Admin:Auth@indexAction'
    ),
    
    '/admin/login.ajax' => array(
        'controller' => 'Admin:Auth@loginAction'
    ),
    
    '/admin/logout' =>  array(
        'controller' => 'Admin:Auth@logoutAction'
    ),
    
    // Tweaks
    '/admin/tweaks' => array(
        'controller' => 'Admin:Tweaks@indexAction',
        'disallow' => array('guest', 'user')
    ),
    
    '/admin/tweaks.ajax' => array(
        'controller' => 'Admin:Tweaks@saveAction',
        'disallow' => array('guest', 'user')
    ),
    
    
    // Users
    '/admin/users' => array(
        'controller' => 'Admin:Users@gridAction',
        'disallow' => array('guest', 'user')
    ),
    
    '/admin/users/add' => array(
        'controller' => 'Admin:Users@addAction',
        'disallow' => array('guest', 'user')
    ),
    
    '/admin/users/edit/(:var)' => array(
        'controller'    => 'Admin:Users@editAction',
        'disallow' => array('guest')
    ),
    
    '/admin/users/save' => array(
        'controller'    => 'Admin:Users@saveAction',
        'disallow' => array('guest', 'user')
    ),
    
    '/admin/users/delete/(:var)' => array(
        'controller' => 'Admin:Users@deleteAction',
        'ajax' => true,
        'method' => 'POST'
    ),
    
    // Languages
    '/admin/languages' => array(
        'controller' => 'Admin:Languages@gridAction',
        'disallow' => array('guest', 'user')
    ),
    
    '/admin/languages/add' => array(
        'controller' => 'Admin:Languages@addAction',
        'disallow' => array('guest', 'user')
    ),

    '/admin/languages/edit/(:var)' => array(
        'controller' => 'Admin:Languages@editAction',
        'disallow' => array('guest', 'user')
    ),

    '/admin/languages/save' => array(
        'controller' => 'Admin:Languages@saveAction',
        'disallow' => array('guest', 'user')
    ),
    
    '/admin/languages/delete' => array(
        'controller' => 'Admin:Languages@deleteAction',
        'disallow' => array('guest', 'user')
    ),
    
    '/admin/languages/tweak' => array(
        'controller' => 'Admin:Languages@tweakAction',
        'disallow' => array('guest', 'user')
    ),
    
    '/admin/languages/change.ajax' => array(
        'controller' => 'Admin:Languages@changeAction'
    ),
    
    // Notifications
    '/admin/notifications' => array(
        'controller' => 'Admin:Notifications@indexAction'
    ),
    
    '/admin/notifications/page/(:var)' => array(
        'controller' => 'Admin:Notifications@indexAction'
    ),
    
    '/admin/notifications/delete/(:var)' => array(
        'controller' => 'Admin:Notifications@deleteAction'
    ),
    
    '/admin/notifications/clear' => array(
        'controller' => 'Admin:Notifications@clearAction'
    ),
    
    // Info
    '/admin/info' => array(
        'controller' => 'Admin:Info@indexAction',
        'disallow' => array('guest', 'user')
    ),
    
    // History
    '/admin/history/clear' => array(
        'controller' => 'Admin:History@clearAction',
        'disallow' => array('user')
    ),
    
    '/admin/history' => array(
        'controller' => 'Admin:History@indexAction'
    ),
    
    '/admin/history/page/(:var)' => array(
        'controller' => 'Admin:History@indexAction'
    ),
    
    // Notepad
    '/admin/notepad' => array(
        'controller' => 'Admin:Notepad@indexAction'
    ),
    
    '/admin/notepad/save' => array(
        'controller' => 'Admin:Notepad@saveAction',
        'disallow' => array('guest')
    ),
    
    '/admin/module-manager' => array(
        'controller' => 'Admin:ModuleManager@indexAction',
        'disallow' => array('guest')
    )
);
