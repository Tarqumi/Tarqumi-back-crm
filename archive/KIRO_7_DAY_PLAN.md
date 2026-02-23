# Tarqumi CRM - 7-Day Development with Kiro AI

> **You (Human) + Kiro AI (with full IDE access) = Complete CRM in 7 days**
> **Kiro will read ALL project files and generate complete, production-ready code**

---

## 🎯 How This Works

### Setup (One Time):
1. Open this project in Kiro IDE
2. Kiro will have access to ALL files:
   - `/docs/` - Requirements, coding rules, meeting notes
   - `/tasks/` - Detailed task breakdowns
   - `/user-stories/` - Feature specifications
   - All existing documentation

### Daily Workflow:
1. You give Kiro a high-level instruction for the day
2. Kiro reads ALL relevant project files automatically
3. Kiro generates complete code (backend + frontend)
4. You review, test, and approve
5. Move to next day

### Kiro's Advantages:
- ✅ Reads ALL project documentation automatically
- ✅ Understands business rules from meeting notes
- ✅ Follows coding standards from coding_rules.md
- ✅ References user stories for features
- ✅ Checks benchmarks for quality
- ✅ Generates tests automatically
- ✅ Creates files in correct locations
- ✅ Maintains consistency across codebase

---

## 📋 Project File Structure (What Kiro Will Read)

```
Tarqumi-CRM/
├── docs/
│   ├── ai_prompt.md              # AI instructions
│   ├── coding_rules.md           # Code standards (SOLID, OOP, DRY, Security)
│   ├── meeting_notes.md          # Business requirements
│   ├── user_stories.md           # Feature specifications
│   ├── benchmarks_and_tests.md   # Quality criteria
│   └── MASSIVE_TASKS_PROMPT.md   # Detailed task descriptions
│
├── tasks/
│   ├── 01-project-setup-infrastructure.md
│   ├── 02-database-models-migrations.md
│   ├── 03-authentication-authorization.md
│   ├── 04-team-management-crud.md
│   ├── 05-client-management-crud.md
│   ├── 06-project-management-crud.md
│   ├── 07-landing-page-cms.md
│   ├── 08-blog-system.md
│   ├── 09-contact-form-email.md
│   └── 10-seo-testing-deployment.md
│
├── user-stories/
│   ├── 01-authentication-security.md
│   ├── 02-team-management.md
│   ├── 03-client-management.md
│   ├── 04-project-management.md
│   ├── 05-landing-page-cms.md
│   ├── 06-blog-system.md
│   ├── 07-contact-communication.md
│   ├── 08-public-landing-page.md
│   ├── 09-seo-technical-infrastructure.md
│   └── 10-quality-assurance-testing.md
│
├── EXECUTION_PLAN.md             # Overall execution strategy
├── QUICK_START_GUIDE.md          # Setup instructions
└── 7_DAY_SUMMARY.md              # Daily breakdown
```

---

## 📅 7-Day Execution Plan

### **DAY 1: Foundation & Database** (8-10 hours)

**Your Instruction to Kiro:**
```
Read all files in /docs/, /tasks/01-project-setup-infrastructure.md, 
/tasks/02-database-models-migrations.md, /tasks/03-authentication-authorization.md, 
and /user-stories/01-authentication-security.md.

Then create:
1. Complete Laravel 11 backend setup with Sanctum
2. Complete Next.js 14 frontend setup with TypeScript and i18n
3. All database migrations (users, clients, projects with relationships)
4. Complete authentication system (login, logout, refresh)
5. Complete RBAC system (roles, permissions, middleware)
6. All seeders (3 admin users, default Tarqumi client)

Follow ALL requirements from docs/coding_rules.md and docs/meeting_notes.md.
Ensure SQL injection prevention, input validation, and bilingual support.
```

**What Kiro Will Do:**
- Read all referenced files
- Understand business rules (default client, 30-day inactive, etc.)
- Follow SOLID principles from coding_rules.md
- Generate complete backend structure
- Generate complete frontend structure
- Create all migrations with proper indexes
- Create models with relationships, scopes, accessors
- Create enums for roles and statuses
- Create authentication controllers and services
- Create frontend auth pages with i18n and RTL
- Create RBAC middleware and permissions
- Generate tests for everything
- Ensure security (SQL injection prevention, input validation)

