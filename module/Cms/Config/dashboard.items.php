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
        'route' => 'Cms:Admin:History@indexAction',
        'icon' => 'icons/history.svg',
        'name' => 'History',
        'description' => 'History of latest actions'
    ),
    array(
        'route' => 'Cms:Admin:Notepad@indexAction',
        'icon' => 'icons/notepad.svg',
        'name' => 'Notepad',
        'description' => 'Keep your notes using notepad'
    ),
    array(
        'route' => 'Cms:Admin:Notifications@indexAction',
        'icon' => 'icons/notifications.svg',
        'name' => 'Notifications',
        'description' => 'All system notifications'
    )
);