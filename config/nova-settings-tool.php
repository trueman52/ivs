<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Settings Path
    |--------------------------------------------------------------------------
    |
    | Path to the JSON file where settings are stored.
    |
    */

    'path' => storage_path('app/settings.json'),

    /*
    |--------------------------------------------------------------------------
    | Sidebar Label
    |--------------------------------------------------------------------------
    |
    | The text that Nova displays for this tool in the navigation sidebar.
    |
    */

    'sidebar-label' => 'Others',

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    |
    | The good stuff :). Each setting defined here will render a field in the
    | tool. The only required key is `key`, other available keys include `type`,
    | `label`, `help`, `placeholder`, `language`, and `panel`.
    |
    */

    'settings' => [

        [
            'key' => 'amount',
            'label' => 'Amount (SGD)',
            'type' => 'number',
            'help' => 'Maximum Booking Amount To Allow Online Payment',
        ],
        
        [
            'key' => 'days',
            'label' => 'Days',
            'type' => 'number',
            'help' => 'Bank Verification Allow Window',
        ],
        
        [
            'key' => 'gst',
            'label' => 'Percentage',
            'type' => 'number',
            'help' => 'GST',
        ],

//        [
//            'key' => 'feature_42',
//            'label' => 'Feature 42',
//            'type' => 'toggle',
//            'help' => 'For the upcoming release. <a href="/docs#feature_42">Read more here.</a>',
//        ],
//
//        [
//            'key' => 'welcome',
//            'label' => 'Welcome Message',
//            'type' => 'textarea',
//            'help' => 'Greeting for new users on their first login.',
//        ],
//
//        [
//            'key' => 'snippet',
//            'label' => 'Tracking Snippet',
//            'type' => 'code',
//            'language' => 'htmlmixed',
//            'help' => 'Analytics snippet to add to all marketing pages.',
//        ],
//
//        [
//            'key' => 'theme',
//            'label' => 'Default App Theme',
//            'type' => 'select',
//            'options' => [
//                'dark' => 'Dark theme',
//                'light' => 'Light theme',
//            ],
//        ],
//
//        [
//            'key' => 'timeout',
//            'type' => 'Number',
//            'label' => 'Timeout (min.)',
//        ],

    ],

];
