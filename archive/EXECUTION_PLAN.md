# Tarqumi CRM - 7-Day AI-Powered Execution Plan

> **Team:** You + AI Coding Assistant (Cursor/Claude/ChatGPT/Copilot)
> **Timeline:** 7 Days (AI-Accelerated Development)
> **Approach:** Use AI to generate 80% of code, focus on validation and business logic
> **Original Timeline:** 8-10 weeks → Compressed to 7 days with AI

---

## 🤖 AI Prompting Strategy

### Core Principles:
1. **Be Extremely Specific:** Include exact file paths, function names, field names, and requirements
2. **Provide Full Context:** Reference business rules, related files, and dependencies
3. **Request Complete Solutions:** Ask for migrations, models, controllers, tests, and frontend in one prompt
4. **Specify Tech Stack:** Always mention Laravel 11, Next.js 14, TypeScript, Tailwind CSS, MySQL
5. **Include Security:** Emphasize SQL injection prevention, input validation, CSRF protection
6. **Bilingual First:** Always request Arabic + English with RTL support
7. **Request Tests:** Always ask for PHPUnit tests (backend) and component tests (frontend)

### AI Tools to Use:
- **Cursor AI:** Primary tool for code generation (best for full-stack)
- **Claude/ChatGPT:** Complex business logic and architecture decisions
- **GitHub Copilot:** Real-time code completion
- **v0.dev:** UI component inspiration
- **Bolt.new:** Quick prototyping

---

## 📅 7-Day Execution Plan

### **DAY 1: Foundation & Database (8-10 hours)**
**Goal:** Complete project setup, database schema, authentication, and RBAC

**Morning (4 hours): Project Setup**
**Developer A Tasks:**
1. Create GitHub repository
2. Setup Laravel project
   ```bash
   composer create-project laravel/laravel tarqumi-backend
   cd tarqumi-backend
   composer require laravel/sanctum
   php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
   ```
3. Configure MySQL database
4. Setup `.env` file
5. Create initial migration structure

**Developer B Tasks:**
1. Clone repository
2. Setup Next.js project
   ```bash
   npx create-next-app@latest tarqumi-frontend --typescript --tailwind --app
   cd tarqumi-frontend
   npm install next-intl
   ```
3. Configure i18n (Arabic/English)
4. Setup folder structure
5. Create basic layout components

**Deliverable:** Both projects running locally, connected via API

---

#### Day 3-5: Database & Authentication Foundation

**Developer A Tasks:**
1. **Create Users Migration & Model** (4 hours)
   ```bash
   php artisan make:migration create_users_table
   php artisan make:model User
   php artisan make:factory UserFactory
   php artisan make:seeder UserSeeder
   ```
   - Add all user fields (name, email, password, role, founder_role, etc.)
   - Add soft deletes
   - Create first admin seeder
   
2. **Setup Sanctum Authentication** (4 hours)
   ```bash
   php artisan make:controller Api/V1/AuthController
   php artisan make:request LoginRequest
   ```
   - Login endpoint
   - Logout endpoint
   - Token generation
   - Test with Postman

3. **Create Clients Migration & Model** (2 hours)
   ```bash
   php artisan make:migration create_clients_table
   php artisan make:model Client
   ```
   - All client fields
   - Seed default "Tarqumi" client

**Developer B Tasks:**
1. **Create Login Page** (4 hours)
   - `/app/[locale]/login/page.tsx`
   - Form with email + password
   - Bilingual (AR/EN)
   - RTL support for Arabic
   
2. **Setup API Service Layer** (3 hours)
   ```typescript
   // lib/api.ts
   export const api = {
     login: async (email, password) => {...},
     logout: async () => {...},
   }
   ```
   - Axios/fetch configuration
   - Token storage (localStorage)
   - Error handling

3. **Create Protected Route Middleware** (3 hours)
   - Check authentication
   - Redirect to login if not authenticated
   - Role-based access

**Deliverable:** Working login system, both can authenticate

---

### **WEEK 2: Core CRM Features - Part 1**

#### Developer A: Backend API Development

**Day 1-2: Client Management API** (12 hours)
```bash
php artisan make:controller Api/V1/ClientController --resource
php artisan make:request StoreClientRequest
php artisan make:request UpdateClientRequest
php artisan make:resource ClientResource
```

Tasks:
1. Create all CRUD endpoints
2. Add validation rules
3. Implement search/filter/pagination
4. Protect default "Tarqumi" client
5. Test all endpoints with Postman
6. Document API in Postman collection

**Day 3-5: Project Management API** (16 hours)
```bash
php artisan make:migration create_projects_table
php artisan make:model Project
php artisan make:controller Api/V1/ProjectController --resource
```

