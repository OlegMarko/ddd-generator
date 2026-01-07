---
title: Configuration
nav_order: 7
---

# Configuration

Laravel DDD Generator can be configured via `config/ddd.php`.

```php
return [
    'base_path' => app_path('Modules'),
    'base_namespace' => 'App\\Modules',

    'auto_discovery' => [
        'enabled' => true,
        'cache' => false,
    ],
];
```

## base_path
Defines where modules are stored.

## base_namespace
Root namespace for generated modules.

## Auto discovery
Automatically registers module service providers and routes.
