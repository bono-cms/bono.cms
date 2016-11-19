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
        'color' => 'slategray',
        'icon' => 'fa fa-history fa-5x',
        'name' => 'History',
        'description' => 'History of latest actions'
    ),
    array(
        'route' => 'Cms:Admin:Notepad@indexAction',
        'icon' => 'fa fa-sticky-note-o fa-5x',
        'color' => 'blueviolet',
        'name' => 'Notepad',
        'description' => 'Keep your notes using notepad'
    ),
    array(
        'route' => 'Cms:Admin:Notifications@indexAction',
        'icon' => 'fa fa-child fa-5x',
        'color' => 'brown',
        'name' => 'Notifications',
        'description' => 'All system notifications'
    ),
);