Tasks:
1. Create projects table with all fields
2. Setup relationships (belongsTo, belongsToMany)
3. Create pivot table for multiple clients
4. Implement all CRUD endpoints
5. Add 6 SDLC status phases (enum)
6. Priority 1-10 validation
7. Test all endpoints

---

#### Developer B: Frontend UI Development

**Day 1-2: Client Management UI** (12 hours)

Create pages:
- `/app/[locale]/dashboard/clients/page.tsx` (List)
- `/app/[locale]/dashboard/clients/new/page.tsx` (Create)
- `/app/[locale]/dashboard/clients/[id]/page.tsx` (View)
- `/app/[locale]/dashboard/clients/[id]/edit/page.tsx` (Edit)

Tasks:
1. Client list with table/cards
2. Search, filter, pagination UI
3. Create client form (all fields)
4. Edit client form
5. Delete confirmation modal
6. Connect to API endpoints
7. Loading states, error handling
8. Bilingual forms

**Day 3-5: Project Management UI** (16 hours)

Create pages:
- `/app/[locale]/dashboard/projects/page.tsx` (List)
- `/app/[locale]/dashboard/projects/new/page.tsx` (Create)
- `/app/[locale]/dashboard/projects/[id]/page.tsx` (View)

Tasks:
1. Project list with filters
2. Create project form
   - Multiple client selection
   - PM dropdown
   - Priority slider (1-10)
   - Status dropdown (6 SDLC phases)
   - Date pickers
3. Project detail view
4. Edit functionality
5. Connect to API
6. Bilingual support

**Deliverable:** Working Client & Project management (full CRUD)

---

### **WEEK 3: Core CRM Features - Part 2**

#### Developer A: Backend API Development

**Day 1-3: Team Management API** (16 hours)
```bash
php artisan make:controller Api/V1/TeamController --resource
php artisan make:request StoreTeamMemberRequest
php artisan make:request UpdateTeamMemberRequest
```

Tasks:
1. Team CRUD endpoints
2. Role validation (super_admin, admin, founder, hr, employee)
3. Founder sub-role validation (ceo, cto, cfo)
4. Profile picture upload
5. Project reassignment on deletion
6. Bulk operations endpoint
7. Authorization checks (only super admin can edit super admin)

**Day 4-5: Auto-Inactive Job** (8 hours)
```bash
php artisan make:command AutoInactiveTeamMembers
php artisan make:notification InactivityWarningNotification
```

Tasks:
1. Create scheduled command
2. Check last_login_at (30 days)
3. Send warning emails (25 days)
4. Mark as inactive
5. Schedule in Kernel.php
6. Test manually

---

#### Developer B: Frontend UI Development

**Day 1-3: Team Management UI** (16 hours)

Create pages:
- `/app/[locale]/dashboard/team/page.tsx` (List)
- `/app/[locale]/dashboard/team/new/page.tsx` (Create)
- `/app/[locale]/dashboard/team/[id]/page.tsx` (View)
- `/app/[locale]/dashboard/team/[id]/edit/page.tsx` (Edit)

Tasks:
1. Team member list
2. Create form with role selection
3. Founder sub-role conditional field
4. Profile picture upload
5. Edit form
6. Delete with reassignment modal
7. Bulk operations UI
8. Connect to API

**Day 4-5: Dashboard Layout & Navigation** (8 hours)

Tasks:
1. Create sidebar navigation
2. Header with user menu
3. Breadcrumbs
4. Role-based menu items
5. Language switcher
6. Responsive mobile menu
7. Dark/light theme (optional)

**Deliverable:** Complete CRM panel with Team, Client, Project management

---

### **WEEK 4: Landing Page Foundation**

#### Developer A: Backend CMS API

**Day 1-2: Landing Page Content API** (12 hours)
```bash
php artisan make:migration create_landing_pages_table
php artisan make:model LandingPage
php artisan make:controller Api/V1/LandingPageController
```

Tasks:
1. Create landing_pages table (SEO fields, content)
2. Create pages: home, about, services, projects, contact
3. API to get/update page content
4. SEO fields (title, description, keywords, og_image)
5. Bilingual content storage
6. Revalidation trigger endpoint

**Day 3-5: Service Cards & Showcase Projects API** (12 hours)
```bash
php artisan make:migration create_service_cards_table
php artisan make:migration create_showcase_projects_table
php artisan make:controller Api/V1/ServiceCardController --resource
php artisan make:controller Api/V1/ShowcaseProjectController --resource
```

Tasks:
1. Service cards CRUD
2. Showcase projects CRUD
3. Image upload handling
4. Ordering/sorting
5. Active/inactive status
6. Bilingual content

---

#### Developer B: Public Landing Pages

**Day 1-2: Home Page (SSR)** (12 hours)

