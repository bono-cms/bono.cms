<?php

return [
    'theme' => 'welcome', // Default theme if non defined
    'obfuscate' => $_ENV['obfuscate'],

    // Global template plugins
    'plugins' => [
        // Global plugins for all site templates
        'site' => [
            'scripts' => [
                '@Site/global.js',
            ]
        ],
        // Improved plugin for dropdowns
        'chosen' => [
            'stylesheets' => [
                'https://cdn.jsdelivr.net/npm/chosen-js@1.8.7/chosen.min.css',
            ],
            'scripts' => [
                'https://cdn.jsdelivr.net/npm/chosen-js@1.8.7/chosen.jquery.min.js'
            ]
        ],
        'lightbox' => [
            'stylesheets' => [
                'https://cdn.jsdelivr.net/npm/lightbox2@2.11.4/dist/css/lightbox.min.css',
            ],
            'scripts' => [
                'https://cdn.jsdelivr.net/npm/lightbox2@2.11.4/dist/js/lightbox.min.js'
            ]
        ],
        'to-top' => [
            'stylesheets' => [
                '@Cms/plugins/to-top/to-top.min.css'
            ],
            'scripts' => [
                '@Cms/plugins/to-top/to-top.min.js'
            ]
        ],
        'preview' => [
            'scripts' => [
                '@Cms/plugins/preview/jquery.preview.js'
            ],
            'stylesheets' => [
                '@Cms/plugins/preview/jquery.preview.css'
            ]
        ],
        'datetimepicker' => [
            'scripts' => [
                '@Cms/plugins/datetimepicker/js/moment.min.js',
                '@Cms/plugins/datetimepicker/js/jquery.datetimepicker.full.min.js'
            ],
            'stylesheets' => [
                '@Cms/plugins/datetimepicker/css/jquery.datetimepicker.min.css'
            ]
        ],
        'datepicker' => [
            'scripts' => [
                '@Cms/plugins/datepicker/js/bootstrap-datepicker.min.js'
            ],
            'stylesheets' => [
                '@Cms/plugins/datepicker/css/datepicker.min.css'
            ]
        ],
        'jquery' => [
            'scripts' => [
                'https://cdn.jsdelivr.net/npm/jquery@3.3.1/dist/jquery.min.js'
            ]
        ],
        'ckeditor' => [
            'scripts' => [
                '@Cms/plugins/ckeditor/ckeditor.js'
            ]
        ],
        'admin' => [
            'scripts' => [
                '@Cms/plugins/jquery.form.js',
            ],

            'stylesheets' => [
                '@Cms/css/style.css'
            ]
        ],
        'zoom' => [
            'scripts' => [
                '@Site/plugins/elevatezoom/jquery.elevateZoom-3.0.8.min.js'
            ]
        ],
        'famfam-flag' => [
            'stylesheets' => [
                '@Site/plugins/famfam-flag/famfamfam-flags.min.css'
            ],
        ],
        'bootstrap' => [
            'stylesheets' => [
                'https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css',
                'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css'
            ],
            'scripts' => [
                'https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js'
            ]
        ],
        'font-awesome-5' => [
            'stylesheets' => [
                'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css'
            ]
        ],
        'jquery.mCustomScrollbar' => [
            'stylesheets' => [
                '@Cms/plugins/jquery.mCustomScrollbar/jquery.mCustomScrollbar.min.css'
            ],

            'scripts' => [
                '@Cms/plugins/jquery.mCustomScrollbar/jquery.mCustomScrollbar.concat.min.js'
            ]
        ]
    ]
];