# Tarqumi CRM – Quality Benchmarks & Test Checklist

> Use this document to systematically test and validate every aspect of the project.
> Each section contains specific test cases. Mark them as ✅ PASS or ❌ FAIL during review.
> Testing should be done BEFORE any merge to main branch.

---

## 📊 Scoring Summary

| Category | Weight | Score |
|----------|--------|-------|
| Security | 25% | __ /100 |
| SEO | 20% | __ /100 |
| Code Quality (SOLID/OOP/DRY) | 15% | __ /100 |
| Functionality & Business Logic | 15% | __ /100 |
| Performance | 10% | __ /100 |
| i18n & RTL | 10% | __ /100 |
| Accessibility & UX | 5% | __ /100 |
| **TOTAL WEIGHTED** | **100%** | **__ /100** |

**Minimum passing score: 80/100. Security must score ≥ 90/100.**

---

## 🔴 1. Security Tests (25%)

### 1.1 SQL Injection
| # | Test Case | How to Test | Status |
|---|-----------|-------------|--------|
| 1.1.1 | All database queries use Eloquent/Query Builder | `grep -r "DB::raw\|DB::select\|DB::statement" --include="*.php"` — verify parameterized | ⬜ |
| 1.1.2 | No string concatenation in queries | Search for `"SELECT.*\$` or `"WHERE.*\$` patterns in codebase | ⬜ |
| 1.1.3 | Search endpoints resist SQL injection | Input `' OR 1=1 --` in all search fields | ⬜ |
| 1.1.4 | Login resists SQL injection | Input `admin' OR '1'='1` as email, `' OR '1'='1` as password | ⬜ |
| 1.1.5 | Project name field resists injection | Input `'; DROP TABLE projects; --` as project name | ⬜ |
| 1.1.6 | Client fields resist injection | Input `Robert'); DROP TABLE clients;--` in all client fields | ⬜ |
| 1.1.7 | Contact form resists injection | Input SQL payloads in Name, Email, Phone, Message fields | ⬜ |
| 1.1.8 | URL parameters resist injection | Try `/api/v1/projects?sort=name;DROP TABLE projects` | ⬜ |
| 1.1.9 | Numeric inputs validated | Input `abc` or `1 OR 1=1` in priority, budget fields | ⬜ |
| 1.1.10 | Blog content resists injection | Input SQL in blog title, description, content | ⬜ |

### 1.2 XSS (Cross-Site Scripting)
| # | Test Case | How to Test | Status |
|---|-----------|-------------|--------|
| 1.2.1 | Stored XSS in project names | Input `<script>alert('XSS')</script>` as project name | ⬜ |
| 1.2.2 | Stored XSS in client names | Input `<img src=x onerror=alert('XSS')>` as client name | ⬜ |
| 1.2.3 | Stored XSS in blog content | Input `<svg onload=alert(1)>` in blog editor | ⬜ |
| 1.2.4 | Reflected XSS in search | Input `<script>alert(1)</script>` in search queries | ⬜ |
| 1.2.5 | XSS in contact form | Input script tags in all contact form fields | ⬜ |
| 1.2.6 | All Blade output uses `{{ }}` | `grep -r "{!!" --include="*.blade.php"` — verify each is intentional | ⬜ |
| 1.2.7 | React output sanitized | Verify no `dangerouslySetInnerHTML` without sanitization | ⬜ |
| 1.2.8 | Content-Security-Policy header set | Check response headers for CSP | ⬜ |

### 1.3 CSRF Protection
| # | Test Case | How to Test | Status |
|---|-----------|-------------|--------|
| 1.3.1 | All POST/PUT/DELETE routes protected | Try requests without CSRF token — must fail with 419 | ⬜ |
| 1.3.2 | API routes use Sanctum auth | Try API calls without Bearer token — must fail with 401 | ⬜ |
| 1.3.3 | Token refresh works properly | Use expired token — verify proper refresh flow | ⬜ |

