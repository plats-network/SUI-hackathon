<?php

return [
    'create' => 'Add New',
    'save_create' => 'Create',
    'save_edit' => 'Save change',
    'cancel_form' => 'Back to list',
    'task' => [
        'page_name' => 'Task management',
        'page_desc' => 'List your tasks',
        'create_page' => 'Create a new task',
        'edit_page' => 'Edit task',
        'form' => [
            'type' => 'Type of task',
            'checkin_type' => 'Type of checkin task',
            'name' => 'Title of task',
            'desc' => 'Description for task',
            'duration' => 'Duration (Minutes)',
            'order' => 'Order',
            'valid_amount' => 'Valid amount',
            'valid_radius' => 'Valid radius',
            'status' => 'Status',
            'image' => 'Cover image',
            'reward_amount' => 'Reward (per user)',
            'total_reward' => 'Total reward',
        ],
        'status' => [
            INACTIVE_TASK => 'Draft',
            ACTIVE_TASK => 'Active',
        ],
        // 'order' => [
        //     OUT_OF_ORDER => 'Out of order',
        //     IN_ORDER => 'In order',
        // ],
        'type' => [
            TYPE_CHECKIN => 'Checkin',
            // TYPE_INSTALL_APP => 'Install Mobile App',
            // TYPE_VIDEO_WATCH => 'Video Watch',
            TYPE_SOCIAL => 'Social',

        ],
        'checkin_type' => [
            ONE_OF_MANY_LOCATIONS => 'One of many locations',
            MULTIPLE_LOCATIONS => 'Multiple locations',

        ],
        'social' => [
            'platform' => 'Platform',
            'action' => 'Action',
            'name' => 'Name',
            'url' => 'Url',
            'type' => [
                FOLLOW      => 'Follow',
                LIKE        => 'Like',
                // SHARE       => 'Share post',
                RETWEET     => 'Retweet',
                // TWEET       => 'Tweet',
                // POST        => 'Post',
                // JOIN_GROUP  => 'Join group',
                HASHTAG     => 'Hashtag',

            ],
            'platform_option' => [
                TWITTER => 'Twitter',
                // FACEBOOK => 'Facebook',
                // DISCORD => 'Discord',
                // TELEGRAM => 'Telegram',

            ]

        ]
    ],
    'reward' => [
        'page_name' => 'Campain',
        'page_desc' => 'Mô tả',
        'create_page' => 'Create',
        'edit_page' => 'Edit',
        'form' => [
            'type' => 'Type',
            'name' => 'Title',
            'desc' => 'Description',
            'duration' => 'Duration (Minutes)',
            'order' => 'Order',
            'valid_amount' => 'Valid amount',
            'valid_radius' => 'Valid radius',
            'status' => 'Status',
            'image' => 'Image',

        ],
        'status' => [
            INACTIVE_TASK => 'Draft',
            ACTIVE_TASK => 'Active',

        ],
        'order' => [
            OUT_OF_ORDER => 'Out of order',
            IN_ORDER => 'In order',

        ],
        'type' => [
            TYPE_CHECKIN => 'Checkin',
            // TYPE_INSTALL_APP => 'Install Mobile App',
            // TYPE_VIDEO_WATCH => 'Video Watch',
            TYPE_SOCIAL => 'Social',

        ],

    ],
    'location' => [
        'form' => [
            'name' => 'Location name',
            'address' => 'Location address',
            'coordinate' => 'Coordinate: Latitude, Longitude',
            'phone_number' => 'Phone number',
            'open_time' => 'Open time',

        ],

    ],
    'task_created' => 'Task created successfully',
    'guild' => [
        'page_name' => 'Guild management',
        'page_desc' => 'List your guilds',
        'create_page' => 'Create a new guild',
        'edit_page' => 'Edit task',

    ],
    'placeholders' => [
        'valid_amount' => 'Ex: number of places to checkin',
        'phone_number' => 'Ex: 0123456789',

    ],
    'company' => [
        'name' => 'Name',
        'form' => [
            'name' => 'Name',
            'logo' => 'Logo',
            'address' => 'Address',
            'phone' => 'Phone number'

        ]

    ]

];
