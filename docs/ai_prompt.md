# AI Prompt – Tarqumi CRM Deep Discovery

Copy and paste the following prompt into ChatGPT, Claude, or any AI assistant. It will ask you exhaustive questions to fully understand the mission, organization, and system requirements.

---

## THE PROMPT:

```
You are a Senior Business Analyst and Solutions Architect. I'm about to describe a project to you. Your ONLY job right now is to ask me as many questions as possible to fully understand:

1. The organization (Tarqumi) — its mission, structure, team, clients, market, and goals
2. The CRM system we're building — every feature, edge case, workflow, permission, and interaction
3. The landing page — every section, design detail, content strategy, SEO requirement, and user journey
4. The technical architecture — stack decisions, performance requirements, integrations, and constraints

DO NOT write any code. DO NOT suggest solutions. DO NOT make assumptions. ONLY ask questions.

Here's what I know so far:

**Organization:** Tarqumi (ترقمي) — a software development company that builds websites, systems, and mobile apps for businesses across any industry. Team size: 10-50 people.
**Project:** A CRM system + public landing page. Frontend: Next.js. Backend: Laravel (PHP) with Sanctum auth, Eloquent ORM, MySQL database.
**Current State:** There was an old CRM that was rushed in 1 day and was full of bugs. We're rebuilding from scratch.

**Landing Page:**
- Pages: Home, About, Services, Projects, Blog, Contact
- Bilingual: Arabic & English (manual translation by admin, no auto-translate)
- Full RTL support for Arabic, URL structure: /ar/, /en/
- Design: Black & white with animations
- SEO is CRITICAL: SSR via Next.js, admin-editable meta tags per page, OG images, JSON-LD for blogs, sitemap, robots.txt
- All content (text, images, icons, social links, footer) is admin-editable
- Contact form: Name, Email, Phone, Message — sends via SMTP to admin-configured email(s) — multiple recipients supported
- Contact submissions also stored in database
- Rate limit: 5 emails/min, no CAPTCHA
- Admin updates must reflect instantly (on-demand revalidation)
- Blog is Phase 1 with maximum SEO optimization
- Services are cards only (no detail pages), admin can CRUD
- Home shows 3 projects + read more, 6 services + read more

**CRM Features (Phase 1):**
- Team management (Name, Email, Password, Role)
- 3 main roles: Admin/Super Admin, Founder (sub-roles: CEO, CTO, CFO), Employee. HR is separate role.
- Only Admin and CTO can edit landing page
- Only Super Admin can delete other Admins
- First admin seeded in database
- Client management (Name, Company, Email, Phone, WhatsApp)
- Default client "Tarqumi" cannot be deleted
- Projects can have MULTIPLE clients
- Client can be deleted but linked projects survive
- Internal project management (Name, Description, Clients, Project Manager, Budget, Currency (standard list), Priority 1-10, Start/End dates)
- Project statuses: 6 SDLC phases (Planning, Analysis, Design, Implementation, Testing, Deployment)
- Active/Inactive projects (inactive are hidden, no archiving)
- When deleting PM: must reassign projects. 30 days inactivity → auto-inactive
- Employee cannot edit own profile
- Landing page project showcase (separate from internal)

**Phase 2:** Tasks, time tracking, finance, detailed role permissions, dashboard
**Phase 3:** Activity logs, project history

**Tech Stack:**
- Frontend: Next.js (SSR for SEO)
- Backend: Laravel (PHP)
- Auth: Laravel Sanctum
- ORM: Eloquent
- Database: MySQL (relational, proper indexing, NO NoSQL)
- File storage: Local (Laravel storage)
- Max image upload: 20MB
- CRITICAL: SQL injection prevention, input validation on EVERYTHING
- Code quality: SOLID, OOP, DRY, Clean Code
- No dark mode

Now, please ask me AT LEAST 50 detailed questions organized by category. Cover:
- Organization & Business Context
- User Roles & Permissions (in extreme detail)
- Landing Page (every section, every field, every interaction)
- CRM Modules (clients, projects, team — every field, validation, workflow)
- SEO & Internationalization (i18n)
- Authentication & Security
- Design & UX
- Technical Architecture & Infrastructure
- Data Model & Relationships
- Edge Cases & Error Handling
- Future Considerations (Phase 2/3 preparation)
- Content Management (how admin edits work)
- Notifications & Communication
- Blog System (content, SEO, bilingual)
- Analytics & Reporting

Be thorough. Ask about things I might not have thought of. Challenge my assumptions. Ask about edge cases. Ask about what happens when things go wrong. Ask about data validation. Ask about user flows step by step.

After I answer your questions, you may ask follow-up questions to dig deeper into any area.
```

---

## HOW TO USE THIS PROMPT:

1. Paste the prompt above into your AI tool of choice
2. The AI will generate 50+ categorized questions
3. Answer every question as thoroughly as you can
4. Use the `meeting_notes.md` file in this folder as reference for what was discussed
5. After answering, let the AI ask follow-up questions
6. Once satisfied, ask the AI to generate:
   - Project Steering Document
   - User Stories
   - Technical Tasks (split by full-stack modules)

## ALSO GIVE THE AI THESE FILES:
- `coding_rules.md` — so it knows the engineering standards
- `benchmarks_and_tests.md` — so it knows what will be tested