**Deliverables:**
- ✅ Laravel backend fully setup
- ✅ Next.js frontend fully setup
- ✅ Database schema complete
- ✅ Authentication working
- ✅ RBAC implemented
- ✅ All tests passing

---

### **DAY 2: Core CRUD Operations** (10-12 hours)

**Your Instruction to Kiro:**
```
Read /tasks/04-team-management-crud.md, /tasks/05-client-management-crud.md, 
/tasks/06-project-management-crud.md, and corresponding user stories.

Then create complete CRUD operations (backend + frontend) for:
1. Team Management
   - Create, list, edit, delete team members
   - Profile picture upload
   - Project reassignment when deleting PM
   - Search, filters, pagination
   - Role-based permissions

2. Client Management
   - Create, list, edit, delete clients
   - Protect default "Tarqumi" client
   - Search, filters, pagination
   - Soft delete preserves projects

3. Project Management
   - Create, list, edit, delete projects
   - Multiple client selection
   - Auto-generated project codes (PROJ-2024-0001)
   - 6 SDLC status phases
   - Priority slider (1-10)
   - Budget with currency
   - Search, filters, pagination, Kanban view

Follow ALL business rules from docs/meeting_notes.md.
Ensure bilingual support and RTL layout.
```

**What Kiro Will Do:**
- Read all task files and user stories
- Understand business rules (default client protection, project reassignment, etc.)
- Generate complete backend APIs (controllers, requests, services, resources)
- Generate complete frontend pages (list, create, edit, delete)
- Implement search, filters, sorting, pagination
- Create form validation (backend + frontend)
- Handle file uploads (profile pictures)
- Implement soft deletes
- Generate comprehensive tests
- Ensure security and validation

**Deliverables:**
- ✅ Team Management complete (backend + frontend)
- ✅ Client Management complete (backend + frontend)
- ✅ Project Management complete (backend + frontend)
- ✅ All CRUD operations working
- ✅ Search and filters working
- ✅ All tests passing

---

### **DAY 3: Landing Page & CMS** (8-10 hours)

**Your Instruction to Kiro:**
```
Read /tasks/07-landing-page-cms.md, /user-stories/05-landing-page-cms.md, 
and /user-stories/08-public-landing-page.md.

Then create:
1. Backend CMS API
   - Landing page content management
   - Service cards CRUD
   - Showcase projects CRUD
   - SEO fields (title, description, keywords, og_image)
   - Bilingual content storage
   - On-demand revalidation endpoint

2. Frontend Public Pages (with SSR)
   - Home page (hero, services preview, projects preview)
   - About page
   - Services page (all service cards)
   - Projects page (all showcase projects)
   - All with dynamic meta tags and SEO

3. Admin CMS UI
   - Page selector and content editor
   - Service cards management
   - Showcase projects management
   - SEO fields editor
   - Image upload

Only CTO founder can edit landing page (check permissions).
Ensure instant revalidation when admin updates content.
```

**What Kiro Will Do:**
- Read all landing page requirements
- Understand CTO-only permission rule
- Generate backend CMS API with all endpoints
- Generate SSR pages with proper data fetching
- Implement on-demand revalidation
- Create admin CMS UI with all editors
- Add SEO meta tags (title, description, OG, Twitter)
- Handle image uploads
- Implement bilingual content
- Generate tests

**Deliverables:**
- ✅ Public landing pages with SSR
- ✅ Admin CMS for editing content
- ✅ Service cards and showcase projects
- ✅ SEO optimization
- ✅ Instant revalidation working
- ✅ All tests passing

---

### **DAY 4: Blog System & Contact Form** (10-12 hours)

**Your Instruction to Kiro:**
```
Read /tasks/08-blog-system.md, /tasks/09-contact-form-email.md, 
/user-stories/06-blog-system.md, and /user-stories/07-contact-communication.md.

Then create:
1. Blog System
   - Backend: posts, categories, tags CRUD
   - Bilingual content (slug_en, slug_ar, title_en, title_ar, content_en, content_ar)
   - Frontend: blog list page, blog detail page, category pages (all with SSR)
   - Admin UI: rich text editor, SEO fields, image upload
   - Maximum SEO optimization (JSON-LD, structured data, slugs)

2. Contact Form
   - Backend: submission endpoint, email sending via SMTP, queue with retry
   - Rate limiting: 5 emails per minute, NO CAPTCHA
   - Multiple recipient configuration
   - Store submissions in database
   - Frontend: contact form page
   - Admin UI: view submissions, email settings

Follow email configuration from docs/meeting_notes.md.
```

