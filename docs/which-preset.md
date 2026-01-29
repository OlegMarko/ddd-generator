---
title: Which preset should I choose?
nav_order: 10
---

# Which preset should I choose?

```mermaid
flowchart TD
    A[Start] --> B{Need HTTP?}
    B -- No --> C{Need CQRS?}
    C -- No --> D[domain]
    C -- Yes --> E[domain + --style=cqrs]
    B -- Yes --> F{Need CRUD?}
    F -- Yes --> G[http-crud]
    F -- No --> H{Need CQRS?}
    H -- No --> I[http-api]
    H -- Yes --> J[http-api + --style=cqrs]
```
