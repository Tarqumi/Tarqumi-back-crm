# Tarqumi CRM – AI Coding Rules

> These rules MUST be followed at ALL times during development. They are the engineering
> standards for this project. Any AI assistant (Cursor, Copilot, ChatGPT, Claude, etc.)
> must adhere to these rules when generating, modifying, or reviewing code.

---

## 🔴 CRITICAL: Security Rules (Non-Negotiable)

### SQL Injection Prevention
- **NEVER** use raw SQL queries with string concatenation
- **ALWAYS** use Eloquent ORM or Laravel Query Builder with parameter binding
- **NEVER** trust any user input — treat ALL input as potentially malicious
- Use `DB::select('SELECT * FROM users WHERE id = ?', [$id])` if raw queries are absolutely needed
- **NEVER** do `DB::select("SELECT * FROM users WHERE id = $id")` — this is a SQL injection vulnerability

### Input Validation
- **Validate EVERY input on EVERY endpoint** — no exceptions, even button-type inputs
- Use Laravel Form Request classes for all validation
- Validate on **both frontend AND backend** — frontend validation is for UX, backend is for security
- Define validation rules for: type, length, format, range, required/optional
- Sanitize all string inputs — strip HTML tags, trim whitespace
- Use `htmlspecialchars()` or Blade's `{{ }}` (auto-escapes) for output

### XSS Prevention
- **NEVER** use `{!! !!}` in Blade unless the content is explicitly sanitized
- Use `{{ }}` for all dynamic output (auto-escapes HTML entities)
- Sanitize rich text/blog content with a whitelist-based HTML sanitizer (e.g., HTMLPurifier)
- Set `Content-Security-Policy` headers

### CSRF Protection
- Use Laravel's built-in CSRF token middleware on all state-changing routes
- For API routes with Sanctum, ensure token-based auth is properly configured
- Include `@csrf` in all forms

### Authentication & Authorization
- Use **Laravel Sanctum** for API token authentication
- Check permissions on **every protected route** — never rely on frontend-only checks
- Use Laravel Gate/Policy for authorization logic
- Hash passwords with **bcrypt** (Laravel default)
- Implement **rate limiting**: 5 contact form emails per minute (`throttle` middleware)
- Never expose sensitive data in API responses (passwords, tokens, internal IDs when unnecessary)

### Environment Security
- **NEVER** hardcode secrets, API keys, passwords, or credentials in code
- Store ALL secrets in `.env` file
- Add `.env` to `.gitignore` — **verify this on every commit**
- Use `config()` helper to access environment variables, not `env()` directly in code (only in config files)

---

## 🟠 Architecture Rules

### SOLID Principles
1. **S — Single Responsibility Principle**: Each class/module/function does ONE thing
   - Controllers handle HTTP request/response only — delegate business logic to Services
   - Models handle data relationships and attributes only
   - Services contain business logic
   - Repositories handle data access (if used beyond Eloquent)

2. **O — Open/Closed Principle**: Classes should be open for extension, closed for modification
   - Use interfaces and abstract classes for extensible behavior
   - Avoid modifying existing working code when adding features

3. **L — Liskov Substitution**: Subtypes must be substitutable for their base types
   - If using inheritance, ensure child classes don't break parent contracts

4. **I — Interface Segregation**: Don't force classes to implement interfaces they don't use
   - Create focused, specific interfaces rather than large generic ones

5. **D — Dependency Inversion**: Depend on abstractions, not concretions
   - Use Laravel's Service Container and dependency injection
   - Type-hint interfaces in constructors, not concrete classes

### OOP Rules
- Use **classes** for all business entities — no loose functions in global scope
- Use **proper encapsulation** — private/protected properties with getters/setters where needed
- Use **inheritance** only when there's a true IS-A relationship
- Prefer **composition over inheritance** when possible
- Use **traits** for shared behavior across unrelated classes (Laravel style)
- Use **enums** (PHP 8.1+) for fixed sets of values (roles, statuses, etc.)