**What Kiro Will Do:**
- Read all blog and contact requirements
- Generate complete blog backend (posts, categories, tags)
- Generate blog frontend with SSR
- Implement rich text editor in admin
- Add exceptional SEO (JSON-LD, structured data)
- Generate contact form backend with email sending
- Implement SMTP queue with retry logic
- Add rate limiting (5/min)
- Create admin UI for submissions
- Generate tests

**Deliverables:**
- ✅ Blog system complete (backend + frontend + admin)
- ✅ Exceptional blog SEO
- ✅ Contact form with email sending
- ✅ Rate limiting working
- ✅ Admin can view submissions
- ✅ All tests passing

---

### **DAY 5: Advanced Features & Business Logic** (8-10 hours)

**Your Instruction to Kiro:**
```
Read ALL files in /docs/ and /tasks/ to understand all business rules.

Then implement:
1. 30-day auto-inactive team members
   - Scheduled command to check last_login_at
   - Send warning emails at 25 days
   - Mark as inactive at 30 days
   - Schedule in Laravel Kernel

2. Project reassignment logic
   - When deleting PM, must reassign all their projects
   - UI shows reassignment modal
   - Validate new manager exists

3. Default client protection
   - "Tarqumi" client cannot be deleted
   - Name and email cannot be changed
   - Enforce in policy and validation

4. Permission enforcement
   - All endpoints check permissions
   - Frontend hides UI based on permissions
   - Only super_admin can delete admins
   - Only CTO can edit landing page

5. Audit logging
   - Track created_by and updated_by on all models
   - Log all important actions

6. Profile picture management
   - Upload, update, delete
   - Optimize images
   - Store in storage/app/public

Ensure ALL business rules from docs/meeting_notes.md are implemented.
```

**What Kiro Will Do:**
- Read all business rules
- Create scheduled command for 30-day inactive
- Implement email notifications
- Add project reassignment logic
- Enforce default client protection
- Add permission checks everywhere
- Implement audit logging
- Handle profile pictures
- Generate tests for all business logic

**Deliverables:**
- ✅ 30-day auto-inactive working
- ✅ Project reassignment working
- ✅ Default client protected
- ✅ All permissions enforced
- ✅ Audit logging implemented
- ✅ All tests passing

---

### **DAY 6: SEO, Performance & Testing** (10-12 hours)

**Your Instruction to Kiro:**
```
Read /tasks/10-seo-testing-deployment.md, /user-stories/09-seo-technical-infrastructure.md, 
and /docs/benchmarks_and_tests.md.

Then implement:
1. SEO Optimization
   - Dynamic sitemap.xml (all pages + blog posts)
   - robots.txt
   - Meta tags on all pages (title, description, keywords, OG, Twitter)
   - JSON-LD structured data
   - Hreflang tags for bilingual
   - Canonical URLs

2. Performance Optimization
   - Database indexing (check all tables)
   - Query optimization (prevent N+1)
   - API response caching
   - Image optimization (WebP, lazy loading)
   - Code splitting
   - Bundle optimization

3. Comprehensive Testing
   - PHPUnit tests for all backend features
   - Component tests for frontend
   - Security tests (SQL injection, XSS, CSRF)
   - Test all business rules
   - Test all permissions
   - Test bilingual and RTL

Target: Lighthouse score 80+ for Performance, SEO, Accessibility, Best Practices.
Follow ALL quality criteria from docs/benchmarks_and_tests.md.
```

**What Kiro Will Do:**
- Read all SEO and performance requirements
- Generate dynamic sitemap controller
- Add meta tags to all pages
- Implement JSON-LD structured data
- Add database indexes
- Optimize queries (eager loading)
- Implement caching
- Optimize images
- Generate comprehensive test suite
- Run security tests
- Ensure Lighthouse score 80+

