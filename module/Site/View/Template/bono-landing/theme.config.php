<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

use Menu\View\BootstrapDropdown;

return array(

	// Menu classes configuration
	'menu' => array(
		'top' => new BootstrapDropdown(),
	),
	
	'plugins' => array(
		// No plugins loaded for this theme
	),
	
	// Stylesheets and scripts
	'theme' => array(
		'stylesheets' => array(
			//
		),
		'scripts' => array(
			'js/jquery.js',
			'js/bootstrap.min.js',
			'js/jquery.easing.min.js',
			'js/jquery.fittext.js',
			'js/wow.min.js',
			'js/creative.js'
		)
	)
);
