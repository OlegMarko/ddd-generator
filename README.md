# Laravel DDD Generator

[![CI](https://github.com/OlegMarko/ddd-generator/actions/workflows/ci.yml/badge.svg)](https://github.com/OlegMarko/ddd-generator/actions/workflows/ci.yml)
[![Coverage](https://codecov.io/gh/OlegMarko/ddd-generator/branch/main/graph/badge.svg)](https://codecov.io/gh/OlegMarko/ddd-generator)
[![Latest Version](https://img.shields.io/packagist/v/fixik/ddd-generator)](https://packagist.org/packages/fixik/ddd-generator)
[![Total Downloads](https://poser.pugx.org/fixik/ddd-generator/downloads)](https://packagist.org/packages/fixik/ddd-generator)
[![License](https://poser.pugx.org/fixik/ddd-generator/license)](https://packagist.org/packages/fixik/ddd-generator)

A Laravel package for generating **Domain-Driven Design (DDD)** modules with
**CQRS**, **modular architecture**, and **HTTP API presets**.

> Opinionated, test-driven, production-ready scaffolding for large Laravel apps.

---

## ✨ Features

- Modular DDD structure (`App/Modules/*`)
- Clean separation:
   - Domain / Application / Infrastructure
- CQRS (Commands / Queries / Handlers)
- HTTP API generation (Controllers, Requests, Resources, Routes)
- Idempotent generators (safe to re-run)
- Preset-based generation (`core`, `api`, `api-http`)
- Custom base namespace & path
- Fully covered by tests

---

## 🚀 Quick Start

### Install

```bash
composer require fixik/ddd-generator --dev
```

### Generate API HTTP module

```bash
php artisan ddd:make api-http Order --entity=Order
```

This will generate:
- Domain entity & repository
- CQRS commands / queries & handlers
- HTTP controller, request, resource
- Module routes
- Module ServiceProvider

## 📦 Presets
| Preset     | Description                                     |
| ---------- | ----------------------------------------------- |
| `core`     | Domain only (Entity, ServiceProvider)           |
| `api`      | core + CQRS + Repository                        |
| `api-http` | api + HTTP layer (Controller, Routes, Requests) |

## 📚 Documentation

### 👉 Full documentation:
https://olegmarko.github.io/ddd-generator/

Includes:
- Architecture overview
- Presets explained
- CQRS flow
- Configuration
- Extending generators

## 🧠 Philosophy

This package enforces explicit architecture:
- No hidden magic
- No guessing entities
- No global routes
- No infrastructure leaks into domain
- Designed for long-living Laravel applications.

## 📄 License

### MIT