# MASSIVE PROMPT FOR TARQUMI CRM DETAILED PROJECT TASKS

> **INSTRUCTIONS:** Copy this entire prompt and paste it into a new AI conversation (ChatGPT, Claude, or any AI assistant). This will generate extremely detailed technical tasks that go beyond the user stories and serve as the actual implementation blueprint for the project.

---

## 🎯 YOUR MISSION

You are a **Senior Full-Stack Architect and Technical Lead** with 15+ years of experience building enterprise-grade web applications. You have deep expertise in:
- Laravel (PHP) backend development with SOLID principles
- Next.js (React/TypeScript) frontend development with SSR
- MySQL database design and optimization
- Security best practices (SQL injection, XSS, CSRF prevention)
- SEO optimization and internationalization (i18n)
- Clean Code, DRY, OOP, and software architecture patterns

Your task is to create **THE MOST DETAILED PROJECT TASKS DOCUMENT EVER CREATED** for the Tarqumi CRM project. These tasks will be MORE detailed than the user stories — they are the actual step-by-step implementation guide that developers will follow to build the entire system.

---

## 📚 CONTEXT: WHAT YOU'RE BUILDING

**Project Name:** Tarqumi CRM + Landing Page  
**Organization:** Tarqumi (ترقمي) — A software development company (10-50 employees)  
**Purpose:** Internal CRM system for managing clients, projects, team members + Public bilingual landing page

### Tech Stack:
- **Frontend:** Next.js 14+ (App Router, TypeScript, SSR for SEO)
- **Backend:** Laravel 11+ (PHP 8.2+, Sanctum auth, Eloquent ORM)
- **Database:** MySQL 8.0+ (relational, properly indexed)
- **Authentication:** Laravel Sanctum (token-based API auth)
- **i18n:** Arabic + English (manual translation, full RTL support)
- **File Storage:** Local filesystem (Laravel storage)
- **Email:** SMTP (Google App Passwords or similar)

### Project Structure:
```
tarqumi-crm/
├── backend/          # Laravel API
│   ├── app/
│   ├── database/
│   ├── routes/
│   └── tests/
├── frontend/         # Next.js SSR app
│   ├── app/
│   ├── components/
│   ├── lib/
│   ├── messages/     # i18n translations
│   └── types/
└── docs/             # Documentation (you've read these)
```

---

## 📖 REQUIRED READING (CRITICAL!)

Before generating tasks, you MUST thoroughly understand these documents:

### 1. User Stories (10 files, 100+ stories)
Located in `user-stories/` folder:
- `01-authentication-security.md` — Auth, RBAC, security testing
- `02-team-management.md` — Team CRUD, roles, permissions
- `03-client-management.md` — Client CRUD, default client protection
- `04-project-management.md` — Project CRUD, SDLC phases, budget tracking
- `05-landing-page-cms.md` — CMS for all landing page content
- `06-blog-system.md` — Blog CRUD, SEO optimization
- `07-contact-communication.md` — Contact form, email delivery
- `08-public-landing-page.md` — Visitor experience, all public pages
- `09-seo-technical-infrastructure.md` — SSR, sitemap, robots.txt, meta tags
- `10-quality-assurance-testing.md` — Security, validation, performance testing

**Each user story contains:**
- User story format: "As a [role], I want to [action], so that [benefit]"
- Detailed Acceptance Criteria (numbered)
- Edge Cases (EC-1, EC-2, etc.)
- Priority levels (🔴 Critical | 🟠 High | 🟡 Medium | 🟢 Nice-to-have)
- Security considerations
- Responsive design requirements
- Performance targets
- UX considerations

### 2. Coding Rules (`docs/coding_rules.md`)
**NON-NEGOTIABLE engineering standards:**
- 🔴 Security: SQL injection prevention, XSS prevention, CSRF protection, input validation on EVERYTHING
- 🟠 Architecture: SOLID principles, OOP, Clean Code, DRY
- 🟡 Laravel Backend: Thin controllers, service layer, proper migrations, API resources
- 🟢 Next.js Frontend: Functional components, TypeScript, CSS modules, i18n
- 🔵 Database: InnoDB, UTF8MB4, proper indexing, foreign key constraints
- 🟣 Testing: Unit tests, feature tests, edge case coverage
- Git: Semantic commits, feature branches

### 3. Meeting Notes (`docs/meeting_notes.md`)
**Business requirements and decisions:**
- Bilingual (AR/EN) with full RTL support
- Black & white design with animations
- SEO is CRITICAL (especially for blog)
- Admin controls ALL landing page content
- Contact form: 5 emails/min rate limit, no CAPTCHA
- Default "Tarqumi" client cannot be deleted
- Projects can have MULTIPLE clients
- 6 SDLC phases for project status
- Priority scale 1-10 (not High/Medium/Low)
- 30-day inactivity → auto-inactive team member
- Employee cannot edit own profile
- Only Super Admin can delete other Admins
- Only Admin and CTO can edit landing page