Create:
- `/app/[locale]/page.tsx` (Home)
- `/components/landing/HeroSection.tsx`
- `/components/landing/ServicesPreview.tsx`
- `/components/landing/ProjectsPreview.tsx`

Tasks:
1. Implement SSR data fetching
2. Hero section with dynamic content
3. Services preview (6 cards)
4. Projects preview (3 featured)
5. CTA sections
6. Animations
7. Bilingual + RTL

**Day 3-5: About, Services, Projects Pages** (12 hours)

Create:
- `/app/[locale]/about/page.tsx`
- `/app/[locale]/services/page.tsx`
- `/app/[locale]/projects/page.tsx`

Tasks:
1. About page with company info
2. Services page (all service cards)
3. Projects page (all showcase projects)
4. SSR for all pages
5. Meta tags
6. Bilingual content

**Deliverable:** Public landing page with dynamic content

---

### **WEEK 5: Blog System**

#### Developer A: Blog Backend

**Day 1-3: Blog API** (16 hours)
```bash
php artisan make:migration create_blog_posts_table
php artisan make:migration create_blog_categories_table
php artisan make:migration create_blog_tags_table
php artisan make:model BlogPost
php artisan make:controller Api/V1/BlogController --resource
```

Tasks:
1. Blog posts table (title, slug, content, SEO fields)
2. Categories and tags tables
3. Many-to-many relationships
4. CRUD endpoints
5. Bilingual content (slug_en, slug_ar, title_en, title_ar)
6. Status (draft, published)
7. Featured image upload
8. Rich text content storage

**Day 4-5: Blog Categories & Tags API** (8 hours)

Tasks:
1. Categories CRUD
2. Tags CRUD
3. Filtering by category/tag
4. Post count per category
5. Bilingual support

---

#### Developer B: Blog Frontend

**Day 1-3: Blog List & Detail Pages** (16 hours)

Create:
- `/app/[locale]/blog/page.tsx` (List with SSR)
- `/app/[locale]/blog/[slug]/page.tsx` (Detail with SSR)
- `/app/[locale]/blog/category/[slug]/page.tsx`

Tasks:
1. Blog list with pagination
2. Search and filters
3. Blog post detail page
4. Rich text rendering
5. Related posts
6. Social sharing buttons
7. **Maximum SEO optimization**
8. JSON-LD structured data

**Day 4-5: Blog Admin UI** (8 hours)

Create:
- `/app/[locale]/dashboard/blog/page.tsx`
- `/app/[locale]/dashboard/blog/new/page.tsx`
- `/app/[locale]/dashboard/blog/[id]/edit/page.tsx`

Tasks:
1. Blog post list (admin)
2. Rich text editor (TinyMCE or similar)
3. Create/edit forms
4. SEO fields editor
5. Image upload
6. Category/tag selection
7. Preview functionality

**Deliverable:** Complete blog system with SEO

---

### **WEEK 6: Contact Form & Email**

#### Developer A: Contact Form Backend

**Day 1-2: Contact Form API** (12 hours)
```bash
php artisan make:migration create_contact_submissions_table
php artisan make:controller Api/V1/ContactController
php artisan make:mail ContactFormSubmission
php artisan make:job SendContactEmail
```

Tasks:
1. Contact submissions table
2. Submit endpoint with validation
3. Rate limiting (5 per minute)
4. Store in database
5. Send email via SMTP
6. Queue email jobs
7. Multiple recipient configuration
8. Admin view submissions endpoint

**Day 3-5: Email Configuration & Settings** (12 hours)

Tasks:
1. SMTP configuration
2. Email templates
3. Recipient management API
4. Email queue monitoring
5. Retry logic for failed emails
6. Admin settings API

---

#### Developer B: Contact Form Frontend

**Day 1-2: Public Contact Page** (12 hours)

Create:
- `/app/[locale]/contact/page.tsx`
- `/components/landing/ContactForm.tsx`

Tasks:
1. Contact form UI
2. Fields: name, email, phone, message
3. Client-side validation
4. Rate limit handling
5. Success/error messages
6. Bilingual form
7. Accessibility

**Day 3-5: Admin Contact Management** (12 hours)

Create:
- `/app/[locale]/dashboard/contacts/page.tsx`
- `/app/[locale]/dashboard/contacts/[id]/page.tsx`
- `/app/[locale]/dashboard/settings/email/page.tsx`

Tasks:
1. View all submissions
2. Search and filter
3. Mark as read/replied
4. Email recipient configuration UI
5. SMTP settings UI
6. Test email functionality

**Deliverable:** Working contact form with email delivery

---

### **WEEK 7: SEO & Performance**

#### Developer A: SEO Backend

