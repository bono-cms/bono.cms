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
			'/css/bootstrap.min.css',
            'http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800',
            'http://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic',
            '/font-awesome/css/font-awesome.min.css',
            '/css/animate.min.css',
            '/css/creative.css'
		),
		'scripts' => array(
			'/js/jquery.js',
			'/js/bootstrap.min.js',
			'/js/jquery.easing.min.js',
			'/js/jquery.fittext.js',
			'/js/wow.min.js',
			'/js/creative.js'
		)
	)
);
