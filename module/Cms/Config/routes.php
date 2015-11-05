<?php/** * This file is part of the Bono CMS *  * Copyright (c) No Global State Lab *  * For the full copyright and license information, please view * the license file that was distributed with this source code. */return array(        '/admin'=> array(        'controller' => 'Admin:Dashboard@indexAction',    ),        '/admin/kernel/install-module.ajax' => array(        'controller' => 'Admin:Dashboard@installModuleAction'    ),        '/admin/kernel/generate-slug' => array(        'controller' => 'Admin:Dashboard@slugAction'    ),        '/admin/kernel/mode-change' => array(        'controller' => 'Admin:Dashboard@changeModeAction'    ),        '/admin/kernel/theme-change' => array(        'controller' => 'Admin:Dashboard@changeThemeAction'    ),        '/admin/kernel/items-per-page'  => array(        'controller' => 'Admin:Dashboard@itemsPerPageChangeAction'    ),        '/admin/login' => array(        'controller' => 'Admin:Auth@indexAction'    ),        '/admin/login.ajax' => array(        'controller' => 'Admin:Auth@loginAction'    ),        '/admin/logout' =>  array(        'controller' => 'Admin:Auth@logoutAction'    ),        // Tweaks    '/admin/tweaks' => array(        'controller' => 'Admin:Tweaks@indexAction',        'disallow' => array('guest', 'user')    ),        '/admin/tweaks.ajax' => array(        'controller' => 'Admin:Tweaks@saveAction',        'disallow' => array('guest', 'user')    ),            // Users    '/admin/users' => array(        'controller' => 'Admin:Users:Browser@indexAction',        'disallow' => array('guest', 'user')    ),        '/admin/users/add' => array(        'controller' => 'Admin:Users:Add@indexAction',        'disallow' => array('guest', 'user')    ),        '/admin/users/add.ajax' => array(        'controller' => 'Admin:Users:Add@addAction',        'disallow' => array('guest', 'user')    ),        '/admin/users/edit/(:var)' => array(        'controller'    => 'Admin:Users:Edit@indexAction',        'disallow' => array('guest')    ),        '/admin/users/edit.ajax' => array(        'controller'    => 'Admin:Users:Edit@updateAction',        'disallow' => array('guest', 'user')    ),        '/admin/users/delete.ajax' => array(        'controller' => 'Admin:Users:Browser@deleteAction',        'ajax' => true,        'method' => 'POST'    ),        // Languages    '/admin/languages' => array(        'controller' => 'Admin:Languages:Browser@indexAction',        'disallow' => array('guest', 'user')    ),        '/admin/languages/add' => array(        'controller' => 'Admin:Languages:Add@indexAction',        'disallow' => array('guest', 'user')    ),    '/admin/languages/add.ajax' => array(        'controller' => 'Admin:Languages:Add@addAction',        'disallow' => array('guest', 'user')    ),    '/admin/languages/edit/(:var)' => array(        'controller' => 'Admin:Languages:Edit@indexAction',        'disallow' => array('guest', 'user')    ),    '/admin/languages/edit.ajax' => array(        'controller' => 'Admin:Languages:Edit@updateAction',        'disallow' => array('guest', 'user')    ),        '/admin/languages/delete.ajax' => array(        'controller' => 'Admin:Languages:Browser@deleteAction',        'disallow' => array('guest', 'user')    ),        '/admin/languages/save.ajax' => array(        'controller' => 'Admin:Languages:Browser@saveAction',        'disallow' => array('guest', 'user')    ),        '/admin/languages/change.ajax' => array(        'controller' => 'Admin:Languages:Browser@changeAction'    ),        // Notifications    '/admin/notifications' => array(        'controller' => 'Admin:Notifications@indexAction'    ),        '/admin/notifications/page/(:var)' => array(        'controller' => 'Admin:Notifications@indexAction'    ),        '/admin/notifications/delete.ajax' => array(        'controller' => 'Admin:Notifications@deleteAction'    ),        '/admin/notifications/clear.ajax' => array(        'controller' => 'Admin:Notifications@clearAction'    ),        // Info    '/admin/info' => array(        'controller' => 'Admin:Info@indexAction',        'disallow' => array('guest', 'user')    ),        // History    '/admin/history/clear.ajax' => array(        'controller' => 'Admin:History@clearAction',        'disallow' => array('user')    ),        '/admin/history' => array(        'controller' => 'Admin:History@indexAction'    ),        '/admin/history/page/(:var)' => array(        'controller' => 'Admin:History@indexAction'    ),        // Notepad    '/admin/notepad' => array(        'controller' => 'Admin:Notepad@indexAction'    ),        '/admin/notepad.ajax' => array(        'controller' => 'Admin:Notepad@saveAction',        'disallow' => array('guest')    ),);