**Day 1-2: Sitemap & Robots** (8 hours)
```bash
php artisan make:controller SitemapController
```

Tasks:
1. Dynamic sitemap.xml generation
2. Include all pages and blog posts
3. Bilingual URLs
4. robots.txt route
5. Update on content changes

**Day 3-5: Performance Optimization** (12 hours)

Tasks:
1. Database indexing
2. Query optimization (N+1 prevention)
3. API response caching
4. Image optimization
5. Eager loading relationships
6. API rate limiting

---

#### Developer B: SEO Frontend

**Day 1-2: Meta Tags & Structured Data** (8 hours)

Tasks:
1. Dynamic meta tags for all pages
2. Open Graph tags
3. Twitter Card tags
4. JSON-LD structured data
5. Hreflang tags
6. Canonical URLs

**Day 3-5: Performance Optimization** (12 hours)

Tasks:
1. Image optimization (WebP, lazy loading)
2. Code splitting
3. Bundle size optimization
4. Lighthouse audit fixes
5. Core Web Vitals optimization
6. SSR caching strategy

**Deliverable:** SEO-optimized website with great performance

---

### **WEEK 8: Testing & Bug Fixes**

#### Developer A: Backend Testing

**Day 1-3: Write Tests** (16 hours)
```bash
php artisan make:test AuthenticationTest
php artisan make:test ClientCrudTest
php artisan make:test ProjectCrudTest
php artisan make:test TeamCrudTest
php artisan make:test BlogCrudTest
```

Tasks:
1. Authentication tests
2. Client CRUD tests
3. Project CRUD tests
4. Team CRUD tests
5. Blog CRUD tests
6. Contact form tests
7. Authorization tests
8. Validation tests

**Day 4-5: Security Testing** (8 hours)

Tasks:
1. SQL injection testing
2. XSS testing
3. CSRF verification
4. Rate limiting verification
5. Input validation testing
6. Fix all security issues

---

#### Developer B: Frontend Testing

**Day 1-3: Component Testing** (16 hours)

Tasks:
1. Test all forms
2. Test navigation
3. Test authentication flow
4. Test CRUD operations
5. Test bilingual switching
6. Test RTL layout
7. Cross-browser testing
8. Mobile responsiveness

**Day 4-5: UI/UX Polish** (8 hours)

Tasks:
1. Fix UI bugs
2. Improve animations
3. Loading states
4. Error handling
5. Success messages
6. Accessibility improvements
7. Final design touches

**Deliverable:** Tested, polished application

---

### **WEEK 9-10: Final Integration & Deployment**

#### Both Developers Working Together

**Week 9: Integration & Final Testing**

**Developer A:**
1. Production environment setup
2. Database migration scripts
3. Seeder for production data
4. SMTP configuration
5. API documentation
6. Deployment scripts

**Developer B:**
1. Build optimization
2. Environment configuration
3. Final SEO audit
4. Lighthouse optimization
5. Cross-browser final testing
6. Mobile testing

**Week 10: Deployment & Launch**

**Together:**
1. Deploy backend to server
2. Deploy frontend to Vercel/server
3. Configure domain and SSL
4. Test production environment
5. Monitor for issues
6. Create user documentation
7. Handover to client

---

## 🔄 Daily Workflow

### Morning (Both)
1. **9:00 AM:** Stand-up meeting (15 min)
   - What did you do yesterday?
   - What will you do today?
   - Any blockers?

2. **9:15 AM:** Start coding
   - Developer A: Backend tasks
   - Developer B: Frontend tasks

### Afternoon
3. **1:00 PM:** Sync meeting (15 min)
   - API endpoints ready?
   - Frontend needs any changes?
   - Test integration

4. **1:15 PM:** Continue coding

### Evening
5. **5:00 PM:** End of day sync (15 min)
   - Demo what you built
   - Commit and push code
   - Plan tomorrow

---

## 📝 Communication Protocol

### Use GitHub Issues
- Create issue for each task
- Assign to Developer A or B
- Use labels: `backend`, `frontend`, `bug`, `feature`
- Link commits to issues

### Use GitHub Projects
- Kanban board: To Do, In Progress, Review, Done
- Move cards as you work

### API Contract
- Developer A documents API in Postman
- Share collection with Developer B
- Update when endpoints change

---

## 🛠️ Tools You Both Need

### Developer A (Backend)
- PHP 8.2+
- Composer
- MySQL
- Postman (API testing)
- Laravel Debugbar
- VS Code with PHP extensions

### Developer B (Frontend)
- Node.js 18+
- npm/yarn
- VS Code with TypeScript extensions
- React DevTools
- Lighthouse (Chrome)

### Both
- Git & GitHub
- Slack/Discord for communication
- Figma (if you have designs)
- Notion/Trello for task management

