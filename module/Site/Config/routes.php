<?php

/**
 * This file is part of the Bono CMS
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

return [
    '/captcha/render/(:var)' => [
        'controller' => 'Main@captchaAction'
    ],
    
    // For changing language
    '/lang/(:var)' => [
        'controller' => 'Main@changeLanguageAction'
    ],
    
    '/(:var)/' => [
        'controller' => 'Main@slugAction'
    ],

    '/(:var)/page/(:var)' => [
        'controller' => 'Main@slugAction'
    ],
    
    '/' => [
        'controller' => 'Main@homeAction'
    ],

    '/(:var)/(:var)/' => [
        'controller' => 'Main@slugLanguageAwareAction'
    ],
    
    '/(:var)/(:var)/page/(:var)' => [
        'controller' => 'Main@slugLanguageAwareAction'
    ],

    // Sitemap
    '/sitemap' => [
        'controller' => 'Sitemap@indexAction'
    ],
    
    '/sitemap/lang/(:var)' => [
        'controller' => 'Sitemap@indexAction'
    ],

    '/test' => [
        'controller' => 'Main@testAction'
    ]
];