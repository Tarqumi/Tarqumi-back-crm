---
inclusion: always
priority: 2
---

# Security Rules (NON-NEGOTIABLE)

## SQL Injection Prevention
- **NEVER** use raw SQL queries with string concatenation
- **ALWAYS** use Eloquent ORM or Laravel Query Builder with parameter binding
- **NEVER** trust any user input — treat ALL input as potentially malicious
- Use `DB::select('SELECT * FROM users WHERE id = ?', [$id])` if raw queries are absolutely needed
- **NEVER** do `DB::select("SELECT * FROM users WHERE id = $id")` — this is a SQL injection vulnerability

## Input Validation
- **Validate EVERY input on EVERY endpoint** — no exceptions, even button-type inputs
- Use Laravel Form Request classes for all validation
- Validate on **both frontend AND backend** — frontend validation is for UX, backend is for security
- Define validation rules for: type, length, format, range, required/optional
- Sanitize all string inputs — strip HTML tags, trim whitespace
- Use `htmlspecialchars()` or Blade's `{{ }}` (auto-escapes) for output

## XSS Prevention
- **NEVER** use `{!! !!}` in Blade unless the content is explicitly sanitized
- Use `{{ }}` for all dynamic output (auto-escapes HTML entities)
- Sanitize rich text/blog content with a whitelist-based HTML sanitizer (e.g., HTMLPurifier)
- Set `Content-Security-Policy` headers

## CSRF Protection
- Use Laravel's built-in CSRF token middleware on all state-changing routes
- For API routes with Sanctum, ensure token-based auth is properly configured
- Include `@csrf` in all forms

## Authentication & Authorization
- Use **Laravel Sanctum** for API token authentication
- Check permissions on **every protected route** — never rely on frontend-only checks
- Use Laravel Gate/Policy for authorization logic
- Hash passwords with **bcrypt** (Laravel default)
- Implement **rate limiting**: 5 contact form emails per minute (`throttle` middleware)
- Never expose sensitive data in API responses (passwords, tokens, internal IDs when unnecessary)

## Environment Security
- **NEVER** hardcode secrets, API keys, passwords, or credentials in code
- Store ALL secrets in `.env` file
- Add `.env` to `.gitignore` — **verify this on every commit**
- Use `config()` helper to access environment variables, not `env()` directly in code (only in config files)

## Test Cases (Must Pass)
- All database queries use Eloquent/Query Builder
- No string concatenation in queries
- Search endpoints resist SQL injection (`' OR 1=1 --`)
- Login resists SQL injection (`admin' OR '1'='1`)
- All fields resist XSS (`<script>alert('XSS')</script>`)
- All POST/PUT/DELETE routes protected with CSRF
- Passwords hashed with bcrypt
- Login rate limiting active
- Role-based access enforced
- File upload validation (reject .exe, .php, .html)
- File size limit enforced (20MB max)
- No secrets in code or git
- Debug mode off in production