### Clean Code Rules
- **DRY (Don't Repeat Yourself)**: Extract repeated logic into helper functions, services, or traits
  - If you write the same code 3+ times, it MUST be extracted
  - Create utility classes for common operations
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

---

## 🟡 Laravel Backend Rules

### Project Structure
```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/      # Thin controllers — delegate to services
│   │   ├── Middleware/        # Custom middleware (rate limiting, locale, etc.)
│   │   ├── Requests/         # Form Request validation classes
│   │   └── Resources/        # API Resource transformers
│   ├── Models/               # Eloquent models
│   ├── Services/             # Business logic layer
│   ├── Enums/                # PHP enums (roles, statuses, etc.)
│   ├── Policies/             # Authorization policies
│   ├── Observers/            # Model observers (auto-inactive after 30 days, etc.)
│   └── Traits/               # Shared behavior traits
├── config/                   # Configuration files
├── database/
│   ├── migrations/           # Database migrations
│   ├── seeders/              # Database seeders (including first admin)
│   └── factories/            # Model factories for testing
├── routes/
│   ├── api.php               # API routes
│   └── web.php               # Web routes (if any)
├── storage/                  # File uploads (images, logos)
├── tests/
│   ├── Feature/              # Feature/integration tests
│   └── Unit/                 # Unit tests
└── .env                      # Environment variables (NEVER commit)
```

### Controller Rules
- **Thin controllers**: Controllers should ONLY handle:
  1. Receive request
  2. Validate (via Form Request)
  3. Call service
  4. Return response (via API Resource)
- Max 5 public methods per controller (index, show, store, update, destroy)
- If you need more, create a new controller
- Use **Resource Controllers** where applicable
- Always return consistent JSON responses:
  ```php
  return response()->json([
      'success' => true,
      'data' => $resource,
      'message' => 'Operation successful'
  ], 200);
  ```

### Model Rules
- Define ALL **relationships** explicitly (hasMany, belongsTo, belongsToMany, etc.)
- Use **$fillable** or **$guarded** — prefer $fillable for explicit mass-assignment protection
- Define **$casts** for proper type handling (dates, booleans, enums)
- Use **scopes** for reusable query logic (`scopeActive`, `scopeByPriority`, etc.)
- Use **accessors and mutators** for computed attributes
- Define **$hidden** to prevent sensitive fields from appearing in JSON

### Migration Rules
- **NEVER** modify existing migrations after they've been run — create new migrations for changes
- Add **indexes** on:
  - All foreign keys
  - Columns used in WHERE clauses frequently
  - Columns used in ORDER BY
  - Status columns
  - Email columns
  - Date columns used in ranges
- Use `->unsigned()` for foreign key integer columns
- Always define `->onDelete()` behavior for foreign keys (cascade, set null, restrict)
- Include `created_at` and `updated_at` timestamps on every table
- Use `softDeletes()` on tables where data should be preserved (clients, team members)

### API Rules
- Version all APIs: `/api/v1/...`
- Use **RESTful conventions**:
  - `GET /api/v1/projects` — list
  - `GET /api/v1/projects/{id}` — show
  - `POST /api/v1/projects` — create
  - `PUT /api/v1/projects/{id}` — update
  - `DELETE /api/v1/projects/{id}` — delete
- Use **API Resources** for response transformation (never return raw models)
- Paginate all list endpoints (default 15 per page)
- Support filtering, searching, and sorting via query parameters
- Always return proper HTTP status codes:
  - `200` — Success
  - `201` — Created
  - `204` — No Content (successful delete)
  - `400` — Bad Request
  - `401` — Unauthorized
  - `403` — Forbidden
  - `404` — Not Found
  - `422` — Validation Error
  - `429` — Too Many Requests (rate limit)
  - `500` — Server Error

### Eloquent Rules
- **NEVER** use raw queries unless absolutely necessary
- Use **eager loading** (`with()`) to avoid N+1 query problems
  - ❌ `Project::all()` then `$project->client` in a loop
  - ✅ `Project::with('client', 'manager')->get()`
- Use **chunking** for large dataset operations
- Use **transactions** for multi-step database operations
- Always use **parameterized queries** — never interpolate variables into queries

---

## 🟢 Next.js Frontend Rules

### Project Structure
```
frontend/
├── app/                      # Next.js App Router
│   ├── [locale]/             # i18n locale routing (/ar, /en)
│   │   ├── page.tsx          # Home page
│   │   ├── about/
│   │   ├── services/
│   │   ├── projects/
│   │   ├── blog/
│   │   ├── contact/
│   │   └── login/            # Admin login (hidden route)
│   ├── layout.tsx            # Root layout
│   └── globals.css           # Global styles with CSS variables
├── components/
│   ├── common/               # Shared components (Button, Card, etc.)
│   ├── layout/               # Header, Footer, Navigation
│   ├── landing/              # Landing page specific components
│   ├── admin/                # Admin panel components
│   └── ui/                   # Base UI primitives
├── lib/                      # Utilities, API client, helpers
├── hooks/                    # Custom React hooks
├── services/                 # API service layer
├── types/                    # TypeScript type definitions
├── messages/                 # i18n translation files
│   ├── ar.json
│   └── en.json
├── public/                   # Static assets
└── styles/                   # CSS modules or additional styles
```

### Component Rules
- Use **functional components** with hooks — NO class components
- Keep components under **200 lines** — split if larger
- One component per file
- Use **TypeScript** for all components with proper type definitions
- Props must have TypeScript interfaces defined
- Use `React.memo()` for expensive components that receive the same props
- Use `useMemo` and `useCallback` to prevent unnecessary re-renders
- Use **error boundaries** for UI-level failure isolation

### Styling Rules
- Use **CSS Modules** or a **global CSS file with CSS variables** — NO inline styles
- **NEVER** hardcode colors — use CSS custom properties:
  ```css
  :root {
    --color-primary: #000000;
    --color-secondary: #FFFFFF;
    --color-accent: #333333;
    --color-gray-100: #F5F5F5;
    --color-gray-200: #E5E5E5;
    /* ... all colors as variables */
  }
  ```
- Use **responsive design** from the start — mobile-first approach
- Use CSS Grid and Flexbox for layouts
- Animations: use CSS transitions/animations or Framer Motion — keep them smooth (60fps)
- All interactive elements must have **hover states** and **focus states**

### i18n (Internationalization) Rules
- **ZERO hardcoded strings** in any component — every single text must use the i18n system
- Use `next-intl` or similar for Next.js i18n
- Translation keys must be descriptive:
  - ❌ `t('btn1')`, `t('text')`
  - ✅ `t('contact.form.submitButton')`, `t('home.hero.title')`
- Maintain translation files for both `ar.json` and `en.json`
- When adding ANY new text, immediately add it to BOTH translation files
- Support RTL layout switching for Arabic:
  ```css
  [dir="rtl"] .component { /* RTL-specific styles */ }
  ```

### SEO Rules (CRITICAL)
- Use **Server-Side Rendering (SSR)** for ALL landing page routes
- Every page must have:
  - `<title>` — dynamic, from admin settings
  - `<meta name="description">` — dynamic
  - `<meta name="keywords">` — dynamic
  - Open Graph meta tags (`og:title`, `og:description`, `og:image`, `og:url`)
  - Twitter Card meta tags
  - Canonical URL
  - `<html lang="ar">` or `<html lang="en">` based on locale
  - `<html dir="rtl">` or `<html dir="ltr">` based on locale
- Use **semantic HTML**:
  - One `<h1>` per page
  - Proper heading hierarchy (h1 → h2 → h3, never skip)
  - `<nav>`, `<main>`, `<article>`, `<section>`, `<aside>`, `<footer>`
  - `<img>` must have `alt` attributes (from admin content)
- Blog posts must have:
  - JSON-LD structured data (`Article` schema)
  - Proper heading hierarchy
  - Descriptive URLs (slugified titles)
  - `<time>` elements for publish dates
  - Author information
- Generate `sitemap.xml` dynamically
- Generate `robots.txt`
- Implement proper `<link rel="alternate" hreflang="...">` for bilingual pages

### API Communication Rules
- Create a **centralized API service** — never make fetch calls directly in components
- Handle loading, error, and success states for every API call
- Use proper error handling with user-friendly error messages
- Cache API responses where appropriate (SWR or React Query)
- Always include the Sanctum token in API requests
- Handle 401 responses by redirecting to login

---

## 🔵 Database Rules

### MySQL Specific
- Use **InnoDB** engine for all tables (supports transactions and foreign keys)
- Use **UTF8MB4** character set (supports Arabic, emoji, etc.)
- **Indexing strategy**:
  - Primary keys: auto-increment integers
  - Foreign keys: always indexed
  - Search columns: indexed (email, name, status, etc.)
  - Composite indexes for common query patterns
  - Date range columns: indexed
- **Naming conventions**:
  - Tables: plural, snake_case (`projects`, `team_members`, `contact_submissions`)
  - Columns: snake_case (`project_name`, `start_date`, `is_active`)
  - Foreign keys: `{related_table_singular}_id` (`client_id`, `manager_id`)
  - Pivot tables: alphabetical order (`client_project`, not `project_client`)
- Use **ENUM** or separate tables for fixed value sets (roles, statuses)
- Use **soft deletes** for: clients, team members, projects
- Use **BIGINT UNSIGNED** for IDs
- Set proper **ON DELETE** constraints:
  - Client deleted → projects keep existing (SET NULL on client_id)
  - Team member deleted → reassign project manager first

### Data Integrity
- Define ALL foreign key constraints in migrations
- Use database-level constraints where possible (UNIQUE, NOT NULL, etc.)
- Use Laravel model events/observers for business rules
- Always wrap multi-table operations in **transactions**

---

## 🟣 Testing Rules

- Write tests for **every feature** before moving to the next
- **Unit tests** for: services, models, helpers, validation
- **Feature tests** for: API endpoints, authentication, authorization
- Test both **happy path** and **error cases**
- Test **validation rules** — ensure invalid data is rejected
- Test **authorization** — ensure role-based access is enforced
- Test **edge cases**: empty data, maximum lengths, special characters, Arabic text
- Use **factories and seeders** for test data
- Run full test suite before any commit
- Minimum test structure:
  ```
  tests/
  ├── Feature/
  │   ├── Auth/
  │   │   ├── LoginTest.php
  │   │   └── RegistrationTest.php
  │   ├── Client/
  │   │   └── ClientCrudTest.php
  │   ├── Project/
  │   │   └── ProjectCrudTest.php
  │   └── LandingPage/
  │       ├── SeoTest.php
  │       └── ContentTest.php
  └── Unit/
      ├── Services/
      └── Models/
  ```

---

## 🔘 Git Rules

- **Commit often** — after each working feature
- Use **semantic commit messages**:
  - `feat: add client management CRUD`
  - `fix: resolve SQL injection in project search`
  - `refactor: extract email service from controller`
  - `test: add unit tests for client validation`
  - `style: fix RTL layout for services page`
  - `docs: update API documentation`
  - `chore: update dependencies`
- **Never commit**: `.env`, `node_modules`, `vendor`, storage files, compiled assets
- Use `.gitignore` properly
- Create **feature branches** for each module
- Review code before merging

---

## ⚪ General Workflow Rules

1. **One feature at a time** — complete, test, commit, then move on
2. **Prefer editing existing code** over writing from scratch
3. **Before writing new logic**, check if similar patterns exist in the codebase
4. **Avoid duplication** — if logic exists, import/reuse it
5. **Don't create one-off script files** — use terminal commands
6. **Comment complex logic** with WHY, not WHAT:
   - ❌ `// loop through projects`
   - ✅ `// Filter out inactive projects to prevent them from appearing in CRM views per business rule`
7. **Keep all code environment-aware** — dev, test, and production must all work
8. **No console.log or dd() in production code** — use proper logging
9. **Handle errors gracefully** — never show raw error messages to users
10. **API responses must be consistent** — always same structure, always proper status codes
