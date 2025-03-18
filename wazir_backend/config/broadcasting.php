<?php

return [
    'default' => env('BROADCAST_DRIVER', 'pusher'),

    'connections' => [
        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY', 'f63d33c94e33b1f539e8'),
            'secret' => env('PUSHER_APP_SECRET', '955f5a3fe8be6948a047'),
            'app_id' => env('PUSHER_APP_ID', '1943008'),
            'options' => [
                'cluster' => env('PUSHER_APP_CLUSTER', 'ap2'),
                'useTLS' => true,
            ],
        ],
        
        // остальные настройки...
    ],
];