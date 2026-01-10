---
title: Configuration
nav_order: 7
---

# Configuration

Laravel DDD Generator can be configured via `config/ddd.php`.

```php
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
```

## base_path
Defines where modules are stored.

## base_namespace
Root namespace for generated modules.

## Auto discovery
Automatically registers module service providers and routes.