### 4. Benchmarks & Tests (`docs/benchmarks_and_tests.md`)
**180+ test cases across:**
- Security (25% weight) — SQL injection, XSS, CSRF, auth, validation, rate limiting
- SEO (20% weight) — Meta tags, OG, structured data, SSR, sitemap
- Code Quality (15% weight) — SOLID, OOP, DRY, clean code
- Functionality (15% weight) — All CRUD operations, business logic
- Performance (10% weight) — Load times, N+1 queries, optimization
- i18n & RTL (10% weight) — Translation completeness, RTL layout
- Accessibility & UX (5% weight) — Keyboard nav, responsive, loading states

**Minimum passing score: 80/100. Security must score ≥ 90/100.**

---

## 🎯 YOUR TASK: GENERATE DETAILED TECHNICAL TASKS

Create a comprehensive task breakdown document with the following structure:

### Task Document Structure:

```markdown
# Tarqumi CRM — Detailed Technical Tasks

## Module 1: Project Setup & Infrastructure
### Task 1.1: Initialize Backend (Laravel)
**Priority:** 🔴 Critical  
**Estimated Time:** 4 hours  
**Dependencies:** None  
**Assigned To:** Backend Developer

**Objective:**
Set up a fresh Laravel 11 project with all required dependencies, configuration, and folder structure.

**Detailed Steps:**
1. Install Laravel 11 via Composer: `composer create-project laravel/laravel backend`
2. Configure `.env` file:
   - Set `APP_NAME=Tarqumi CRM`
   - Set `APP_ENV=local`
   - Set `APP_DEBUG=true` (local only)
   - Configure database connection (MySQL)
   - Set `DB_DATABASE=tarqumi_crm`
   - Set `DB_USERNAME` and `DB_PASSWORD`
3. Install Laravel Sanctum: `composer require laravel/sanctum`
4. Publish Sanctum config: `php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"`
5. Run migrations: `php artisan migrate`
6. Create folder structure:
   - `app/Services/` — Business logic layer
   - `app/Enums/` — PHP enums for roles, statuses
   - `app/Policies/` — Authorization policies
   - `app/Observers/` — Model observers
   - `app/Traits/` — Shared behavior traits
   - `app/Http/Requests/` — Form request validation
   - `app/Http/Resources/` — API resource transformers
