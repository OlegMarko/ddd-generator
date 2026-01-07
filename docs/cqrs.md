---
title: CQRS
nav_order: 5
---

# CQRS

CQRS separates **writes** (Commands) from **reads** (Queries).

## Command
- Mutates state
- Contains only data

## CommandHandler
- Contains business logic
- Depends on repositories

## Query
- Read-only
- No side effects

## QueryHandler
- Fetches data
- Returns DTOs or primitives

### See full diagrams in [Diagrams](diagrams.md)
