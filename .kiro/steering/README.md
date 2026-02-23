# Tarqumi CRM - Agent Steering Documentation

This directory contains comprehensive steering files for AI-assisted development of the Tarqumi CRM system.

## Steering Files Overview

### Always Included (Priority Order)
1. **00-project-overview.md** — Project mission, components, tech stack, critical requirements
2. **01-security-rules.md** — SQL injection, XSS, CSRF, authentication, validation (NON-NEGOTIABLE)
3. **02-architecture-solid.md** — SOLID principles, OOP, clean code, Laravel structure
4. **03-frontend-nextjs.md** — Next.js structure, components, styling, SEO rules
5. **04-database-mysql.md** — MySQL config, indexing, naming, migrations, key tables
6. **05-user-roles-permissions.md** — Role hierarchy, permission matrix, business rules
7. **06-seo-requirements.md** — SSR, meta tags, Open Graph, blog SEO, sitemap (CRITICAL)
8. **07-i18n-rtl.md** — Internationalization, RTL support, translation keys, bilingual content
9. **08-testing-quality.md** — Test coverage, quality standards, pre-commit/deployment checklists
10. **10-api-conventions.md** — RESTful standards, response format, status codes, pagination
11. **11-git-workflow.md** — Commit messages, branch strategy, code review

### Manual Inclusion
- **09-user-stories-reference.md** — Quick reference to all 55 user stories (use when implementing features)

## How to Use These Files

### For AI Assistants
These files are automatically loaded based on their `inclusion` setting:
- `inclusion: always` — Loaded for every interaction
- `inclusion: manual` — Load explicitly when needed (e.g., `#user-stories-reference`)

### Priority System
Files are numbered 00-11 to indicate loading priority. Lower numbers = higher priority.

### For Developers
1. Read **00-project-overview.md** first to understand the project
2. Review **01-security-rules.md** — these are non-negotiable
3. Reference specific files as needed during development
4. Use **09-user-stories-reference.md** when implementing features
5. Check **08-testing-quality.md** before commits and deployments

## Key Principles

### Security First
- SQL injection prevention is CRITICAL
- Validate EVERY input on EVERY endpoint
- Never trust user input
- See: 01-security-rules.md

### Code Quality
- SOLID principles mandatory
- DRY, KISS, YAGNI
- Files < 300 lines
- Functions < 30 lines
- See: 02-architecture-solid.md

### SEO Excellence
- SSR for all landing pages
- Blog SEO is maximum priority
- Dynamic sitemap and robots.txt
- See: 06-seo-requirements.md

### Bilingual & RTL
- ZERO hardcoded strings
- Full RTL support for Arabic
- Both languages required before commit
- See: 07-i18n-rtl.md

### Testing
- Minimum 80/100 total score
- Security must be ≥90/100
- All tests pass before merge
- See: 08-testing-quality.md

## Quick Reference

### Tech Stack
- Frontend: Next.js (SSR)
- Backend: Laravel (PHP)
- Auth: Sanctum
- Database: MySQL
- ORM: Eloquent

### Phase 1 Scope
- Landing page (Home, About, Services, Projects, Blog, Contact)
- Blog system with maximum SEO
- CRM: Team, Client, Project management
- Bilingual (AR/EN) with RTL
- Role-based access control

### Critical Business Rules
- Default "Tarqumi" client cannot be deleted
- Only Super Admin can delete other Admins
- Employee cannot edit own profile
- CTO is only founder who can edit landing page
- HR is a **separate role** (NOT a founder sub-role)
- Projects can have multiple clients
- 30-day inactivity → auto-inactive
- PM deletion requires project reassignment
- Phase 1: Only Admin/Super Admin can CRUD clients & projects
- Founder roles (CTO/CEO/CFO) can VIEW CRM data only in Phase 1

## Documentation References
- Full user stories: `docs/user_stories.md`
- Coding rules: `docs/coding_rules.md`
- Test benchmarks: `docs/benchmarks_and_tests.md`
- Meeting notes: `docs/meeting_notes.md`

## Contact
For questions or clarifications, refer to the project documentation in the `docs/` directory.
