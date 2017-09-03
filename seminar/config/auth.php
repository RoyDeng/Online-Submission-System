<?php

return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'token',
            'provider' => 'users',
        ],

        'admin' => [
            'driver' => 'session',
            'provider' => 'maintainer',
        ],

        'author' => [
            'driver' => 'session',
            'provider' => 'author',
        ],

        'chair' => [
            'driver' => 'session',
            'provider' => 'chair',
        ],

        'editor' => [
            'driver' => 'session',
            'provider' => 'editor',
        ],

        'reviewer' => [
            'driver' => 'session',
            'provider' => 'reviewer',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\User::class,
        ],

        'maintainer' => [
			'driver' => 'eloquent',
			'model' => App\Maintainer::class,
        ],
        
        'author' => [
			'driver' => 'eloquent',
			'model' => App\Author::class,
        ],
        
        'chair' => [
			'driver' => 'eloquent',
			'model' => App\Conference\Chair::class,
        ],
        
        'editor' => [
			'driver' => 'eloquent',
			'model' => App\Conference\Editor::class,
        ],
        
        'reviewer' => [
			'driver' => 'eloquent',
			'model' => App\Conference\Reviewer::class,
		],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
        ],
    ],

];
