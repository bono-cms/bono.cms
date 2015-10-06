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
	
	'/captcha/render/(:var)' => array(
		'controller' => 'Captcha@renderAction'
	),
	
	// For changing language
	'/lang/(:var)' => array(
		'controller' => 'Main@changeLanguageAction'
	),
	
	'/(:var)/' => array(
		'controller' => 'Main@slugAction'
	),

	'/(:var)/page/(:var)' => array(
		'controller' => 'Main@slugAction'
	),
	
	'/' => array(
		'controller' => 'Main@homeAction'
	),
	
	'/(:var)/(:var)/' => array(
		'controller' => 'Main@slugLanguageAwareAction'
	),
	
	'/(:var)/(:var)/page/(:var)' => array(
		'controller' => 'Main@slugLanguageAwareAction'
	),
	
	'/test' => array(
		'controller' => 'Main@testAction'
	)
);
