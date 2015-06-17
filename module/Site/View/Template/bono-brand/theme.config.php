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
use Menu\View\BootstrapDropdown;

return array(

	'menu' => array(
		'brand-main' => new Dropdown(),
		'brand-top' => new BootstrapDropdown(),
	),
	
	'plugins' => array('jquery', 'to-top'),

	// Stylesheets and scripts to be loaded when accessing plugin bag
	'theme' => array(
		'stylesheets' => array(
			'/css/cosmo.min.css',
			'/css/carousel.css',
		),

		'scripts' => array(
			'/js/bootstrap.min.js',
		)
	)
);