---

## ⚠️ Critical Rules

1. **Never work on the same file simultaneously**
2. **Pull before you start working each day**
3. **Commit frequently with clear messages**
4. **Test your code before pushing**
5. **Document your API endpoints**
6. **Ask questions immediately if blocked**
7. **Review each other's code**

---

## 🎯 Success Metrics

### End of Each Week
- [ ] All planned tasks completed
- [ ] Code pushed to GitHub
- [ ] No breaking bugs
- [ ] Integration tested
- [ ] Documentation updated

### End of Project
- [ ] All features working
- [ ] Tests passing
- [ ] Security audit passed
- [ ] Performance benchmarks met
- [ ] Deployed to production
- [ ] Client approved

---

**Ready to start? Begin with Week 1, Day 1! 🚀**

---

## 📖 Detailed Task Breakdown by Module

### Module 1: Project Setup & Infrastructure (Week 1, Days 1-2)

**Developer A - Backend Setup (4 hours):**
1. Install Laravel 11 with PHP 8.2+
2. Install and configure Sanctum
3. Create MySQL database with UTF8MB4
4. Configure `.env` with all variables
5. Setup CORS for frontend URL
6. Create custom folder structure (Services, Enums, Policies, Observers, Traits)
7. Initialize Git repository
8. Run initial migrations
9. Create first admin seeder

**Developer B - Frontend Setup (4 hours):**
1. Create Next.js 14 project with TypeScript
2. Install dependencies: next-intl, axios, react-query, framer-motion, react-hook-form, zod
3. Configure i18n middleware for /ar and /en routes
4. Setup Tailwind with CSS variables
5. Create folder structure (components, lib, hooks, services, types, messages)
6. Configure API client with interceptors
7. Setup React Query provider
8. Create translation files (en.json, ar.json)
9. Test both locale routes

**Deliverable:** Both projects running, connected, and ready for development

---

### Module 2: Database Models & Migrations (Week 1, Days 3-5)

**Developer A - Database Schema (12 hours):**

**Day 3: Users Table (4 hours)**
- Create comprehensive users migration with all fields
- Add role enum (super_admin, admin, founder, hr, employee)
- Add founder_role enum (ceo, cto, cfo)
- Add status tracking (is_active, last_login_at, last_active_at, inactive_days)
- Create indexes on email, role, is_active, last_active_at
- Implement soft deletes
- Create User model with relationships, scopes, accessors
- Create UserRole and FounderRole enums
- Create User factory with states
- Update AdminSeeder to create super_admin, CTO, CEO
- Test in Tinker

**Day 4: Clients Table (4 hours)**
- Create clients migration with all fields
- Add is_default flag for "Tarqumi" client
- Create indexes on email, name, company_name, is_active
- Implement soft deletes
- Create Client model with relationships and scopes
- Create Client factory
- Create DefaultClientSeeder for "Tarqumi"
- Create ClientPolicy (default client cannot be deleted)
- Test in Tinker

**Day 5: Projects Table (4 hours)**
- Create projects migration with all fields
- Add auto-generated project code (PROJ-2024-0001)
- Add 6 SDLC status phases enum (planning, analysis, design, implementation, testing, deployment)
- Add priority (1-10 scale)
- Add budget with currency field
- Create client_project pivot table for multiple clients
- Create indexes on code, name, manager_id, status, is_active, dates
- Implement soft deletes
- Create Project model with relationships
- Create ProjectStatus enum
- Create Project factory
- Test in Tinker

**Developer B - API Service Layer (12 hours)**

**Day 3: API Client Setup (4 hours)**
- Create axios API client with base configuration
- Add request interceptor for auth token
- Add response interceptor for error handling
- Create API response types (ApiResponse, PaginatedResponse, ApiError)
- Create error handler utility
- Test API connection

**Day 4: Authentication Service (4 hours)**
- Create AuthService with login, logout, getCurrentUser, refreshToken methods
- Create BaseService class for CRUD operations
- Create custom hooks (useApiQuery, useApiMutation)
- Setup React Query configuration
- Create QueryProvider component
- Add to root layout

**Day 5: UI Components (4 hours)**
- Create Loading component
- Create ErrorMessage component
- Create Button component
- Create Input component
- Create Form components
- Create Modal component
- Test components in both languages

**Deliverable:** Complete database schema and API service layer ready

---

### Module 3: Authentication & Authorization (Week 1, Days 3-5 + Week 2, Day 1)

**Developer A - Backend Auth (8 hours):**

**Sanctum Setup (4 hours)**
- Configure Sanctum for SPA authentication
- Create AuthController with login, logout, user, refresh methods
- Create LoginRequest with validation
- Add auth routes to api.php
- Create UpdateLastActive middleware
- Test with Postman