### 1.4 Authentication & Authorization
| # | Test Case | How to Test | Status |
|---|-----------|-------------|--------|
| 1.4.1 | Passwords hashed with bcrypt | Check database — password field must be hashed | ⬜ |
| 1.4.2 | Login rate limiting | Try 10 failed logins rapidly — must be throttled | ⬜ |
| 1.4.3 | Admin-only routes blocked for employees | Login as employee, try accessing admin endpoints | ⬜ |
| 1.4.4 | CTO can edit landing page | Login as CTO founder, verify landing page edit access | ⬜ |
| 1.4.5 | CEO cannot edit landing page | Login as CEO founder, verify NO landing page edit access | ⬜ |
| 1.4.6 | Employee cannot edit profiles | Login as employee, try to edit own or others' profiles | ⬜ |
| 1.4.7 | Only super admin can delete admins | Login as regular admin, try deleting another admin | ⬜ |
| 1.4.8 | Protected routes redirect to login | Access admin URLs while logged out — must redirect | ⬜ |
| 1.4.9 | Session/token expiry works | Wait for token expiry — verify logout behavior | ⬜ |
| 1.4.10 | Password reset works securely | Test password reset flow — token must be single-use and time-limited | ⬜ |

### 1.5 Input Validation
| # | Test Case | How to Test | Status |
|---|-----------|-------------|--------|
| 1.5.1 | Email format validated | Input `notanemail`, `@test.com`, `test@`, `test@.com` | ⬜ |
| 1.5.2 | Phone format validated | Input `abc`, `12`, `+999999999999999999` | ⬜ |
| 1.5.3 | Required fields enforced | Submit forms with empty required fields | ⬜ |
| 1.5.4 | Max length enforced | Input 10,000 character strings in name fields | ⬜ |
| 1.5.5 | Priority range enforced | Input `-1`, `0`, `11`, `999`, `abc` as priority | ⬜ |
| 1.5.6 | Budget non-negative | Input `-100`, `-1`, `abc` as budget | ⬜ |
| 1.5.7 | Date validation | Input `2099-13-32`, `not-a-date`, end date before start date | ⬜ |
| 1.5.8 | File upload validation | Upload `.exe`, `.php`, `.html` files as images — must reject | ⬜ |
| 1.5.9 | File size limit (20MB) | Upload 25MB file — must reject | ⬜ |
| 1.5.10 | URL format validated | Input `notaurl`, `javascript:alert(1)` in URL fields | ⬜ |
| 1.5.11 | Button/hidden inputs validated | Tamper with hidden inputs and button values via devtools | ⬜ |

### 1.6 Rate Limiting
| # | Test Case | How to Test | Status |
|---|-----------|-------------|--------|
| 1.6.1 | Contact form: 5 emails/min limit | Send 6 contact form submissions in 1 minute — 6th must fail | ⬜ |
| 1.6.2 | Rate limit returns 429 status | Verify proper HTTP 429 response with retry-after header | ⬜ |
| 1.6.3 | Login rate limiting active | Attempt 10+ rapid login attempts | ⬜ |
| 1.6.4 | API rate limiting configured | Check `throttle` middleware on all API route groups | ⬜ |

### 1.7 Data Protection
| # | Test Case | How to Test | Status |
|---|-----------|-------------|--------|
| 1.7.1 | `.env` not in git | Check `.gitignore` includes `.env` | ⬜ |
| 1.7.2 | No secrets in code | `grep -r "password\|secret\|key" --include="*.php" --include="*.ts"` — verify no hardcoded values | ⬜ |
| 1.7.3 | API responses hide sensitive data | Check that password hashes never appear in API responses | ⬜ |
| 1.7.4 | Error messages don't leak internals | Trigger 500 error — verify no stack trace in response | ⬜ |
| 1.7.5 | Debug mode off in config | Verify `APP_DEBUG=false` in production config | ⬜ |

---

## 🔵 2. SEO Tests (20%)

### 2.1 Meta Tags
| # | Test Case | How to Test | Status |
|---|-----------|-------------|--------|
| 2.1.1 | Every page has unique `<title>` | View page source — each page must have distinct title | ⬜ |
| 2.1.2 | Every page has `<meta name="description">` | View source — description present and unique per page | ⬜ |
| 2.1.3 | Every page has `<meta name="keywords">` | View source — keywords present | ⬜ |
| 2.1.4 | Titles are admin-editable | Change title in admin panel → verify change on page | ⬜ |
| 2.1.5 | Descriptions are admin-editable | Change description → verify | ⬜ |
| 2.1.6 | Keywords are admin-editable | Change keywords → verify | ⬜ |
| 2.1.7 | Title length ≤ 60 characters | Validate admin can't exceed (or warning is shown) | ⬜ |
| 2.1.8 | Description length ≤ 160 characters | Validate or warn | ⬜ |

