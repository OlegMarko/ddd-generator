---
title: Presets
nav_order: 4
---

# Presets

Presets define **what architectural layers are generated** for a module.
They help you start with the right structure depending on your needs.

---

## Available presets

| Preset | Description |
|------|-------------|
| `domain` | Domain-only module (Entities, Events, Repositories) |
| `http` | Domain + HTTP layer (Controllers, Requests, Routes) |
| `cqrs` | Domain + CQRS (Commands, Queries, Handlers) |
| `http-cqrs` | Domain + CQRS + HTTP |

---

## Usage examples

### Domain-only module

```bash
php artisan ddd:make domain Order
```

---

### HTTP module

```bash
php artisan ddd:make http Order
```

---

### CQRS module

```bash
php artisan ddd:make cqrs Order
```

---

### HTTP + CQRS module

```bash
php artisan ddd:make http-cqrs Order
```

---

## How to choose a preset

Which preset should I choose? [Presets](which-preset.md)