**RBAC Implementation (4 hours)**
- Create RoleMiddleware for role-based access
- Create FounderRoleMiddleware for founder sub-roles
- Create HasPermissions trait with all permission methods
- Create AuthHelper utility class
- Create permissions configuration file
- Create PermissionsController for frontend
- Add permissions route
- Test all permission scenarios

**Developer B - Frontend Auth (8 hours)**

**Login Page (4 hours)**
- Create login page at /[locale]/login
- Create login form with validation
- Implement login logic with AuthService
- Handle errors and loading states
- Store token and user in localStorage
- Redirect to dashboard on success
- Test in both languages

**Protected Routes (4 hours)**
- Create auth middleware for client-side
- Create useAuth hook
- Create ProtectedRoute component
- Implement role-based UI rendering
- Create permissions context
- Fetch user permissions on login
- Test access control

**Deliverable:** Complete authentication system with role-based access control

---

### Module 4: Team Management CRUD (Week 2-3)

**Developer A - Team API (16 hours):**

**Create Team Member (4 hours)**
- Create TeamController with resource methods
- Create StoreTeamMemberRequest with validation
- Create TeamService for business logic
- Implement profile picture upload
- Create UserResource for API responses
- Add route with role middleware
- Create tests

**List Team Members (4 hours)**
- Create IndexTeamRequest for query validation
- Implement pagination (10, 20, 50, 100 per page)
- Implement search (name, email, phone, department)
- Implement filters (role, status, department)
- Implement sorting (name, email, role, created_at, last_active_at)
- Create departments endpoint
- Create tests

**Update Team Member (4 hours)**
- Create UpdateTeamMemberRequest
- Implement authorization (only super_admin can edit super_admin)
- Prevent privilege escalation
- Handle profile picture replacement
- Create tests

**Delete Team Member (4 hours)**
- Create DeleteTeamMemberRequest
- Check for managed projects
- Implement project reassignment
- Protect last super_admin
- Implement soft delete
- Create tests

**Developer B - Team UI (16 hours):**

**Team List Page (4 hours)**
- Create /[locale]/dashboard/team page
- Implement table with all columns
- Add search input with debounce
- Add filter dropdowns (role, status, department)
- Add sorting controls
- Add pagination controls
- Connect to API
- Test in both languages

**Create Team Member Page (4 hours)**
- Create /[locale]/dashboard/team/new page
- Create form with all fields
- Add role selection with conditional founder_role
- Add profile picture upload with preview
- Implement validation
- Connect to API
- Show success/error messages
- Test in both languages

**Edit Team Member Page (4 hours)**
- Create /[locale]/dashboard/team/[id]/edit page
- Load existing data
- Pre-fill form
- Handle profile picture update
- Implement validation
- Connect to API
- Test in both languages

**Delete with Reassignment (4 hours)**
- Create delete confirmation modal
- Check for managed projects
- Show project reassignment form if needed
- Implement reassignment logic
- Connect to API
- Show success/error messages
- Test in both languages

**Deliverable:** Complete team management system

---

### Module 5: Client Management CRUD (Week 3)

**Developer A - Client API (12 hours):**

**Create Client (3 hours)**
- Create ClientController
- Create StoreClientRequest
- Create ClientService
- Create ClientResource
- Protect default "Tarqumi" client
- Add route
- Create tests

**List Clients (3 hours)**
- Create IndexClientRequest
- Implement pagination, search, filters, sorting
- Add export to CSV
- Create tests

**Update Client (3 hours)**
- Create UpdateClientRequest
- Protect default client from critical changes
- Track change history
- Create tests

**Delete Client (3 hours)**
- Implement soft delete
- Protect default client
- Preserve project relationships
- Implement restore
- Create tests

**Developer B - Client UI (12 hours):**

**Client List Page (3 hours)**
- Create /[locale]/dashboard/clients page
- Implement table/cards view
- Add search and filters
- Add pagination
- Connect to API

**Create Client Page (3 hours)**
- Create /[locale]/dashboard/clients/new page
- Create form with all fields
- Implement validation
- Connect to API

**Edit Client Page (3 hours)**
- Create /[locale]/dashboard/clients/[id]/edit page
- Load and pre-fill data
- Protect default client fields
- Connect to API

**Client Detail Page (3 hours)**
- Create /[locale]/dashboard/clients/[id] page
- Show all client info
- Show associated projects
- Show contact history
- Connect to API

**Deliverable:** Complete client management system

---

### Module 6: Project Management CRUD (Week 3-4)

**Developer A - Project API (16 hours):**

**Create Project (4 hours)**
- Create ProjectController
- Create StoreProjectRequest
- Create ProjectService
- Implement multiple client selection
- Auto-generate project code
- Create ProjectResource
- Add route
- Create tests

