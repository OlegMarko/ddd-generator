---
title: Diagrams
nav_order: 9
---

# Architecture & Flow Diagrams

---

## Use Case flow diagram

```mermaid
sequenceDiagram
    participant Client
    participant HTTP as Controller
    participant UC as CreateOrderUseCase
    participant DOM as Domain Entity
    participant R as OrderRepository
    participant EVT as Domain Event
    participant DIS as Event Dispatcher

    Client->>HTTP: POST /orders
    HTTP->>UC: execute(input)
    UC->>DOM: create/change state
    UC->>R: save(entity)
    DOM-->>EVT: OrderCreated
    UC->>DIS: dispatch(event)
    HTTP-->>Client: 201 Created

```

## CQRS — Write flow (Command)

```mermaid
sequenceDiagram
    participant Client
    participant HTTP as Controller
    participant CMD as CreateOrderCommand
    participant H as CommandHandler
    participant R as OrderRepository
    participant DB as Database

    Client->>HTTP: POST /orders
    HTTP->>CMD: create
    HTTP->>H: dispatch(command)
    H->>R: save(entity)
    R->>DB: persist
    DB-->>R: ok
    R-->>H: entity
    H-->>HTTP: result
    HTTP-->>Client: 201 Created
```

---

## CQRS — Read flow (Query)

```mermaid
sequenceDiagram
    participant Client
    participant HTTP as Controller
    participant QRY as GetOrderQuery
    participant H as QueryHandler
    participant R as OrderRepository
    participant DB as Database

    Client->>HTTP: GET /orders/{id}
    HTTP->>QRY: create
    HTTP->>H: handle(query)
    H->>R: findById(id)
    R->>DB: select
    DB-->>R: data
    R-->>H: Order
    H-->>HTTP: OrderDTO
    HTTP-->>Client: 200 OK
```

---

## Domain Events — Flow

```mermaid
sequenceDiagram
    participant CMD as CommandHandler
    participant DOM as Domain Entity
    participant EVT as Domain Event
    participant DIS as Event Dispatcher
    participant LST as Listener
    participant INF as Infrastructure

    CMD->>DOM: change state
    DOM-->>EVT: OrderCreated
    CMD->>DIS: dispatch(event)
    DIS->>LST: handle(event)
    LST->>INF: side effect
```

---

## CQRS — Dependency boundaries

```mermaid
graph TD
    HTTP[HTTP Controller]:::http
    CMD[Command]:::command
    QRY[Query]:::query
    DOM[Domain]:::domain
    R[Repository]

    HTTP --> CMD
    HTTP --> QRY
    CMD --> DOM
    QRY --> DOM
    DOM --> R
```

---

## HTTP — Request lifecycle

```mermaid
sequenceDiagram
    participant Client
    participant Router
    participant HTTP as Controller
    participant Req as FormRequest
    participant H as Handler
    participant Res as Resource

    Client->>Router: HTTP request
    Router->>HTTP: match route
    HTTP->>Req: validate
    HTTP->>H: dispatch
    H-->>HTTP: result
    HTTP->>Res: transform
    Res-->>Client: JSON response
```

---

## Layered architecture overview

```mermaid
graph TD
    HTTP[HTTP Layer]:::http
    APP[Application Layer]
    DOM[Domain Layer]:::domain
    INF[Infrastructure]

    HTTP --> APP
    APP --> DOM
    DOM --> APP
    APP --> INF
```
