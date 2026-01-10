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
    C -- Yes --> E[cqrs]
    B -- Yes --> F{Need CQRS?}
    F -- No --> G[http]
    F -- Yes --> H[http-cqrs]
```
