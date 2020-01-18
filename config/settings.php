<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Settings Path
    |--------------------------------------------------------------------------
    |
    | This is where your settings are stored on disk within your application's
    | `storage` directory. Note: any subdirectories must already exist.
    |
    | Defaults to 'app/settings.json' if not specified here.
    |
    */

    'path' => 'app/settings.json',

    /*
    |--------------------------------------------------------------------------
    | Navigation Title
    |--------------------------------------------------------------------------
    |
    | This is the text Nova's navigation sidebar will display for this tool.
    |
    | Defaults to 'Settings' if not specified here.
    |
    */

    'navigation' => 'Others',

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    |
    | This is the good stuff :). Each 'panel' will be shown grouped together
    | under its 'title'. Each 'setting' in a panel will display a row in Nova,
    | and you can specify the key used to store its value on disk, its display
    | name in Nova, a description, its type (only boolean or text for now),
    | and a link for more information.
    |
    | Each setting must include at least a key, name, and type.
    |
    */

    'panels' => [

        [

            'name' => 'Others',

            'settings' => [

                [
                    'key' => 'business_characteristics',
                    'name' => 'Business Characteristics',
                    'type' => 'text',
                    'description' => 'Check for Admin Use Only',
                ],

                [
                    'key' => 'twitter_url',
                    'name' => 'Twitter',
                    'type' => 'text',
                    'description' => 'App Twitter page URL.',
                    'link' => [
                        'text' => 'More.',
                        'url' => '/documentation#twitter_url',
                    ],
                ],

            ],

        ],

        [

            'name' => 'Maximum Booking Amount To Allow Online Payment',

            'settings' => [
                
                [
                    'key' => 'amount',
                    'name' => 'Amount(SGD)',
                    'type' => 'text',
                ],
                
                [
                    'key' => 'new_feature',
                    'name' => 'New Feature',
                    'type' => 'toggle',
                    'description' => 'Top secret new app feature.',
                    'link' => [
                        'text' => 'Documentation',
                        'url' => '/documentation#new_feature',
                    ],
                ],

                [
                    'key' => 'enabled_feature',
                    'name' => 'Enabled Feature',
                    'type' => 'toggle',
                    'default' => true,
                    'description' => 'Feature enabled by default.',
                    'link' => [
                        'text' => 'Documentation',
                        'url' => '/documentation#new_feature',
                    ],
                ],

                [
                    'key' => 'welcome_message',
                    'name' => 'Welcome Message',
                    'type' => 'textarea',
                    'description' => 'Message for new users on their first login.',
                    'link' => [
                        'text' => 'Documentation',
                        'url' => '/documentation#new_feature',
                    ],
                ],

                [
                    'key' => 'snippet',
                    'name' => 'Code Snippet',
                    'type' => 'code',
                    'language' => 'javascript',
                    'description' => 'Code to inject into the homepage.',
                    'link' => [
                        'text' => 'Documentation',
                        'url' => '/documentation#new_feature',
                    ],
                ],

            ],

        ],

    ],

];
