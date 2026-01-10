---
title: Installation
nav_order: 2
---

# Installation

Laravel DDD Generator is designed to be used as a **development-only tool**
for scaffolding modules and architecture.

---

## Requirements

- PHP >= 8.1
- Laravel >= 10.x
- Composer

---

## Install via Composer

```bash
composer require fixik/ddd-generator --dev
```

## Publish configuration (optional)

```bash
php artisan vendor:publish --tag=ddd-config
```

This will create:

```bash
config/ddd.php
```

## Default configuration

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

## Verify installation

Shows a list of available commands:

```bash
php artisan list | grep ddd
```

## First module

```bash
php artisan ddd:make http-cqrs Order --entity=Order
```

## Next steps

Continue exploring the documentation:

- 📐 Read about the overall [Architecture](architecture.md)
- 🎛️ Learn how generation [Presets](presets.md) work
- 🔀 Understand the CQRS flow in [CQRS](cqrs.md)

These sections explain the design decisions and help you
use the generator effectively in real-world projects.
