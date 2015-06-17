<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

use Menu\View\BootstrapDropdown;

return array(
	// Menu classes configuration
	'menu' => array(
		// Top menu
		'top' => new BootstrapDropdown(),
		//'nav' => new Dropdown(),
		//'partners' => new Dropdown(),
	),
	
	'plugins' => array('to-top'),
	
	// Stylesheets and scripts
	'theme' => array(
		'stylesheets' => array(
			'/css/cosmo.min.css',
			'/css/blog.css',
		),
		'scripts' => array(
			'/js/jquery.min.js',
			'/js/bootstrap.min.js',
		)
	)
);
