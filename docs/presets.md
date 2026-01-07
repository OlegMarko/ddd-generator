---
title: Presets
nav_order: 4
---

# Presets

Presets define **what layers and components are generated**
when creating a module.

They provide opinionated defaults while keeping everything explicit.

---

## Available presets

| Preset     | Description |
|-----------|------------|
| `core`     | Domain-only module |
| `api`      | core + CQRS + Repository |
| `api-http` | api + HTTP layer |

---

## core preset

### Description
Minimal domain module for modeling business logic.

### Generates
- Domain Entity
- Module ServiceProvider

### CLI example

```bash
php artisan ddd:make core Order --entity=Order
```

### Result

```
Modules/Order
├── Domain
│   └── Entities/Order.php
└── OrderServiceProvider.php
```

---

## api preset

### Description
Adds Application layer and CQRS.

### Generates
- core preset
- Commands & Queries
- CommandHandlers & QueryHandlers
- Repository interface and implementation

### CLI example

```bash
php artisan ddd:make api Order --entity=Order
```

### Result

```
Modules/Order
├── Domain
│   ├── Entities
│   └── Repositories
├── Application
│   ├── Commands
│   ├── CommandHandlers
│   ├── Queries
│   └── QueryHandlers
└── Infrastructure
    └── Persistence
```

---

## api-http preset

### Description
Generates a full REST-ready HTTP API.

### Generates
- api preset
- HTTP Controllers
- Form Requests
- API Resources
- Module routes

### CLI example

```bash
php artisan ddd:make api-http Order --entity=Order
```

### Result

```
Modules/Order
├── Domain
├── Application
├── Infrastructure
│   ├── Persistence
│   └── Http
│       ├── Controllers
│       ├── Requests
│       ├── Resources
│       └── routes.php
└── OrderServiceProvider.php
```

---

## Preset guarantees

- Presets are idempotent
- Safe to re-run multiple times
- Never overwrite existing code
- Entity must be explicitly provided

---

## Custom presets

You can create your own presets by implementing:

```php
Fixik\DddGenerator\Presets\PresetInterface
```

This allows:
- Custom module templates
- Team-specific architecture
- Company standards enforcement

---

## When to use which preset

| Use case | Preset |
|--------|--------|
| Domain modeling | `core` |
| Internal services | `api` |
| Public REST API | `api-http` |