**List Projects (4 hours)**
- Create IndexProjectRequest
- Implement pagination, search, filters (status, priority, manager, client)
- Implement sorting
- Add Kanban view data
- Create tests

**Update Project (4 hours)**
- Create UpdateProjectRequest
- Handle client updates (add/remove)
- Handle manager reassignment
- Track change history
- Create tests

**Delete Project (4 hours)**
- Implement soft delete
- Check for dependencies
- Preserve relationships
- Implement restore
- Create tests

**Developer B - Project UI (16 hours):**

**Project List Page (4 hours)**
- Create /[locale]/dashboard/projects page
- Implement table view
- Add Kanban board view toggle
- Add search and filters
- Add pagination
- Connect to API

**Create Project Page (4 hours)**
- Create /[locale]/dashboard/projects/new page
- Create form with all fields
- Add multiple client selection
- Add PM dropdown
- Add priority slider (1-10)
- Add status dropdown (6 SDLC phases)
- Add date pickers
- Connect to API

**Edit Project Page (4 hours)**
- Create /[locale]/dashboard/projects/[id]/edit page
- Load and pre-fill data
- Handle client updates
- Connect to API

**Project Detail Page (4 hours)**
- Create /[locale]/dashboard/projects/[id] page
- Show all project info
- Show clients
- Show manager
- Show timeline
- Show change history
- Connect to API

**Deliverable:** Complete project management system

---

### Module 7: Landing Page CMS (Week 4-5)

**Developer A - CMS API (16 hours):**

**Landing Page Content (4 hours)**
- Create landing_pages table
- Create LandingPageController
- Implement get/update page content
- Add SEO fields (title, description, keywords, og_image)
- Bilingual content storage
- Revalidation trigger endpoint
- Create tests

**Service Cards (4 hours)**
- Create service_cards table
- Create ServiceCardController
- Implement CRUD operations
- Image upload handling
- Ordering/sorting
- Active/inactive status
- Bilingual content
- Create tests

**Showcase Projects (4 hours)**
- Create showcase_projects table
- Create ShowcaseProjectController
- Implement CRUD operations
- Image upload handling
- Live status
- Active status
- Bilingual content
- Create tests

**Revalidation System (4 hours)**
- Create revalidation endpoint
- Implement on-demand ISR
- Clear Next.js cache
- Test instant updates
- Create tests

**Developer B - Public Landing Pages (16 hours):**

**Home Page (4 hours)**
- Create /[locale]/page.tsx with SSR
- Create HeroSection component
- Create ServicesPreview component (6 cards)
- Create ProjectsPreview component (3 featured)
- Fetch data with SSR
- Add animations
- Implement bilingual + RTL
- Add meta tags

**About/Services/Projects Pages (4 hours)**
- Create /[locale]/about/page.tsx
- Create /[locale]/services/page.tsx
- Create /[locale]/projects/page.tsx
- Implement SSR for all
- Add meta tags
- Bilingual content

**Admin CMS UI (4 hours)**
- Create /[locale]/dashboard/landing-page page
- Create page selector
- Create SEO fields editor
- Create content editor
- Create service cards CRUD
- Create showcase projects CRUD
- Connect to API

**SEO Optimization (4 hours)**
- Add dynamic meta tags to all pages
- Add Open Graph tags
- Add Twitter Card tags
- Add JSON-LD structured data
- Add canonical URLs
- Add hreflang tags
- Test with SEO tools

**Deliverable:** Complete landing page with CMS

---

### Module 8: Blog System (Week 5)

**Developer A - Blog API (16 hours):**

**Blog Posts (8 hours)**
- Create blog_posts, blog_categories, blog_tags tables
- Create BlogController
- Implement CRUD operations
- Bilingual content (slug_en, slug_ar, title_en, title_ar, content_en, content_ar)
- Status (draft, published)
- Featured image upload
- Rich text content storage
- Create tests

**Categories & Tags (4 hours)**
- Create CategoryController
- Create TagController
- Implement CRUD operations
- Filtering by category/tag
- Post count per category
- Bilingual support
- Create tests

**Blog SEO (4 hours)**
- Add SEO fields to blog posts
- Generate slugs from titles
- Implement structured data
- Add sitemap integration
- Create tests

**Developer B - Blog Frontend (16 hours):**

**Blog List Page (4 hours)**
- Create /[locale]/blog/page.tsx with SSR
- Implement pagination
- Add search and filters
- Add category filter
- Connect to API
- Maximum SEO optimization

**Blog Detail Page (4 hours)**
- Create /[locale]/blog/[slug]/page.tsx with SSR
- Render rich text content
- Add related posts
- Add social sharing buttons
- Add JSON-LD structured data
- Maximum SEO optimization

