# DDD Generator for Laravel

[![CI](https://github.com/OlegMarko/ddd-generator/actions/workflows/ci.yml/badge.svg)](https://github.com/OlegMarko/ddd-generator/actions/workflows/ci.yml)
[![Coverage](https://codecov.io/gh/OlegMarko/ddd-generator/branch/main/graph/badge.svg)](https://codecov.io/gh/OlegMarko/ddd-generator)
[![Latest Version](https://img.shields.io/packagist/v/fixik/ddd-generator)](https://packagist.org/packages/fixik/ddd-generator)
[![Packagist Downloads](https://img.shields.io/packagist/dm/fixik/ddd-generator)](https://packagist.org/packages/fixik/ddd-generator)
[![License](https://img.shields.io/github/license/OlegMarko/ddd-generator)](https://packagist.org/packages/fixik/ddd-generator)


A production-ready **Laravel package** for generating **Classic DDD structure** with **optional CQRS**, **auto-binding**, and **auto-discovery of modules**.

This package is designed for **modular monoliths** and long-living Laravel applications.

---

## ✨ Features

* ✅ Classic DDD structure (Domain / Application / Infrastructure)
* 🔁 Optional CQRS (Commands / Queries / Handlers)
* 🚀 Mega-command for fast scaffolding
* 🔌 Automatic repository binding (interface → implementation)
* 🔍 Auto-discovery of modules
* ⚡ Cached module discovery (production-ready)
* 🧱 Zero Laravel dependency in Domain layer

---

## 📌 Requirements

* PHP 8.1+
* Laravel 10, 11, or 12

---

## 📦 Installation

### Step 1: Install the package

```bash
composer require fixik/ddd-generator
```

Or for local development:

```json
"repositories": [
  {
    "type": "path",
    "url": "packages/ddd-generator"
  }
]
```

Then:
```bash
composer require fixik/ddd-generator:@dev
```

### Step 2: Publish configuration

```bash
php artisan vendor:publish --tag=config
```

This creates `config/ddd.php` with default settings.

---

## 🚀 Quick Start

### Generate Your First Module

The easiest way to get started is using the mega-command:

```bash
php artisan ddd:make Order \
  --entity=Order \
  --value-object=OrderId \
  --event=OrderCreated \
  --listener=SendOrderConfirmation \
  --dto=CreateOrderData \
  --repository \
  --usecase=CreateOrder \
  --model \
  --mapper \
  --cqrs
```

This single command creates a complete DDD module with:
- ✅ Domain Entity (`Order`)
- ✅ Value Object (`OrderId`)
- ✅ Domain Event (`OrderCreated`)
- ✅ Event Listener (`SendOrderConfirmationListener`)
- ✅ DTO (`CreateOrderData`)
- ✅ Repository interface + Eloquent implementation
- ✅ Eloquent Model
- ✅ Entity ↔ Model Mapper
- ✅ UseCase (`CreateOrder`)
- ✅ CQRS Command & Query with Handlers
- ✅ Module ServiceProvider (auto-registered)

### Minimal Example

For a simple module without CQRS:

```bash
php artisan ddd:make Product \
  --entity=Product \
  --repository \
  --usecase=CreateProduct
```

This generates:
- Entity
- Repository interface + implementation
- UseCase
- Module ServiceProvider

---

## 🗂 Generated Directory Structure

After running the generator, your module will have this structure:

```text
app/Modules/
└── Order/
    ├── Application/
    │   ├── CommandHandlers/     # CQRS command handlers
    │   ├── Commands/            # CQRS commands
    │   ├── DTO/                 # Data Transfer Objects
    │   ├── Listeners/           # Event listeners
    │   ├── Queries/             # CQRS queries
    │   ├── QueryHandlers/       # CQRS query handlers
    │   └── UseCases/            # Application use cases
    ├── Domain/
    │   ├── Entities/            # Domain entities (pure PHP)
    │   ├── Events/              # Domain events
    │   ├── Repositories/        # Repository interfaces
    │   └── ValueObjects/        # Value objects
    ├── Infrastructure/
    │   └── Persistence/
    │       ├── Mappers/         # Entity ↔ Model mappers
    │       ├── Models/          # Eloquent models
    │       └── Repositories/    # Repository implementations
    └── OrderServiceProvider.php # Auto-discovered service provider
```

---

## 📖 Command Reference

### Mega-Command: `ddd:make`

Generate a complete DDD module with all components:

```bash
php artisan ddd:make {module} [options]
```

**Options:**
- `--entity=Name` - Create a domain entity
- `--value-object=Name` - Create value objects (can be used multiple times)
- `--event=Name` - Create domain events (can be used multiple times)
- `--listener=Name` - Create event listeners (can be used multiple times)
- `--queued-listener` - Make all listeners queueable
- `--dto=Name` - Create DTOs (can be used multiple times)
- `--usecase=Name` - Create a use case
- `--repository` - Generate repository interface + implementation
- `--model` - Generate Eloquent model
- `--mapper` - Generate Entity ↔ Model mapper
- `--cqrs` - Enable CQRS (creates Commands, Queries, and Handlers)

**Examples:**

```bash
# Full-featured module
php artisan ddd:make Order \
  --entity=Order \
  --value-object=OrderId \
  --value-object=Money \
  --event=OrderCreated \
  --event=OrderCancelled \
  --listener=SendEmail \
  --listener=UpdateInventory \
  --queued-listener \
  --dto=CreateOrderData \
  --repository \
  --usecase=CreateOrder \
  --model \
  --mapper \
  --cqrs

# Simple module
php artisan ddd:make Product \
  --entity=Product \
  --repository \
  --usecase=CreateProduct
```

### Individual Commands

Generate specific components:

#### Domain Layer

```bash
# Create a domain entity
php artisan ddd:make-entity {module} {name}

# Create a value object
php artisan ddd:make-value-object {module} {name} [--type=string]

# Create a domain event
php artisan ddd:make-event {module} {name}

# Create repository interface + implementation
php artisan ddd:make-repository {module} {entity}
```

**Examples:**
```bash
php artisan ddd:make-entity Order Order
php artisan ddd:make-value-object Order OrderId --type=string
php artisan ddd:make-event Order OrderCreated
php artisan ddd:make-repository Order Order
```

#### Application Layer

```bash
# Create a use case
php artisan ddd:make-usecase {module} {name} [--entity=EntityName]

# Create a DTO
php artisan ddd:make-dto {module} {name}

# Create an event listener
php artisan ddd:make-listener {module} {event} {listener} [--queued]

# Create CQRS command + handler
php artisan ddd:make-command {module} {name}

# Create CQRS query + handler
php artisan ddd:make-query {module} {name}
```

**Examples:**
```bash
php artisan ddd:make-usecase Order CreateOrder --entity=Order
php artisan ddd:make-dto Order CreateOrderData
php artisan ddd:make-listener Order OrderCreated SendEmail --queued
php artisan ddd:make-command Order CreateOrder
php artisan ddd:make-query Order GetOrder
```

#### Infrastructure Layer

```bash
# Create an Eloquent model
php artisan ddd:make-model {module} {name}

# Create Entity ↔ Model mapper
php artisan ddd:make-mapper {module} {entity}
```

**Examples:**
```bash
php artisan ddd:make-model Order Order
php artisan ddd:make-mapper Order Order
```

### Module Management

```bash
# Create module structure (folders only)
php artisan ddd:make-module {name} [--cqrs]

# Rebuild module discovery cache
php artisan ddd:modules-cache

# Rebuild event listener cache
php artisan ddd:event-listeners-cache
```

---

## 🔌 Auto-Discovery

### Module Auto-Discovery

Modules are **automatically discovered and registered**. No manual registration needed!

When you create a module with a ServiceProvider (automatically created when generating repositories), it will be auto-discovered and registered.

**No need to do this:**
```php
// ❌ Not needed - auto-discovery handles this
$this->app->register(OrderServiceProvider::class);
```

### Event Listener Auto-Registration

Event listeners are automatically registered when they follow the naming convention:
- Listener class: `{EventName}Listener`
- Event class: `{EventName}`

Example:
- Event: `App\Modules\Order\Domain\Events\OrderCreated`
- Listener: `App\Modules\Order\Application\Listeners\OrderCreatedListener`

The listener will be automatically registered to listen to the event.

---

## ⚡ Caching (Production)

For production environments, cache module and listener discovery:

### Cache Commands

```bash
# Cache module discovery
php artisan ddd:modules-cache

# Cache event listener discovery
php artisan ddd:event-listeners-cache
```

### Cache Files

- `bootstrap/cache/ddd-modules.php` - Cached module service providers
- `bootstrap/cache/ddd-event-listeners.php` - Cached event listener mappings
- `bootstrap/cache/ddd-class-map.php` - Cached class map

### Deployment

Add to your deployment script:

```bash
php artisan ddd:modules-cache
php artisan ddd:event-listeners-cache
php artisan config:cache
php artisan route:cache
```

---

## ⚙️ Configuration

After publishing the config file (`config/ddd.php`), you can customize:

```php
return [
    // Base path for modules
    'base_path' => app_path('Modules'),

    // CQRS settings
    'cqrs' => [
        'enabled' => false,
    ],

    // Auto-discovery settings
    'auto_discovery' => [
        'enabled' => true,        // Enable/disable auto-discovery
        'cache' => true,          // Use cache in production
        'cache_path' => base_path('bootstrap/cache/ddd-modules.php'),
    ],

    // Event listener settings
    'event_listeners' => [
        'cache' => true,
        'cache_path' => base_path('bootstrap/cache/ddd-event-listeners.php'),
    ],

    // Class map settings
    'class_map' => [
        'cache' => true,
        'cache_path' => base_path('bootstrap/cache/ddd-class-map.php'),
    ],
];
```

---

## 🎯 Common Workflows

### Workflow 1: Create a Simple CRUD Module

```bash
php artisan ddd:make Product \
  --entity=Product \
  --repository \
  --usecase=CreateProduct \
  --usecase=UpdateProduct \
  --usecase=DeleteProduct \
  --model \
  --mapper
```

### Workflow 2: Create Module with Events

```bash
php artisan ddd:make Order \
  --entity=Order \
  --event=OrderCreated \
  --event=OrderPaid \
  --event=OrderCancelled \
  --listener=SendConfirmationEmail \
  --listener=UpdateInventory \
  --queued-listener \
  --repository \
  --usecase=CreateOrder
```

### Workflow 3: Create Module with CQRS

```bash
php artisan ddd:make Order \
  --entity=Order \
  --repository \
  --usecase=CreateOrder \
  --cqrs \
  --model \
  --mapper
```

This creates:
- Entity
- Repository
- UseCase
- Command (`CreateOrderCommand` + `CreateOrderHandler`)
- Query (`GetOrderQuery` + `GetOrderHandler`)

### Workflow 4: Incremental Development

Start with a basic module:

```bash
php artisan ddd:make Product --entity=Product --repository
```

Then add components as needed:

```bash
php artisan ddd:make-value-object Product ProductId
php artisan ddd:make-event Product ProductCreated
php artisan ddd:make-listener Product ProductCreated SendNotification
php artisan ddd:make-usecase Product CreateProduct --entity=Product
```

---

## 🧠 Architectural Rules

This package enforces clean DDD architecture:

1. **Domain Layer** - Pure PHP, **zero Laravel dependencies**
   - Entities, Value Objects, Events, Repository Interfaces

2. **Application Layer** - Orchestrates use cases
   - UseCases, DTOs, Event Listeners, CQRS Commands/Queries

3. **Infrastructure Layer** - Framework-specific implementations
   - Eloquent Models, Repository Implementations, Mappers

4. **Dependency Direction**
   - Domain ← Application ← Infrastructure
   - Domain never depends on Application or Infrastructure

5. **Repository Pattern**
   - Interface in Domain, Implementation in Infrastructure
   - Automatically bound via ServiceProvider

---

## 🔍 How It Works

### Repository Auto-Binding

When you generate a repository:

```bash
php artisan ddd:make-repository Order Order
```

This creates:
1. `Domain/Repositories/OrderRepository` (interface)
2. `Infrastructure/Persistence/Repositories/EloquentOrderRepository` (implementation)
3. Updates `OrderServiceProvider` to bind them:

```php
$this->app->bind(
    OrderRepository::class,
    EloquentOrderRepository::class
);
```

### Event Listener Discovery

Listeners are discovered by:
1. Scanning `Application/Listeners` folder
2. Matching listener name to event name
3. Auto-registering via `Event::listen()`

Example:
- `OrderCreatedListener` → listens to `OrderCreated` event

---

## 🛠 Troubleshooting

### Module Not Auto-Discovered

1. Check that `auto_discovery.enabled` is `true` in config
2. Ensure ServiceProvider exists: `{Module}ServiceProvider.php`
3. Clear cache: `php artisan ddd:modules-cache`

### Event Listener Isn’t Registered

1. Check naming convention: `{EventName}Listener`
2. Ensure both event and listener exist
3. Clear cache: `php artisan ddd:event-listeners-cache`

### Cache Issues

Clear all caches:
```bash
php artisan ddd:modules-cache
php artisan ddd:event-listeners-cache
php artisan config:clear
```

---

## 🛣 Roadmap

* Bus integration (Command → Handler)
* Hexagonal architecture preset
* Module enable/disable
* Event sourcing support

---

## 📄 License

MIT
