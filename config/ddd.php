<?php

return [
    'base_path' => app_path('Modules'), // Base path for generated modules
    'base_namespace' => 'App\\Modules', // Base namespace for generated modules

    'cqrs' => [
        'enabled' => false,
    ],

    'auto_discovery' => [
        'enabled' => true,
        'cache' => true,
        'cache_path' => base_path('bootstrap/cache/ddd-modules.php'),

//        'register_migrations' => true,
//        'register_routes' => false,
    ],

    'event_listeners' => [
        'cache' => true,
        'cache_path' => base_path('bootstrap/cache/ddd-event-listeners.php'),
    ],

    'class_map' => [
        'cache' => true,
        'cache_path' => base_path('bootstrap/cache/ddd-class-map.php'),
    ],
];