**Category Page (2 hours)**
- Create /[locale]/blog/category/[slug]/page.tsx
- List posts by category
- Implement SSR

**Blog Admin UI (6 hours)**
- Create /[locale]/dashboard/blog page
- Create /[locale]/dashboard/blog/new page
- Create /[locale]/dashboard/blog/[id]/edit page
- Implement rich text editor (TinyMCE)
- Add SEO fields editor
- Add image upload
- Add category/tag selection
- Add preview functionality
- Connect to API

**Deliverable:** Complete blog system with maximum SEO

---

### Module 9: Contact Form & Email (Week 6)

**Developer A - Contact API (12 hours):**

**Contact Form (6 hours)**
- Create contact_submissions table
- Create ContactController
- Implement submit endpoint with validation
- Rate limiting (5 per minute)
- Store in database
- Send email via SMTP
- Queue email jobs
- Multiple recipient configuration
- Create tests

**Email Configuration (6 hours)**
- Configure SMTP
- Create email templates
- Create recipient management API
- Email queue monitoring
- Retry logic for failed emails
- Admin settings API
- Create tests

**Developer B - Contact Frontend (12 hours):**

**Public Contact Page (6 hours)**
- Create /[locale]/contact/page.tsx
- Create ContactForm component
- Fields: name, email, phone, message
- Client-side validation
- Rate limit handling
- Success/error messages
- Bilingual form
- Accessibility

**Admin Contact Management (6 hours)**
- Create /[locale]/dashboard/contacts page
- Create /[locale]/dashboard/contacts/[id] page
- Create /[locale]/dashboard/settings/email page
- View all submissions
- Search and filter
- Mark as read/replied
- Email recipient configuration UI
- SMTP settings UI
- Test email functionality

**Deliverable:** Working contact form with email delivery

---

### Module 10: SEO & Performance (Week 7)

**Developer A - SEO Backend (12 hours):**

**Sitemap & Robots (4 hours)**
- Create SitemapController
- Generate dynamic sitemap.xml
- Include all pages and blog posts
- Bilingual URLs
- Create robots.txt route
- Update on content changes
- Create tests

**Performance Optimization (8 hours)**
- Add database indexes
- Optimize queries (prevent N+1)
- Implement API response caching
- Optimize image handling
- Implement eager loading
- Add API rate limiting
- Create tests

**Developer B - SEO Frontend (12 hours):**

**Meta Tags & Structured Data (4 hours)**
- Add dynamic meta tags to all pages
- Add Open Graph tags
- Add Twitter Card tags
- Add JSON-LD structured data
- Add hreflang tags
- Add canonical URLs

**Performance Optimization (8 hours)**
- Implement image optimization (WebP, lazy loading)
- Add code splitting
- Optimize bundle size
- Fix Lighthouse audit issues
- Optimize Core Web Vitals
- Implement SSR caching strategy
- Test with Lighthouse

**Deliverable:** SEO-optimized website with great performance (Lighthouse 80+)

---

### Module 11: Testing & Bug Fixes (Week 8)

**Developer A - Backend Testing (16 hours):**

**Write Tests (12 hours)**
- Authentication tests
- Client CRUD tests
- Project CRUD tests
- Team CRUD tests
- Blog CRUD tests
- Contact form tests
- Authorization tests
- Validation tests

**Security Testing (4 hours)**
- SQL injection testing
- XSS testing
- CSRF verification
- Rate limiting verification
- Input validation testing
- Fix all security issues

**Developer B - Frontend Testing (16 hours):**

**Component Testing (12 hours)**
- Test all forms
- Test navigation
- Test authentication flow
- Test CRUD operations
- Test bilingual switching
- Test RTL layout
- Cross-browser testing
- Mobile responsiveness

**UI/UX Polish (4 hours)**
- Fix UI bugs
- Improve animations
- Add loading states
- Improve error handling
- Add success messages
- Accessibility improvements
- Final design touches

**Deliverable:** Tested, polished application ready for deployment

---

### Module 12: Deployment & Launch (Week 9-10)

**Week 9: Integration & Final Testing**

**Developer A:**
- Production environment setup
- Database migration scripts
- Seeder for production data
- SMTP configuration
- API documentation
- Deployment scripts

**Developer B:**
- Build optimization
- Environment configuration
- Final SEO audit
- Lighthouse optimization
- Cross-browser final testing
- Mobile testing

**Week 10: Deployment & Launch**

**Together:**
- Deploy backend to server
- Deploy frontend to Vercel/server
- Configure domain and SSL
- Test production environment
- Monitor for issues
- Create user documentation
- Handover to client

**Deliverable:** Fully deployed and live application