7. Configure CORS in `config/cors.php`:
   - Allow frontend origin (http://localhost:3000 for dev)
   - Allow credentials
   - Expose headers: Authorization
8. Add Sanctum middleware to `api` route group in `app/Http/Kernel.php`
9. Create `.env.example` with all required variables (no values)
10. Add `.env` to `.gitignore` (verify it's there)
11. Initialize Git: `git init`, `git add .`, `git commit -m "feat: initialize Laravel backend"`

**Acceptance Criteria:**
- [ ] Laravel 11 installed and running
- [ ] Database connection successful
- [ ] Sanctum installed and configured
- [ ] Folder structure matches coding_rules.md
- [ ] `.env` not tracked in Git
- [ ] CORS configured for frontend
- [ ] Initial commit made

**Testing:**
- Run `php artisan serve` — should start without errors
- Run `php artisan migrate:status` — should show migrations table
- Run `php artisan route:list` — should show default routes

**Files Created:**
- `backend/.env`
- `backend/config/sanctum.php`
- `backend/app/Services/.gitkeep`
- `backend/app/Enums/.gitkeep`
- `backend/app/Policies/.gitkeep`
- `backend/app/Observers/.gitkeep`
- `backend/app/Traits/.gitkeep`

**Security Considerations:**
- Ensure `.env` is in `.gitignore`
- Set strong `APP_KEY` (auto-generated)
- Configure `SESSION_SECURE_COOKIE=true` for production

**Notes:**
- Keep `APP_DEBUG=true` only in local environment
- Document all environment variables in `.env.example`
- Use `php artisan key:generate` if APP_KEY is missing

---

### Task 1.2: Initialize Frontend (Next.js)
[Similar detailed breakdown...]

---

## Module 2: Database Design & Migrations
### Task 2.1: Create Users Table Migration
[Detailed breakdown...]

### Task 2.2: Create Roles Enum
[Detailed breakdown...]

[Continue for ALL tables...]

---

## Module 3: Authentication & Authorization
### Task 3.1: Create User Model with Sanctum
[Detailed breakdown...]

### Task 3.2: Implement Login API Endpoint
[Detailed breakdown...]

[Continue...]

---

## Module 4: Team Management (Backend)
## Module 5: Team Management (Frontend)
## Module 6: Client Management (Backend)
## Module 7: Client Management (Frontend)
## Module 8: Project Management (Backend)
## Module 9: Project Management (Frontend)
## Module 10: Landing Page CMS (Backend)
## Module 11: Landing Page CMS (Frontend)
## Module 12: Blog System (Backend)
## Module 13: Blog System (Frontend)
## Module 14: Contact Form (Backend)
## Module 15: Contact Form (Frontend)
## Module 16: Public Landing Pages (Frontend)
## Module 17: SEO Infrastructure
## Module 18: i18n & RTL Implementation
## Module 19: Security Hardening
## Module 20: Testing & Quality Assurance
## Module 21: Performance Optimization
## Module 22: Deployment Preparation
```

---

## 📋 REQUIREMENTS FOR EACH TASK

Every single task you generate MUST include:

### 1. Task Header
- **Task ID:** Module.TaskNumber (e.g., 3.1, 3.2)
- **Task Title:** Clear, action-oriented (e.g., "Create Login API Endpoint")
- **Priority:** 🔴 Critical | 🟠 High | 🟡 Medium | 🟢 Nice-to-have
- **Estimated Time:** Realistic time estimate (hours or days)
- **Dependencies:** List of tasks that must be completed first
- **Assigned To:** Role (Backend Dev, Frontend Dev, Full-Stack Dev, DevOps)

### 2. Objective
- One paragraph explaining WHAT this task accomplishes and WHY it's important

### 3. Detailed Steps
- **Numbered list** of EVERY SINGLE STEP to complete the task
- Include exact commands to run
- Include exact file paths
- Include exact code snippets where helpful
- Include configuration changes
- Include database queries if needed
- Be SO detailed that a junior developer could follow it

### 4. Acceptance Criteria
- **Checklist format** (- [ ] item)
- Specific, measurable, testable criteria
- Must align with user story acceptance criteria
- Include edge cases from user stories

### 5. Testing
- How to manually test this task
- What automated tests to write
- Expected behavior
- How to verify success

### 6. Files Created/Modified
- List of all files this task touches
- Specify if creating new or modifying existing

### 7. Security Considerations
- Any security implications
- Validation rules
- Authorization checks
- Input sanitization

### 8. Performance Considerations (if applicable)
- Database indexing
- Query optimization
- Caching strategy
- N+1 query prevention

### 9. i18n Considerations (if applicable)
- Translation keys to add
- RTL layout considerations
- Locale-specific formatting

### 10. Notes
- Any gotchas or common mistakes
- Links to documentation
- Alternative approaches considered
- Future improvements (Phase 2/3)

---

## 🎨 TASK ORGANIZATION RULES

### Module Grouping:
- Group tasks by **feature module** (not by frontend/backend split)
- Each module should be **independently deployable** when complete
- Modules should follow **dependency order** (auth before team management, etc.)

### Task Granularity:
- Each task should take **2-8 hours** for an experienced developer
- If a task is > 8 hours, split it into subtasks
- If a task is < 1 hour, consider combining with related tasks

### Numbering System:
- Module numbers: 1, 2, 3, etc.
- Task numbers within module: 1.1, 1.2, 1.3, etc.
- Subtasks (if needed): 1.1.1, 1.1.2, etc.

### Priority Assignment:
- 🔴 Critical: Blocks other work, security-related, core functionality
- 🟠 High: Important features, user-facing, SEO-related
- 🟡 Medium: Nice-to-have features, optimizations
- 🟢 Low: Future enhancements, polish, Phase 2/3 prep

---

## 🔥 CRITICAL REQUIREMENTS

### Security Tasks:
- **EVERY input validation rule** must have its own task or subtask
- **EVERY API endpoint** must have authorization checks documented
- **SQL injection prevention** must be explicitly mentioned in database tasks
- **XSS prevention** must be explicitly mentioned in output tasks
- **CSRF protection** must be verified in form tasks
- **Rate limiting** must be configured for contact form and login

### SEO Tasks:
- **EVERY page** must have a task for meta tags implementation
- **Blog SEO** must have dedicated tasks (structured data, OG tags, etc.)
- **Sitemap generation** must be a separate task
- **Robots.txt** must be a separate task
- **SSR verification** must be in testing tasks

### i18n Tasks:
- **Translation file structure** must be a task
- **EVERY new feature** must include "Add translation keys" as a step
- **RTL layout** must be tested in acceptance criteria
- **Language switcher** must be a dedicated task

### Database Tasks:
- **EVERY table** must have its own migration task
- **EVERY foreign key** must have ON DELETE behavior specified
- **EVERY searchable column** must have an index
- **Seeder for first admin** must be a task
- **Seeder for default "Tarqumi" client** must be a task

### Testing Tasks:
- **EVERY module** must have a corresponding testing task
- **Security tests** must be a dedicated module
- **Edge case tests** from user stories must be included
- **180+ test cases** from benchmarks_and_tests.md must be covered

---

## 📊 EXPECTED OUTPUT

Generate a **MASSIVE MARKDOWN DOCUMENT** with:

### Estimated Totals:
- **200-300 detailed tasks** across all modules
- **20-25 modules** organized by feature
- **Every task** following the template above
- **Every user story** mapped to one or more tasks
- **Every test case** from benchmarks_and_tests.md covered

### Document Structure:
```markdown
# Tarqumi CRM — Detailed Technical Tasks
## Table of Contents
[Auto-generated TOC with all modules and tasks]

## Project Overview
[Brief summary]

## Task Statistics
- Total Modules: X
- Total Tasks: Y
- Estimated Total Time: Z hours
- Critical Priority: N tasks
- High Priority: N tasks
- Medium Priority: N tasks
- Low Priority: N tasks

## Module 1: Project Setup & Infrastructure
### Task 1.1: [Title]
[Full task details as per template]

### Task 1.2: [Title]
[Full task details as per template]

[Continue for ALL modules and tasks...]

## Appendix A: Task Dependency Graph
[Visual or text representation of task dependencies]

## Appendix B: User Story to Task Mapping
[Table showing which tasks implement which user stories]

## Appendix C: Test Coverage Matrix
[Table showing which tasks include which test cases]
```

---

## 🚀 ADDITIONAL INSTRUCTIONS

### Code Examples:
- Include **actual code snippets** in tasks where helpful
- Use proper syntax highlighting (```php, ```typescript, etc.)
- Show **before and after** for modifications
- Include **comments** explaining complex logic

### Commands:
- Provide **exact terminal commands** to run
- Include **expected output** where helpful
- Specify **working directory** if not obvious

### Configuration:
- Show **exact configuration values** to set
- Explain **why** each config is needed
- Include **environment-specific** variations (dev vs prod)

### Error Handling:
- Include **common errors** developers might encounter
- Provide **troubleshooting steps**
- Link to **relevant documentation**

### Best Practices:
- Reference **coding_rules.md** principles in tasks
- Enforce **SOLID, OOP, DRY** in implementation steps
- Remind about **security** in every relevant task
- Remind about **i18n** in every user-facing task

---

## ✅ VALIDATION CHECKLIST

Before submitting your generated tasks document, verify:

- [ ] Every user story from all 10 files is covered by at least one task
- [ ] Every test case from benchmarks_and_tests.md is covered
- [ ] Every requirement from meeting_notes.md is addressed
- [ ] Every coding rule from coding_rules.md is enforced in tasks
- [ ] All security requirements are explicitly mentioned
- [ ] All SEO requirements are explicitly mentioned
- [ ] All i18n requirements are explicitly mentioned
- [ ] Database design is complete (all tables, relationships, indexes)
- [ ] API endpoints are fully specified (routes, methods, auth, validation)
- [ ] Frontend components are fully specified (props, state, styling)
- [ ] Testing tasks cover unit, feature, and integration tests
- [ ] Performance optimization tasks are included
- [ ] Deployment preparation tasks are included
- [ ] Task dependencies are logical and complete
- [ ] Time estimates are realistic
- [ ] Every task follows the required template
- [ ] Document is well-organized and easy to navigate

---

## 🎯 FINAL NOTES

This is **THE FOUNDATION** of the entire project. These tasks will be:
- Given to developers as their implementation guide
- Used for project planning and time estimation
- Used for progress tracking and milestone definition
- Used for code review checklists
- Used for QA testing plans

**Make it PERFECT. Make it COMPLETE. Make it DETAILED.**

The quality of this task document will directly determine the quality of the final product.

**DO NOT RUSH. TAKE YOUR TIME. BE THOROUGH.**

---

## 🚀 BEGIN GENERATION

Now, generate the complete detailed technical tasks document following ALL the requirements above.

Start with:
```markdown
# Tarqumi CRM — Detailed Technical Tasks
*Generated: [Current Date]*
*Version: 1.0*
*Status: Ready for Implementation*

---

## Table of Contents
[Generate full TOC]

---

## Project Overview
[Brief overview]

---

## Task Statistics
[Statistics]

---

## Module 1: Project Setup & Infrastructure
### Task 1.1: Initialize Backend (Laravel)
[Full details...]
```

**GO!**
