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
    
    '/install' => array(
        'controller' => 'Install:Install@indexAction'
    ),
    
    '/install.ajax' => array(
        'controller' => 'Install:Install@installAction'
    ),
    
    '/install/ready' => array(
        'controller' => 'Install:Install@readyAction'
    )
);
