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
            'ssl' => false
		),

        /**
		 * Configuration for view manager
		 */
		'view' => array(
            'theme' => 'welcome', // Default theme if non defined
            'obfuscate' => true,

            // Global template plugins
            'plugins' => array(
                // Global plugins for all site templates
                'site' => array(
                    'scripts' => array(
                        '@Site/global.js',
                    )
                ),

                // Improved plugin for dropdowns
                'chosen' => array(
                    'stylesheets' => array(
                        '@Cms/plugins/chosen/chosen.css',
                        '@Cms/plugins/chosen/chosen-bootstrap.css'
                    ),
                    'scripts' => array(
                        '@Cms/plugins/chosen/chosen.jquery.min.js'
                    )
                ),

                'lightbox' => array(
                    'stylesheets' => array(
                        '@Cms/plugins/lightbox/css/lightbox.css',
                    ),
                    
                    'scripts' => array(
                        '@Cms/plugins/lightbox/js/lightbox.min.js'
                    )
                ),

                'superfish' => array(
                    'stylesheets' => array(
                        '@Site/plugins/superfish/dist/css/superfish.css'
                    ),

                    'scripts' => array(
                        '@Site/plugins/superfish/dist/js/hoverIntent.js',
                        '@Site/plugins/superfish/dist/js/superfish.js'
                    )
                ),

                'to-top' => array(
                    'stylesheets' => array(
                        '@Cms/plugins/to-top/to-top.min.css'
                    ),
                    'scripts' => array(
                        '@Cms/plugins/to-top/to-top.min.js'
                    )
                ),

                'bx-slider' => array(
                    'stylesheets' => array(
                        '@Site/plugins/bxslider/jquery.bxslider.css'
                    ),
                    'scripts' => array(
                        '@Site/plugins/bxslider/jquery.bxslider.min.js'
                    )
                ),

                'preview' => array(
                    'scripts' => array(
                        '@Cms/plugins/preview/jquery.preview.js'
                    ),
                    'stylesheets' => array(
                        '@Cms/plugins/preview/jquery.preview.css'
                    )
                ),

                'datetimepicker' => array(
                    'scripts' => array(
                        '@Cms/plugins/datetimepicker/js/moment.min.js',
                        '@Cms/plugins/datetimepicker/js/bootstrap-datetimepicker.min.js'
                    ),
                    'stylesheets' => array(
                        '@Cms/plugins/datetimepicker/css/bootstrap-datetimepicker.min.css'
                    )
                ),

                'datepicker' => array(
                    'scripts' => array(
                        '@Cms/plugins/datepicker/js/bootstrap-datepicker.min.js'
                    ),
                    'stylesheets' => array(
                        '@Cms/plugins/datepicker/css/datepicker.min.css'
                    )
                ),

                'jquery' =>	array(
                    'scripts' => array(
                        '@Cms/plugins/jquery/1.11.2/jquery.min.js'
                    )
                ),

                'ckeditor' => array(
                    'scripts' => array(
                        '@Cms/plugins/ckeditor/ckeditor.js'
                    )
                ),

                'admin' => array(
                    'scripts' => array(
                        '@Cms/plugins/jquery.form.js',
                        '@Cms/admin/app.js'
                    ),

                    'stylesheets' => array(
                        '@Cms/css/style.css'
                    )
                ),
                
                'zoom' => array(
                    'scripts' => array(
                        '@Site/plugins/elevatezoom/jquery.elevateZoom-3.0.8.min.js'
                    )
                ),

                'famfam-flag' => array(
                    'stylesheets' => array(
                        '@Site/plugins/famfam-flag/famfamfam-flags.min.css'
                    ),
                ),

                'bootstrap' => array(
                    'stylesheets' => array(
                        '@Cms/plugins/bootstrap-4/css/pulse.min.css'
                    ),

                    'scripts' => array(
                        '@Cms/plugins/bootstrap-4/js/bootstrap.bundle.min.js'
                    )
                ),

                'font-awesome-5' => array(
                    'stylesheets' => array(
                        '@Cms/plugins/font-awesome-5/css/all.min.css'
                    )
                ),

                'jquery.mCustomScrollbar' => array(
                    'stylesheets' => array(
                        '@Cms/plugins/jquery.mCustomScrollbar/jquery.mCustomScrollbar.min.css'
                    ),

                    'scripts' => array(
                        '@Cms/plugins/jquery.mCustomScrollbar/jquery.mCustomScrollbar.concat.min.js'
                    )
                )
            )
        ),

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
			'site' => 'http://bono-cms.com', // Vendor website
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
