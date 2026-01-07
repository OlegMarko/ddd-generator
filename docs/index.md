---
title: Home
nav_order: 1
---

# Laravel DDD Generator

Laravel DDD Generator helps you build **modular, scalable Laravel applications**
using **Domain-Driven Design (DDD)** and **CQRS**.

---

## Why this package?

- Explicit architecture
- Modular boundaries
- CQRS-ready
- HTTP API presets
- Idempotent generators

---

## Quick start

```bash
composer require fixik/ddd-generator --dev
php artisan ddd:make api-http Order --entity=Order
```

## Next steps

Continue exploring the documentation:

- 📐 Read about the overall [Architecture](architecture.md)
- 🎛️ Learn how generation [Presets](presets.md) work
- 🔀 Understand the CQRS flow in [CQRS](cqrs.md)
- 🧩 See full [Diagrams](diagrams.md)

These sections explain the design decisions and help you
use the generator effectively in real-world projects.