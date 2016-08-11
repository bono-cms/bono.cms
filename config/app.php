<?php

return array(

	'production' => false,
	'timezone' => 'UTC',

	/**
	 * Framework components configuration
	 */
	'components' => array(
        // Module Manager configuration
        'module_manager' => array(
            'core_modules' => array(
                'Cms', 
                'Pages', 
                'Menu'
            )
        ),
        
        /**
         * Configuration service
         */
        'config' => array(
            'adapter' => 'sql',
            'options' => array(
                'connection' => 'mysql',
                'table' => 'bono_config'
            )
        ),

		/**
		 * CAPTCHA configuration
		 */
		'captcha' => array(
			'type' => 'standard',
			'options' => array(
				'text' => 'math'
			)
		),

		/**
		 * Router configuration
		 */
		'router' => array(
			'default' => 'Site:Main@notFoundAction',
		),

		/**
		 * Configuration for view manager
		 */
		'view' => include(__DIR__ . '/view.php'),

		/**
		 * Translator configuration
		 */
		'translator' => array(
			// Default site language
			'default' => null
		),

		/**
		 * Param bag which holds application-level parameters
		 * This values can be accessed in controllers, like $this->paramBag->get(..key..)
		 */
		'paramBag' => array(
			'version' => '1.3', // Current CMS version
			'wysiwyg' => 'ckeditor',
			'site' => 'http://bono-cms.ml', // Vendor website
            'admin_language' => $_ENV['admin_language'], // Administration language
            'admin_segment' => 'admin', // The identification segment to be used to enter administration area
            'home_controller' => null // Can be overridden, for example to "Blog:Home@indexAction"
		),

		/**
		 * Form validation component. It has two options only
		 */
		'validator' => array(
			'render' => 'JsonCollection',
		),

		/**
		 * Database component provider
		 * It needs to be configured here and accessed in mappers
		 * 
		 * Like this: $this->db->...
		 */
		'db' => array(
			'mysql' => $_ENV['mysql']
		),

		/**
		 * MapperFactory which relies on previous db section
		 */
		'mapperFactory' => array(
			'connection' => 'mysql',
            'prefix' => ''
		),
		
		/**
		 * Pagination component used in data mappers. 
		 * It's completely independent from a storage layer (be it SQL, or No-SQL, or pure array)
		 * and can be used as a standalone component as well.
		 */
		'paginator' => array(
			'style' => 'Digg',
		)
	)
);
