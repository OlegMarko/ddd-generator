---
title: HTTP Layer
nav_order: 6
---

# HTTP Layer

HTTP concerns live in the **Infrastructure** layer.

Generated components:
- Controllers
- Form Requests
- API Resources
- Module routes

## Flow

```text
HTTP Request
  -> Controller
    -> Command / Query
      -> Handler
        -> Repository
```

Routes are loaded via the module ServiceProvider.

### See full diagrams in [Diagrams](diagrams.md)