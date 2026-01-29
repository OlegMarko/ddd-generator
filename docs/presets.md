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
| `domain` + `--style=cqrs` | Domain + CQRS (Commands, Queries, Handlers) |
| `http-crud` | Domain + HTTP CRUD (Controllers, Requests, Routes) |
| `http-api` | Domain + HTTP API (Controllers, Requests, Routes) |
| `http-api` + `--style=cqrs` | Domain + CQRS + HTTP API |

---

## Usage examples

### Domain-only module

```bash
php artisan ddd:make domain Order
```

---

### Domain + CQRS

```bash
php artisan ddd:make domain Order --style=cqrs
```

---

### HTTP CRUD module

```bash
php artisan ddd:make http-crud Order
```

---

### HTTP API module

```bash
php artisan ddd:make http-api Order
```

---

### HTTP API + CQRS

```bash
php artisan ddd:make http-api Order --style=cqrs
```

---

## How to choose a preset

Which preset should I choose? [Presets](which-preset.md)
