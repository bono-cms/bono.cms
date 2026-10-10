<?php

return [
	'production' => false,
	'timezone' => $_ENV['timezone'],
	/**
	 * Framework components configuration
	 */
	'components' => [
        'auth_manager' => [
            'secret_key' => $_ENV['auth_secret']
        ],
        
        // Module Manager configuration
        'module_manager' => [
            'core_modules' => [
                'Cms', 
                'Pages', 
            ]
        ],
        
        /**
         * Configuration service
         */
        'config' => [
            'adapter' => 'sql',
            'options' => [
                'connection' => 'mysql',
                'table' => 'bono_config'
            ]
        ],

		/**
		 * CAPTCHA configuration
		 */
		'captcha' => [
			'type' => 'standard',
			'options' => [
				'text' => 'math'
			]
		],

		/**
		 * Router configuration
		 */
        'router' => [
			'default' => 'Site:Main@notFoundAction',
            'ssl' => false
		],

        /**
		 * Configuration for view manager
		 */
		'view' => include __DIR__ . '/view.config.php',

		/**
		 * Translator configuration
		 */
		'translator' => [
			// Default site language
			'default' => null
		],

		/**
		 * Param bag which holds application-level parameters
		 * These values can be accessed in controllers, like $this->paramBag->get(..key..)
		 */
		'paramBag' => [
			'version' => '1.3', // Current CMS version
			'wysiwyg' => 'ckeditor',
			'site' => 'http://bono-cms.dev', // Vendor website
            'admin_language' => $_ENV['admin_language'], // Administration language
            'admin_segment' => 'admin', // The identification segment to be used to enter administration area
            'home_controller' => null // Can be overridden, for example to "Blog:Home@indexAction"
		],

		/**
		 * Form validation component. It has two options only
		 */
		'validator' => [
			'render' => 'JsonCollection',
		],

		/**
		 * Database component provider
		 * It needs to be configured here and accessed in mappers
		 * 
		 * Like this: $this->db->...
		 */
		'db' => [
			'mysql' => $_ENV['mysql']
		],

		/**
		 * MapperFactory which relies on previous db section
		 */
		'mapperFactory' => [
			'connection' => 'mysql',
            'prefix' => ''
		],
		
		/**
		 * Pagination component used in data mappers. 
		 * It's completely independent from a storage layer (be it SQL, or No-SQL, or pure array)
		 * and can be used as a standalone component as well.
		 */
		'paginator' => [
			'style' => 'Digg',
		]
	]
];