### 2.2 Open Graph & Social Sharing
| # | Test Case | How to Test | Status |
|---|-----------|-------------|--------|
| 2.2.1 | `og:title` present on all pages | View source | ⬜ |
| 2.2.2 | `og:description` present | View source | ⬜ |
| 2.2.3 | `og:image` present | View source — valid image URL | ⬜ |
| 2.2.4 | `og:url` present and correct | View source | ⬜ |
| 2.2.5 | `og:type` set correctly | `website` for pages, `article` for blog posts | ⬜ |
| 2.2.6 | Twitter Card meta tags present | `twitter:card`, `twitter:title`, `twitter:description` | ⬜ |
| 2.2.7 | WhatsApp link preview works | Share URL on WhatsApp — validate image/title/description | ⬜ |
| 2.2.8 | Facebook sharing works | Use [Facebook Sharing Debugger](https://developers.facebook.com/tools/debug/) | ⬜ |

### 2.3 Technical SEO
| # | Test Case | How to Test | Status |
|---|-----------|-------------|--------|
| 2.3.1 | SSR works — full HTML in source | `curl` the page — HTML must contain all content (not empty div) | ⬜ |
| 2.3.2 | `sitemap.xml` generated dynamically | Access `/sitemap.xml` — all pages listed including blog posts | ⬜ |
| 2.3.3 | `robots.txt` present | Access `/robots.txt` — admin routes excluded | ⬜ |
| 2.3.4 | Canonical URLs set | `<link rel="canonical">` on every page | ⬜ |
| 2.3.5 | `hreflang` tags for bilingual | `<link rel="alternate" hreflang="ar">` and `hreflang="en"` | ⬜ |
| 2.3.6 | `<html lang="ar">` for Arabic pages | Check lang attribute changes with locale | ⬜ |
| 2.3.7 | Clean URLs (no query strings) | URLs are `/en/blog/my-post-title` not `/blog?id=123` | ⬜ |
| 2.3.8 | Blog posts have JSON-LD schema | `<script type="application/ld+json">` with Article schema | ⬜ |
| 2.3.9 | Images have alt attributes | All `<img>` tags have descriptive `alt` | ⬜ |
| 2.3.10 | No broken internal links | Crawl site with a link checker tool | ⬜ |

### 2.4 HTML Structure
| # | Test Case | How to Test | Status |
|---|-----------|-------------|--------|
| 2.4.1 | One `<h1>` per page | DOM inspection — exactly one h1 | ⬜ |
| 2.4.2 | Proper heading hierarchy | h1 → h2 → h3, no skipping levels | ⬜ |
| 2.4.3 | Semantic elements used | `<nav>`, `<main>`, `<article>`, `<section>`, `<footer>` present | ⬜ |
| 2.4.4 | Class names are semantic | Classes describe content, not presentation (`hero-section` not `div-1`) | ⬜ |
| 2.4.5 | Proper `<time>` elements | Blog dates use `<time datetime="...">` | ⬜ |

### 2.5 Blog SEO (Priority!)
| # | Test Case | How to Test | Status |
|---|-----------|-------------|--------|
| 2.5.1 | Blog slugs from title | URLs are `/blog/how-to-build-software` not `/blog/123` | ⬜ |
| 2.5.2 | Blog has structured data | JSON-LD Article schema with author, date, headline | ⬜ |
| 2.5.3 | Blog images have alt text | All blog images have descriptive alt attributes | ⬜ |
| 2.5.4 | Blog has proper headings | h1 for title, h2 for sections | ⬜ |
| 2.5.5 | Blog excerpt/description unique | Each blog post has unique meta description | ⬜ |
| 2.5.6 | Blog pagination SEO-friendly | Proper prev/next links, canonical URLs | ⬜ |
| 2.5.7 | Blog bilingual URLs | `/en/blog/slug-en` and `/ar/blog/slug-ar` with hreflang | ⬜ |

---

## 🟢 3. Code Quality Tests (15%)

### 3.1 SOLID Principles
| # | Test Case | How to Test | Status |
|---|-----------|-------------|--------|
| 3.1.1 | Single Responsibility | Each class has one reason to change — controllers don't contain business logic | ⬜ |
| 3.1.2 | Services layer exists | Business logic in `Services/`, not in Controllers or Models | ⬜ |
| 3.1.3 | Open/Closed | New features added via extension, not modification of existing code | ⬜ |
| 3.1.4 | Dependency Injection used | Services injected via constructor, not `new` keyword | ⬜ |
| 3.1.5 | Interface Segregation | No god-interfaces — interfaces are focused | ⬜ |
| 3.1.6 | Controllers are thin | Controllers < 100 lines, max 5 public methods | ⬜ |
| 3.1.7 | No god classes | No class > 300 lines | ⬜ |

### 3.2 OOP & Clean Code
| # | Test Case | How to Test | Status |
|---|-----------|-------------|--------|
| 3.2.1 | Proper encapsulation | No public properties on models/services — use methods | ⬜ |
| 3.2.2 | Enums for fixed values | Roles, statuses use PHP Enums, not magic strings | ⬜ |
| 3.2.3 | No magic numbers | Constants or enums used instead of raw numbers | ⬜ |
| 3.2.4 | Meaningful variable names | No `$d`, `$tmp`, `$x` — all names are descriptive | ⬜ |
| 3.2.5 | Functions < 30 lines | No function exceeds 30 lines | ⬜ |
| 3.2.6 | Proper type hints | All function parameters and return types are typed | ⬜ |
| 3.2.7 | Composition over inheritance | Traits/interfaces used where appropriate | ⬜ |

### 3.3 DRY (Don't Repeat Yourself)
| # | Test Case | How to Test | Status |
|---|-----------|-------------|--------|
| 3.3.1 | No duplicate logic | Search for similar code blocks — must be extracted to helpers | ⬜ |
| 3.3.2 | Shared validation rules | Common validation rules in a trait or base request | ⬜ |
| 3.3.3 | API service centralized | Frontend has ONE API service, not fetch calls everywhere | ⬜ |
| 3.3.4 | CSS variables for colors | `grep -r "#[0-9a-fA-F]" --include="*.css"` — minimal hardcoded colors | ⬜ |
| 3.3.5 | Translation keys not duplicated | Translation files have no duplicate keys | ⬜ |
| 3.3.6 | Reusable components | Common UI patterns (buttons, cards, modals) are components | ⬜ |

### 3.4 Code Structure
| # | Test Case | How to Test | Status |
|---|-----------|-------------|--------|
| 3.4.1 | Consistent folder structure | Backend follows pattern in coding_rules.md | ⬜ |
| 3.4.2 | Frontend follows structure | Next.js follows pattern in coding_rules.md | ⬜ |
| 3.4.3 | Files < 300 lines | `find . -name "*.php" -o -name "*.tsx" | xargs wc -l | sort -rn | head` | ⬜ |
| 3.4.4 | No dead code | Unused imports, functions, variables removed | ⬜ |
| 3.4.5 | Proper error handling | Try-catch blocks with meaningful error messages | ⬜ |
| 3.4.6 | No console.log/dd() in production | Search for debug statements | ⬜ |

---

## 🟡 4. Functionality & Business Logic Tests (15%)

### 4.1 Authentication
| # | Test Case | How to Test | Status |
|---|-----------|-------------|--------|
| 4.1.1 | Admin can login | Seeded admin credentials work | ⬜ |
| 4.1.2 | Invalid credentials rejected | Wrong password → proper error message | ⬜ |
| 4.1.3 | Logout works | Token invalidated after logout | ⬜ |
| 4.1.4 | Password reset works | Full flow: request → email → reset → login | ⬜ |

### 4.2 Client Management
| # | Test Case | How to Test | Status |
|---|-----------|-------------|--------|
| 4.2.1 | Create client | All fields saved correctly (name, company, email, phone, WhatsApp) | ⬜ |
| 4.2.2 | Edit client | Update each field individually — verify persistence | ⬜ |
| 4.2.3 | Delete client | Client removed, linked projects NOT deleted | ⬜ |
| 4.2.4 | Default client undeleteable | Try to delete "Tarqumi" client — must fail | ⬜ |
| 4.2.5 | Client list with pagination | 20+ clients — verify pagination works | ⬜ |
| 4.2.6 | Client search works | Search by name, email — results accurate | ⬜ |
| 4.2.7 | Active/inactive toggle | Toggle status — verify filtering | ⬜ |

### 4.3 Project Management
| # | Test Case | How to Test | Status |
|---|-----------|-------------|--------|
| 4.3.1 | Create project | All fields saved (name, desc, client, PM, budget, currency, priority, dates) | ⬜ |
| 4.3.2 | Multiple clients per project | Assign 3 clients to one project | ⬜ |
| 4.3.3 | Default client when none selected | Create project without client → defaults to "Tarqumi" | ⬜ |
| 4.3.4 | Priority 1-10 slider | Slider only allows 1-10 range | ⬜ |
| 4.3.5 | 6 SDLC statuses | All 6 statuses available: Planning, Analysis, Design, Implementation, Testing, Deployment | ⬜ |
| 4.3.6 | Inactive projects hidden | Mark project inactive → no longer visible in CRM | ⬜ |
| 4.3.7 | Budget + currency combo | Set budget with different currencies — display correct symbols | ⬜ |
| 4.3.8 | Project manager from team | Only existing team members appear in PM dropdown | ⬜ |
| 4.3.9 | Start/end date validation | End date cannot be before start date | ⬜ |
| 4.3.10 | Edit project | Update all fields — verify persistence | ⬜ |

### 4.4 Team Management
| # | Test Case | How to Test | Status |
|---|-----------|-------------|--------|
| 4.4.1 | Create team member | All fields work (name, email, password, role) | ⬜ |
| 4.4.2 | Role assignment | Admin, Founder (CEO/CTO/CFO), Employee, HR | ⬜ |
| 4.4.3 | Founder sub-role selection | Selecting "Founder" shows sub-role dropdown | ⬜ |
| 4.4.4 | Delete member → reassign PM | Deleting a PM forces reassignment of their projects | ⬜ |
| 4.4.5 | 30-day inactivity rule | Inactive 30+ days → auto-marked inactive | ⬜ |
| 4.4.6 | Password reset by admin | Admin can reset any member's password | ⬜ |
| 4.4.7 | Employee can't edit profile | Employee login → no profile edit option | ⬜ |

### 4.5 Landing Page Content Management
| # | Test Case | How to Test | Status |
|---|-----------|-------------|--------|
| 4.5.1 | Edit page SEO (title, desc, keywords) | Edit each field → verify on frontend | ⬜ |
| 4.5.2 | Edit hero section text | Change text → verify instant update | ⬜ |
| 4.5.3 | Add/edit/remove service cards | CRUD operations on services | ⬜ |
| 4.5.4 | Add/edit/remove showcase projects | CRUD operations on showcase projects | ⬜ |
| 4.5.5 | Blog CRUD | Create, edit, delete blog posts in both languages | ⬜ |
| 4.5.6 | Upload/change logo | Upload logo → verify on landing page | ⬜ |
| 4.5.7 | Edit footer content | Change email, text, social links | ⬜ |
| 4.5.8 | Social media links dynamic | Add Facebook → appears. Remove → disappears | ⬜ |
| 4.5.9 | Contact email configuration | Set email(s) → send contact form → verify delivery | ⬜ |
| 4.5.10 | Multiple contact email recipients | Set 3 emails → all receive submissions | ⬜ |
| 4.5.11 | Instant revalidation | Edit content → refresh landing page → change visible immediately | ⬜ |
| 4.5.12 | OG image upload | Upload OG image → share URL → verify preview | ⬜ |

### 4.6 Contact Form
| # | Test Case | How to Test | Status |
|---|-----------|-------------|--------|
| 4.6.1 | Submit with valid data | Name, email, phone, message → email sent + stored in DB | ⬜ |
| 4.6.2 | Required fields enforced | Submit empty → validation errors | ⬜ |
| 4.6.3 | Email sent via SMTP | Check email delivery | ⬜ |
| 4.6.4 | Submission stored in database | Check database for contact_submissions record | ⬜ |
| 4.6.5 | Rate limit works | 6th submission in 1 minute → rejected | ⬜ |
| 4.6.6 | Success message displayed | After submit → user sees confirmation | ⬜ |

---

## 🟣 5. Performance Tests (10%)

| # | Test Case | How to Test | Status |
|---|-----------|-------------|--------|
| 5.1 | Landing page loads < 3 seconds | Lighthouse or browser devtools | ⬜ |
| 5.2 | No N+1 query problems | Use Laravel Debugbar — check query count per page | ⬜ |
| 5.3 | Images optimized | Images served in WebP or compressed format | ⬜ |
| 5.4 | Lazy loading for below-fold content | Images below fold use `loading="lazy"` | ⬜ |
| 5.5 | CSS/JS bundles minimized | Production build has minified assets | ⬜ |
| 5.6 | Database queries efficient | Check EXPLAIN on complex queries — use indexes | ⬜ |
| 5.7 | Pagination on all list endpoints | No endpoint returns 1000+ records at once | ⬜ |
| 5.8 | Lighthouse Performance ≥ 80 | Run Lighthouse audit | ⬜ |
| 5.9 | Lighthouse Best Practices ≥ 80 | Run Lighthouse audit | ⬜ |
| 5.10 | API response times < 500ms | All CRUD APIs respond within 500ms | ⬜ |
| 5.11 | SSR pages render correctly | Full HTML in view-source (not empty shell) | ⬜ |
| 5.12 | Eager loading used | All relationships loaded with `with()` — no lazy loads in loops | ⬜ |

---

## 🔵 6. i18n & RTL Tests (10%)

| # | Test Case | How to Test | Status |
|---|-----------|-------------|--------|
| 6.1 | All text comes from translation files | No hardcoded strings visible on any page | ⬜ |
| 6.2 | Arabic translation complete | Every key in `en.json` exists in `ar.json` | ⬜ |
| 6.3 | English translation complete | Every key in `ar.json` exists in `en.json` | ⬜ |
| 6.4 | Language switcher works | Toggle AR → EN → AR — all text changes | ⬜ |
| 6.5 | RTL layout in Arabic | `dir="rtl"` set, layout mirrors correctly | ⬜ |
| 6.6 | LTR layout in English | `dir="ltr"` set, layout is normal | ⬜ |
| 6.7 | URL format `/ar/` and `/en/` | URL routing correctly uses locale prefix | ⬜ |
| 6.8 | `<html lang>` correct | Arabic pages: `lang="ar"`, English: `lang="en"` | ⬜ |
| 6.9 | Form inputs support Arabic | Type Arabic text in all fields — renders correctly | ⬜ |
| 6.10 | Blog posts bilingual | Each blog post has AR and EN versions | ⬜ |
| 6.11 | Admin panel supports both languages | CRM panel works in both Arabic and English | ⬜ |
| 6.12 | Numbers formatted correctly | Arabic numerals vs Western numerals as appropriate | ⬜ |
| 6.13 | Dates formatted per locale | Date format matches locale expectations | ⬜ |
| 6.14 | Navigation mirrors in RTL | Nav items, dropdowns, icons properly flip | ⬜ |
| 6.15 | No text overflow in RTL | Long Arabic text doesn't break layouts | ⬜ |

---

## ⚪ 7. Accessibility & UX Tests (5%)

| # | Test Case | How to Test | Status |
|---|-----------|-------------|--------|
| 7.1 | Keyboard navigation works | Tab through all interactive elements | ⬜ |
| 7.2 | Focus indicators visible | Tab elements show focus outlines | ⬜ |
| 7.3 | Color contrast sufficient | Text readable against backgrounds (WCAG AA) | ⬜ |
| 7.4 | Responsive: mobile (375px) | Test at 375px width — no overflow or broken layout | ⬜ |
| 7.5 | Responsive: tablet (768px) | Test at 768px width | ⬜ |
| 7.6 | Responsive: desktop (1440px) | Test at 1440px width | ⬜ |
| 7.7 | Loading states exist | API calls show loading indicators | ⬜ |
| 7.8 | Error states exist | Failed API calls show user-friendly errors | ⬜ |
| 7.9 | Empty states exist | No data → shows "No projects yet" message, not blank | ⬜ |
| 7.10 | Success feedback | Actions (save, delete, create) show confirmation | ⬜ |
| 7.11 | Confirmation dialogs | Destructive actions (delete) require confirmation | ⬜ |
| 7.12 | Form validation inline | Errors shown next to fields, not only at top | ⬜ |
| 7.13 | Animations smooth (60fps) | No janky animations — smooth transitions | ⬜ |
| 7.14 | Interactive elements have hover states | Buttons, links, cards have hover effects | ⬜ |
| 7.15 | Lighthouse Accessibility ≥ 80 | Run Lighthouse audit | ⬜ |

---

## 🧪 8. Automated Test Coverage

### 8.1 Backend (Laravel PHPUnit)
| # | Test Area | What to Test | Status |
|---|-----------|--------------|--------|
| 8.1.1 | Auth tests | Login, logout, register, password reset, unauthorized access | ⬜ |
| 8.1.2 | Client CRUD tests | Create, read, update, delete, validation, default client protection | ⬜ |
| 8.1.3 | Project CRUD tests | All CRUD + multiple clients + SDLC statuses + PM assignment | ⬜ |
| 8.1.4 | Team CRUD tests | Create, roles, delete + reassign PM, 30-day inactivity | ⬜ |
| 8.1.5 | Blog CRUD tests | Create, edit, delete, bilingual, SEO fields | ⬜ |
| 8.1.6 | Service CRUD tests | Landing page services CRUD | ⬜ |
| 8.1.7 | Contact form tests | Submit, validation, rate limiting, email sending, DB storage | ⬜ |
| 8.1.8 | Landing page content tests | Edit SEO, edit content, instant revalidation trigger | ⬜ |
| 8.1.9 | Authorization tests | Each role tested against each endpoint | ⬜ |
| 8.1.10 | Validation tests | Every field's validation rules tested with invalid data | ⬜ |

### 8.2 Frontend (Jest/Testing Library)
| # | Test Area | What to Test | Status |
|---|-----------|--------------|--------|
| 8.2.1 | Component renders | All components render without errors | ⬜ |
| 8.2.2 | i18n renders | Components render correctly in both AR and EN | ⬜ |
| 8.2.3 | Form validation | Client-side validation shows errors | ⬜ |
| 8.2.4 | API error handling | Components handle API failure gracefully | ⬜ |
| 8.2.5 | RTL rendering | Components render correctly in RTL mode | ⬜ |

---

## 📋 9. Pre-Commit Checklist

Run through this before EVERY commit:

- [ ] All tests pass (`php artisan test`)
- [ ] No TypeScript errors (`npx tsc --noEmit`)
- [ ] No ESLint errors/warnings
- [ ] No hardcoded strings (all text in translation files)
- [ ] No `console.log`, `dd()`, or `dump()` in code
- [ ] No hardcoded colors (CSS variables used)
- [ ] New API endpoints documented
- [ ] New features have tests
- [ ] File sizes < 300 lines
- [ ] `.env` not staged for commit
- [ ] Commit message follows semantic format

---

## 📋 10. Pre-Deployment Checklist

Run through this before ANY deployment:

- [ ] `APP_DEBUG=false`
- [ ] `APP_ENV=production`
- [ ] All tests pass
- [ ] Lighthouse Performance ≥ 80
- [ ] Lighthouse SEO ≥ 90
- [ ] Lighthouse Accessibility ≥ 80
- [ ] Lighthouse Best Practices ≥ 80
- [ ] No security vulnerabilities (`npm audit`, `composer audit`)
- [ ] Rate limiting configured
- [ ] CORS configured properly
- [ ] HTTPS enforced
- [ ] Database migrations up to date
- [ ] First admin seeded
- [ ] SMTP configured and tested
- [ ] File upload directory writable
- [ ] Sitemap accessible
- [ ] Robots.txt accessible
- [ ] OG images working
- [ ] Both languages working
- [ ] RTL layout working
- [ ] Contact form delivers to all configured emails

---

**Total Test Cases: 180+**
**All must pass before production deployment.**