**Deliverables:**
- ✅ SEO fully optimized
- ✅ Performance optimized
- ✅ Comprehensive test suite
- ✅ All tests passing
- ✅ Lighthouse score 80+
- ✅ Security verified

---

### **DAY 7: Polish, Deploy & Documentation** (8-10 hours)

**Your Instruction to Kiro:**
```
Read ALL project files to understand the complete system.

Then:
1. UI/UX Polish
   - Add loading states to all async operations
   - Add error handling with user-friendly messages
   - Add success feedback for all actions
   - Improve animations and transitions
   - Ensure responsive design on all screen sizes
   - Test cross-browser compatibility

2. Production Setup
   - Create production .env.example
   - Create database migration scripts
   - Create production seeders
   - Configure SMTP for production
   - Setup error logging
   - Setup monitoring

3. Documentation
   - Create comprehensive README.md
   - Document all API endpoints
   - Create user guide
   - Create admin guide
   - Document deployment process
   - Document environment variables

4. Final Testing
   - Test all features end-to-end
   - Test on mobile devices
   - Test in different browsers
   - Fix any remaining bugs

Ensure production-ready quality.
```

**What Kiro Will Do:**
- Polish all UI/UX
- Add loading and error states
- Create production configuration
- Generate comprehensive documentation
- Create deployment guides
- Test everything
- Fix bugs
- Ensure production-ready

**Deliverables:**
- ✅ UI/UX polished
- ✅ Production configuration ready
- ✅ Complete documentation
- ✅ All bugs fixed
- ✅ Production-ready CRM
- ✅ Ready to deploy

---

## 🎯 Kiro's Workflow (Automatic)

For each day, Kiro will:

1. **Read Phase:**
   - Read all referenced documentation files
   - Understand business rules and requirements
   - Check coding standards
   - Review user stories

2. **Plan Phase:**
   - Determine file structure
   - Plan database schema
   - Plan API endpoints
   - Plan frontend pages

3. **Generate Phase:**
   - Create backend files (migrations, models, controllers, services, requests, resources)
   - Create frontend files (pages, components, services, types)
   - Create tests
   - Follow SOLID, OOP, DRY principles
   - Ensure security (SQL injection prevention, validation)
   - Implement bilingual support
   - Add RTL support

4. **Validate Phase:**
   - Check against coding rules
   - Verify business logic
   - Ensure test coverage
   - Check security

5. **Report Phase:**
   - List all files created
   - Explain what was implemented
   - Highlight any decisions made
   - Suggest next steps

---

## 💡 Tips for Working with Kiro

### Do:
- ✅ Give high-level instructions referencing project files
- ✅ Let Kiro read documentation automatically
- ✅ Review generated code before moving on
- ✅ Test features as they're built
- ✅ Ask Kiro to explain decisions
- ✅ Request changes if needed

### Don't:
- ❌ Provide detailed specifications (they're in the files)
- ❌ Repeat business rules (Kiro reads them)
- ❌ Write code manually (let Kiro generate)
- ❌ Skip testing (Kiro generates tests)
- ❌ Forget to reference documentation files

---

## 📊 Progress Tracking

| Day | Status | Files Created | Tests | Completion |
|-----|--------|---------------|-------|------------|
| 1 | ⬜ | 0 | 0 | 0% |
| 2 | ⬜ | 0 | 0 | 0% |
| 3 | ⬜ | 0 | 0 | 0% |
| 4 | ⬜ | 0 | 0 | 0% |
| 5 | ⬜ | 0 | 0 | 0% |
| 6 | ⬜ | 0 | 0 | 0% |
| 7 | ⬜ | 0 | 0 | 0% |

---

## 🚀 Ready to Start?

**Day 1 Instruction:**
```
Read all files in /docs/, /tasks/01-project-setup-infrastructure.md, 
/tasks/02-database-models-migrations.md, /tasks/03-authentication-authorization.md, 
and /user-stories/01-authentication-security.md.

Then create complete foundation: Laravel backend, Next.js frontend, database schema, 
authentication, and RBAC. Follow ALL requirements from docs/coding_rules.md and 
docs/meeting_notes.md.
```

**Let's build! 🎉**
