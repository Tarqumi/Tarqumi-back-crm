---
inclusion: always
priority: 3
---

# Architecture & SOLID Principles

## SOLID Principles

### S — Single Responsibility Principle
Each class/module/function does ONE thing:
- **Controllers**: Handle HTTP request/response only — delegate business logic to Services
- **Models**: Handle data relationships and attributes only
- **Services**: Contain business logic
- **Repositories**: Handle data access (if used beyond Eloquent)

### O — Open/Closed Principle
Classes should be open for extension, closed for modification:
- Use interfaces and abstract classes for extensible behavior
- Avoid modifying existing working code when adding features

### L — Liskov Substitution
Subtypes must be substitutable for their base types:
- If using inheritance, ensure child classes don't break parent contracts

### I — Interface Segregation
Don't force classes to implement interfaces they don't use:
- Create focused, specific interfaces rather than large generic ones

### D — Dependency Inversion
Depend on abstractions, not concretions:
- Use Laravel's Service Container and dependency injection
- Type-hint interfaces in constructors, not concrete classes

## OOP Rules
- Use **classes** for all business entities — no loose functions in global scope
- Use **proper encapsulation** — private/protected properties with getters/setters where needed
- Use **inheritance** only when there's a true IS-A relationship
- Prefer **composition over inheritance** when possible
- Use **traits** for shared behavior across unrelated classes (Laravel style)
- Use **enums** (PHP 8.1+) for fixed sets of values (roles, statuses, etc.)

## Clean Code Rules
- **DRY (Don't Repeat Yourself)**: Extract repeated logic into helper functions, services, or traits
  - If you write the same code 3+ times, it MUST be extracted
- **KISS (Keep It Simple, Stupid)**: Choose the simplest correct solution
- **YAGNI (You Aren't Gonna Need It)**: Don't build features not in the current requirements
- **Meaningful names**: Variables, functions, classes must have descriptive names
  - ❌ `$d`, `$tmp`, `$data`, `$result`
  - ✅ `$projectBudget`, `$activeClients`, `$contactFormSubmission`
- **Small functions**: Each function should do ONE thing and be < 30 lines
- **Small files**: Keep files under **300 lines**. Split if larger.
- **No magic numbers**: Use constants or enums
  - ❌ `if ($priority > 7)`
  - ✅ `if ($priority > self::HIGH_PRIORITY_THRESHOLD)`

## Laravel Backend Structure
```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/      # Thin controllers — delegate to services
│   │   ├── Middleware/        # Custom middleware
│   │   ├── Requests/         # Form Request validation classes
│   │   └── Resources/        # API Resource transformers
│   ├── Models/               # Eloquent models
│   ├── Services/             # Business logic layer
│   ├── Enums/                # PHP enums (roles, statuses)
│   ├── Policies/             # Authorization policies
│   ├── Observers/            # Model observers
│   └── Traits/               # Shared behavior traits
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── routes/
│   ├── api.php
│   └── web.php
├── tests/
│   ├── Feature/
│   └── Unit/
└── .env
```

## Controller Rules
- **Thin controllers**: Max 5 public methods (index, show, store, update, destroy)
- Controllers should ONLY:
  1. Receive request
  2. Validate (via Form Request)
  3. Call service
  4. Return response (via API Resource)
- Always return consistent JSON responses:
  ```php
  return response()->json([
      'success' => true,
      'data' => $resource,
      'message' => 'Operation successful'
  ], 200);
  ```

## Model Rules
- Define ALL **relationships** explicitly
- Use **$fillable** for explicit mass-assignment protection
- Define **$casts** for proper type handling
- Use **scopes** for reusable query logic
- Use **accessors and mutators** for computed attributes
- Define **$hidden** to prevent sensitive fields from appearing in JSON

## Eloquent Rules
- **NEVER** use raw queries unless absolutely necessary
- Use **eager loading** (`with()`) to avoid N+1 query problems
  - ❌ `Project::all()` then `$project->client` in a loop
  - ✅ `Project::with('client', 'manager')->get()`
- Use **chunking** for large dataset operations
- Use **transactions** for multi-step database operations
- Always use **parameterized queries**
