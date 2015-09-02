<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

use Menu\View\Dropdown;

return array(

	// Menu classes configuration
	'menu' => array(
		// Top menu
		'top' => new Dropdown(array(
			'class' => array(
				'base' => 'sf-menu',
			)
		)),
		
		'nav' => new Dropdown(),
		'partners' => new Dropdown(),
	),
	
	// Those plugins are defined in /config/view.php
	// And have higher priority
	'plugins' => array(
		'jquery', 
		'superfish', 
		'to-top', 
		'bootstrap.blue', 
		'bootstrap.core'
	),
	
	// Style sheets and scripts for this theme to load
	'theme' => array(
		'stylesheets' => array(
			'/css/styles.css',
		),
		'scripts' => array(
			'/js/app.js'
		)
	)
);
