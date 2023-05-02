<?php

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'daily'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single'],
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
            'days' => 7,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => 'critical',
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => 'debug',
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => 'debug',
        ],

        'MicroserviceConnectorService' => env('LOG_CHANNEL') == 'stderr' ?
            [
                'driver' => 'monolog',
                'level' => env('LOG_LEVEL', 'debug'),
                'handler' => StreamHandler::class,
                'formatter' => LineFormatter::class,
                'formatter_with' => [
                    'dateFormat' => 'Y-m-d H:i:s',
                    'format' => '[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n',
                ],
                'with' => [
                    'stream' => 'php://stderr',
                ],
                'name' => 'MicroserviceConnectorService',
            ]
            :
            [
                'driver' => 'daily',
                'path' => storage_path('logs/MicroserviceConnectorService/jobs.log'),
                'level' => 'debug',
                'days' => '7',
            ],

        'EventDispatcherService' => env('LOG_CHANNEL') == 'stderr' ?
            [
                'driver' => 'monolog',
                'level' => env('LOG_LEVEL', 'debug'),
                'handler' => StreamHandler::class,
                'formatter' => LineFormatter::class,
                'formatter_with' => [
                    'dateFormat' => 'Y-m-d H:i:s',
                    'format' => '[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n',
                ],
                'with' => [
                    'stream' => 'php://stderr',
                ],
                'name' => 'EventDispatcherService',
            ]
            :
            [
                'driver' => 'daily',
                'path' => storage_path('logs/EventDispatcherService/jobs.log'),
                'level' => 'debug',
                'days' => '7',
            ],
        'ArticleStorageService' => env('LOG_CHANNEL') == 'stderr' ?
            [
                'driver' => 'monolog',
                'level' => env('LOG_LEVEL', 'debug'),
                'handler' => StreamHandler::class,
                'formatter' => LineFormatter::class,
                'formatter_with' => [
                    'dateFormat' => 'Y-m-d H:i:s',
                    'format' => '[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n',
                ],
                'with' => [
                    'stream' => 'php://stderr',
                ],
                'name' => 'ArticleStorageService',
            ]
            :
            [
                'driver' => 'daily',
                'path' => storage_path('logs/ArticleStorageService/jobs.log'),
                'level' => 'debug',
                'days' => '7',
            ],

        'stderr' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => StreamHandler::class,
            'formatter' => LineFormatter::class,
            'formatter_with' => [
                'dateFormat' => 'Y-m-d H:i:s',
                'format' => '[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n',
            ],
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'emergency' => env('LOG_CHANNEL') == 'stderr' ?
            [
                'driver' => 'monolog',
                'level' => env('LOG_LEVEL', 'debug'),
                'handler' => StreamHandler::class,
                'formatter' => LineFormatter::class,
                'formatter_with' => [
                    'dateFormat' => 'Y-m-d H:i:s',
                    'format' => '[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n',
                ],
                'with' => [
                    'stream' => 'php://stderr',
                ],
            ] : [
                'path' => storage_path('logs/laravel.log'),
            ],
    ],

];
