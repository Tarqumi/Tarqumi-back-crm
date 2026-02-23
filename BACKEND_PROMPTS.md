# Backend Developer - Kiro AI Prompts (7 Days)

> **Copy each day's prompt and paste into Kiro AI**
> **Kiro will read all project files and generate complete backend code**

---

## 📋 How to Use This File

1. **Each day:** Copy the entire prompt for that day
2. **Paste into Kiro AI** in a new or continuing chat
3. **Kiro will:**
   - Read all referenced project files
   - Understand requirements and business rules
   - Generate complete backend code
   - Create tests
   - Report what was created
4. **You:** Review, test, commit, move to next day

---

## 🚀 DAY 1: Backend Foundation & Database

**Copy this entire prompt:**

```
CONTEXT:
I'm the backend developer for Tarqumi CRM. This is Day 1 of 7-day development.

READ THESE FILES FIRST:
- /docs/coding_rules.md (SOLID, OOP, DRY, Security standards)
- /docs/meeting_notes.md (Business requirements and rules)
- /tasks/01-project-setup-infrastructure.md
- /tasks/02-database-models-migrations.md
- /tasks/03-authentication-authorization.md
- /user-stories/01-authentication-security.md

TECH STACK:
- Laravel 11 with PHP 8.2+
- MySQL 8.0+ with UTF8MB4
- Laravel Sanctum for API authentication
- Eloquent ORM (NO raw SQL queries)

DAY 1 TASKS - CREATE COMPLETE BACKEND:

1. PROJECT SETUP:
   - Create Laravel 11 project structure
   - Install and configure Sanctum
   - Setup CORS for http://localhost:3000
   - Create folder structure: app/Services, app/Enums, app/Policies, app/Traits, app/Observers
   - Configure .env.example with all variables
   - Setup .gitignore properly

2. DATABASE SCHEMA - Create migrations for:
   
   A. USERS TABLE:
   - Fields: id, name, email (unique), password, phone, whatsapp, department, job_title, profile_picture
   - role (enum: super_admin, admin, founder, hr, employee)
   - founder_role (enum: ceo, cto, cfo - nullable)
   - is_active (boolean), last_login_at, last_active_at, inactive_days
   - timestamps, soft deletes
   - Indexes: email, role, is_active, last_active_at, composite (role, is_active)
   
   B. CLIENTS TABLE:
   - Fields: id, name, company_name, email (unique), phone, whatsapp, address, website, industry, notes
   - is_active (boolean), is_default (boolean - for "Tarqumi" client)
   - created_by, updated_by (foreign keys to users)
   - timestamps, soft deletes
   - Indexes: email, name, company_name, is_active, is_default
   
   C. PROJECTS TABLE:
   - Fields: id, code (unique, auto-generated: PROJ-2024-0001), name, description
   - manager_id (foreign key to users, on delete restrict)
   - budget (decimal 15,2), currency (string, default SAR)
   - priority (tinyint, 1-10 scale)
   - start_date, end_date, estimated_hours
   - status (enum: planning, analysis, design, implementation, testing, deployment)
   - is_active (boolean)
   - created_by, updated_by (foreign keys to users)
   - timestamps, soft deletes
   - Indexes: code, name, manager_id, status, is_active, dates, composite (status, is_active)
   
   D. CLIENT_PROJECT PIVOT TABLE:
   - Fields: id, client_id, project_id, is_primary (boolean)
   - timestamps
   - Unique constraint: (client_id, project_id)

3. MODELS - Create with:
   - All relationships (hasMany, belongsTo, belongsToMany)
   - Scopes (active, inactive, byRole, search, etc.)
   - Accessors (is_founder, is_super_admin, can_edit_landing_page, etc.)
   - $fillable, $hidden, $casts
   - SoftDeletes trait
   - Auto-generate project code in boot() method

4. ENUMS - Create:
   - UserRole enum (super_admin, admin, founder, hr, employee) with label() and permission methods
   - FounderRole enum (ceo, cto, cfo) with label() and canEditLandingPage()
   - ProjectStatus enum (6 SDLC phases) with label(), order(), percentage()

5. FACTORIES - Create for:
   - User (with states: superAdmin, admin, founder, hr, inactive)
   - Client (with states: default, inactive)
   - Project (with states: active, inactive, overdue)

6. SEEDERS - Create:
   - AdminSeeder: 3 users (super_admin, CTO founder, CEO founder)
   - DefaultClientSeeder: "Tarqumi" client with is_default=true

7. AUTHENTICATION SYSTEM:
   - AuthController with methods: login, logout, user, refresh
   - LoginRequest with validation
   - UpdateLastActive middleware
   - Routes in routes/api.php (public: login, protected: logout, user, refresh)
   - Response format: {success, data: {user, token}, message}

8. RBAC SYSTEM:
   - RoleMiddleware (check user roles)
   - FounderRoleMiddleware (check founder sub-roles)
   - HasPermissions trait with ALL permission methods:
     * canManageTeam, canCreateTeamMember, canEditTeamMember, canDeleteTeamMember
     * canDeleteAdmin, canManageClients, canViewClients
     * canManageProjects, canViewProjects
     * canEditLandingPage, canManageBlog
     * canViewContactSubmissions, canDeleteContactSubmissions
   - PermissionsController (GET /api/v1/permissions - returns all user permissions)
   - config/permissions.php with all roles and permissions

9. POLICIES - Create:
   - ClientPolicy (protect default "Tarqumi" client from deletion)
   - Register policies in AuthServiceProvider

10. TESTS - Create PHPUnit tests for:
    - Authentication (login, logout, invalid credentials, inactive user)
    - User model (scopes, accessors, relationships)
    - Client model (default client protection)
    - Project model (code generation, relationships)
    - RBAC (all permission checks)

CRITICAL BUSINESS RULES (from meeting_notes.md):
- Default "Tarqumi" client CANNOT be deleted
- Only Super Admin can delete other Admins
- Only CTO founder can edit landing page
- Employee cannot edit own profile
- 30-day inactivity rule (implement in Day 5)
- SQL injection prevention (use Eloquent ORM ONLY)
- Input validation on EVERY field

SECURITY REQUIREMENTS:
- Use Eloquent ORM (NO raw SQL)
- Hash passwords with bcrypt
- Validate ALL inputs
- CSRF protection enabled
- Rate limiting on login
- .env in .gitignore

OUTPUT:
After generating all code, provide:
1. List of all files created
2. Commands to run (migrations, seeders)
3. How to test the authentication
4. Any important notes or decisions made

Let's start! Generate complete Day 1 backend code.
```

---

## 🚀 DAY 2: Team, Client & Project CRUD APIs

**Copy this entire prompt:**

```
CONTEXT:
Backend Developer - Day 2 of 7. Day 1 (foundation) is complete.

READ THESE FILES:
- /tasks/04-team-management-crud.md
- /tasks/05-client-management-crud.md
- /tasks/06-project-management-crud.md
- /user-stories/02-team-management.md
- /user-stories/03-client-management.md
- /user-stories/04-project-management.md
- /docs/coding_rules.md (for standards)
- /docs/meeting_notes.md (for business rules)

DAY 2 TASKS - CREATE COMPLETE CRUD APIs:

1. TEAM MANAGEMENT API:
   
   A. TeamController (app/Http/Controllers/Api/V1/TeamController.php):
   - index(IndexTeamRequest): list with pagination, search, filters, sorting
   - store(StoreTeamMemberRequest): create team member
   - show(User): get single team member
   - update(UpdateTeamMemberRequest, User): update team member
   - destroy(DeleteTeamMemberRequest, User): soft delete with project reassignment
   - departments(): get unique departments list
   - reassign(User $oldManager, User $newManager): reassign projects
   
   B. Requests:
   - StoreTeamMemberRequest: validate all fields, check permissions (canCreateTeamMember)
   - UpdateTeamMemberRequest: validate, prevent privilege escalation, only super_admin can edit super_admin
   - DeleteTeamMemberRequest: check if manages projects, prevent deleting last super_admin
   - IndexTeamRequest: validate query params (page, per_page, search, role, status, department, sort_by, sort_order)
   
   C. TeamService (app/Services/TeamService.php):
   - createTeamMember(array $data): handle profile picture upload, hash password
   - updateTeamMember(User $user, array $data): handle picture update
   - deleteTeamMember(User $user): check managed projects
   - reassignProjects(User $old, User $new): reassign all projects
   - getTeamMembers(array $filters): pagination, search, filters
   - getDepartments(): unique list
   - uploadProfilePicture(UploadedFile): optimize and store
   
   D. UserResource (app/Http/Resources/UserResource.php):
   - Transform user data for API responses
   - Include: id, name, email, phone, role, founder_role, is_active, profile_picture URL, managed_projects_count
   
   E. Routes (protected by role:super_admin,admin,hr):
   - GET /api/v1/team
   - POST /api/v1/team
   - GET /api/v1/team/{user}
   - PUT /api/v1/team/{user}
   - DELETE /api/v1/team/{user}
   - GET /api/v1/team/departments
   - POST /api/v1/team/{oldManager}/reassign/{newManager}

2. CLIENT MANAGEMENT API:
   
   A. ClientController (app/Http/Controllers/Api/V1/ClientController.php):
   - index(IndexClientRequest): list with pagination, search, filters
   - store(StoreClientRequest): create client
   - show(Client): get single client with projects
   - update(UpdateClientRequest, Client): update (protect default client)
   - destroy(Client): soft delete (protect default client, preserve projects)
   - restore(Client): restore soft deleted
   
   B. Requests:
   - StoreClientRequest: validate all fields, check permissions
   - UpdateClientRequest: validate, protect default client name/email
   - IndexClientRequest: validate query params
   
   C. ClientService (app/Services/ClientService.php):
   - createClient(array $data): create with audit fields
   - updateClient(Client $client, array $data): protect default client
   - deleteClient(Client $client): check is_default, soft delete
   - getClients(array $filters): pagination, search, filters
   
   D. ClientResource (app/Http/Resources/ClientResource.php):
   - Transform client data
   - Include: all fields, projects_count, creator, updater
   
   E. Routes (protected by role:super_admin,admin):
   - GET /api/v1/clients
   - POST /api/v1/clients
   - GET /api/v1/clients/{client}
   - PUT /api/v1/clients/{client}
   - DELETE /api/v1/clients/{client}
   - POST /api/v1/clients/{client}/restore

3. PROJECT MANAGEMENT API:
   
   A. ProjectController (app/Http/Controllers/Api/V1/ProjectController.php):
   - index(IndexProjectRequest): list with pagination, search, filters, Kanban data
   - store(StoreProjectRequest): create with multiple clients
   - show(Project): get with all relationships
   - update(UpdateProjectRequest, Project): update including clients
   - destroy(Project): soft delete
   - restore(Project): restore soft deleted
   
   B. Requests:
   - StoreProjectRequest: validate all fields, priority 1-10, dates, clients array
   - UpdateProjectRequest: validate, check manager exists
   - IndexProjectRequest: validate query params (status, priority, manager, client filters)
   
   C. ProjectService (app/Services/ProjectService.php):
   - createProject(array $data): auto-generate code, attach clients (default to Tarqumi if none)
   - updateProject(Project $project, array $data): sync clients
   - deleteProject(Project $project): soft delete
   - getProjects(array $filters): pagination, search, filters, eager load relationships
   - getKanbanData(): group by status for Kanban view
   
   D. ProjectResource (app/Http/Resources/ProjectResource.php):
   - Transform project data
   - Include: all fields, clients, manager, is_overdue, days_remaining, completion_percentage
   
   E. Routes (protected by role:super_admin,admin):
   - GET /api/v1/projects
   - POST /api/v1/projects
   - GET /api/v1/projects/{project}
   - PUT /api/v1/projects/{project}
   - DELETE /api/v1/projects/{project}
   - POST /api/v1/projects/{project}/restore
   - GET /api/v1/projects/kanban

CRITICAL BUSINESS RULES:
- Default "Tarqumi" client cannot be deleted or have name/email changed
- When deleting PM, must reassign all their projects first
- Projects can have multiple clients
- If no clients specified, attach default "Tarqumi" client
- Project code auto-generated: PROJ-{YEAR}-{NUMBER}
- Priority must be 1-10
- Only super_admin can delete other admins
- Employee cannot edit own profile

SECURITY:
- All inputs validated
- SQL injection prevention (Eloquent only)
- Authorization checks on all endpoints
- Audit logging (created_by, updated_by)

TESTS - Create for:
- Team CRUD operations
- Client CRUD operations (including default client protection)
- Project CRUD operations (including multiple clients)
- Search and filters
- Permissions and authorization
- Project reassignment

OUTPUT:
1. List all files created
2. API endpoints summary
3. How to test each CRUD operation
4. Any important notes

Generate complete Day 2 backend code.
```

---


## 🚀 DAY 3: Authentication & Team Management APIs

**Copy this entire prompt:**

```
CONTEXT:
Backend Developer - Day 3 of 7. Days 1-2 (foundation + database) complete.

READ THESE FILES:
- /tasks/03-authentication-authorization.md
- /tasks/04-team-management-crud.md
- /user-stories/01-authentication-security.md
- /user-stories/02-team-management.md
- /docs/coding_rules.md
- /docs/meeting_notes.md

DAY 3 TASKS - AUTHENTICATION & TEAM MANAGEMENT:

1. AUTHENTICATION SYSTEM (Laravel Sanctum):
   - AuthController: login, logout, user, refresh methods
   - LoginRequest with validation
   - UpdateLastActive middleware
   - Token-based authentication
   - Last login/active tracking
   - Inactive user prevention

2. ROLE-BASED ACCESS CONTROL (RBAC):
   - RoleMiddleware (check user roles)
   - FounderRoleMiddleware (check founder sub-roles)
   - HasPermissions trait with ALL permission methods
   - PermissionsController (returns user permissions)
   - config/permissions.php with all roles

3. TEAM MANAGEMENT CRUD:
   
   A. CREATE TEAM MEMBER:
   - TeamController with full CRUD
   - StoreTeamMemberRequest validation
   - TeamService for business logic
   - Profile picture upload (max 5MB)
   - Password hashing
   - Email uniqueness
   - UserResource for API responses
   
   B. LIST TEAM MEMBERS:
   - IndexTeamRequest validation
   - Pagination (20 per page, configurable)
   - Search (name, email, phone, department)
   - Filters (role, status, department)
   - Sorting (name, email, role, created_at, last_active_at)
   - Departments endpoint (unique list)
   
   C. UPDATE TEAM MEMBER:
   - UpdateTeamMemberRequest
   - Prevent privilege escalation
   - Only Super Admin can edit Super Admin
   - Profile picture replacement
   - Partial updates supported
   
   D. DELETE TEAM MEMBER:
   - DeleteTeamMemberRequest
   - Check if managing projects
   - Require project reassignment first
   - Soft delete
   - Cannot delete last Super Admin

4. POLICIES:
   - UserPolicy for team member operations
   - Authorization checks on all endpoints

5. TESTS:
   - Authentication tests (login, logout, invalid credentials, inactive user)
   - RBAC tests (all permission checks)
   - Team CRUD tests (create, list, update, delete)
   - Search and filter tests
   - Authorization tests

CRITICAL BUSINESS RULES:
- Only Super Admin can delete other Admins
- Only CTO founder can edit landing page
- Employee cannot edit own profile
- 30-day inactivity tracking (implement observer in Day 5)
- Admin cannot escalate own privileges
- Cannot delete last Super Admin
- Manager cannot be deleted if managing projects

SECURITY:
- Sanctum token authentication
- Password hashing (bcrypt)
- Input validation on ALL fields
- CSRF protection
- Rate limiting on login (5 attempts per minute)
- Last active tracking

OUTPUT:
1. List all files created
2. API endpoints summary
3. How to test authentication
4. How to test team management
5. Any important notes

Generate complete Day 3 backend code.
```


---

## 🚀 DAY 4: Client & Project Management APIs

**Copy this entire prompt:**

```
CONTEXT:
Backend Developer - Day 4 of 7. Days 1-3 complete (foundation, database, auth, team).

READ THESE FILES:
- /tasks/05-client-management-crud.md
- /tasks/06-project-management-crud.md
- /user-stories/03-client-management.md
- /user-stories/04-project-management.md
- /docs/coding_rules.md
- /docs/meeting_notes.md

DAY 4 TASKS - CLIENT & PROJECT MANAGEMENT:

1. CLIENT MANAGEMENT CRUD:
   
   A. CREATE CLIENT:
   - ClientController with full CRUD
   - StoreClientRequest validation
   - ClientService for business logic
   - ClientResource for API responses
   - Audit logging (created_by, updated_by)
   
   B. LIST CLIENTS:
   - IndexClientRequest validation
   - Pagination (20 per page)
   - Search (name, company, email, phone)
   - Filters (status, industry, has_projects)
   - Sorting (name, company, email, projects_count)
   - Export to CSV
   
   C. UPDATE CLIENT:
   - UpdateClientRequest
   - Protect default "Tarqumi" client (name/email cannot change)
   - Change history tracking
   
   D. DELETE CLIENT:
   - Soft delete
   - Default "Tarqumi" client CANNOT be deleted
   - Projects preserved (keep client reference)
   - Restore functionality
   
   E. CLIENT DETAIL:
   - Show endpoint with all relationships
   - Associated projects
   - Contact history
   - Change history

2. PROJECT MANAGEMENT CRUD:
   
   A. CREATE PROJECT:
   - ProjectController with full CRUD
   - StoreProjectRequest validation
   - ProjectService for business logic
   - Auto-generate project code (PROJ-2024-0001)
   - Multiple clients support (1-10 clients)
   - Default to "Tarqumi" if no client specified
   - ProjectResource for API responses
   
   B. LIST PROJECTS:
   - IndexProjectRequest validation
   - Pagination (25 per page)
   - Search (name, code, client, manager)
   - Filters (status, priority, manager, client, budget_range, date_range, active)
   - Sorting (name, code, status, priority, budget, start_date, end_date)
   - Kanban view data (group by status)
   - Export to CSV
   
   C. UPDATE PROJECT:
   - UpdateProjectRequest
   - Code cannot be changed
   - Client list can be modified
   - Manager can be reassigned
   - Change history tracking
   
   D. DELETE PROJECT:
   - Soft delete
   - Restore functionality
   
   E. PROJECT DETAIL:
   - Show endpoint with all relationships
   - All clients
   - Manager details
   - Timeline data
   - Activity log
   - Computed fields (days_remaining, is_overdue, completion_percentage)

3. PROJECT STATUS WORKFLOW:
   - 6 SDLC phases (Planning, Analysis, Design, Implementation, Testing, Deployment)
   - Status change endpoint
   - Confirmation for backward movement
   - Status history tracking
   - Notifications to PM

4. PROJECT MANAGER REASSIGNMENT:
   - Reassignment endpoint
   - Notifications to old and new PM
   - Workload updates

5. POLICIES:
   - ClientPolicy (protect default client)
   - ProjectPolicy (authorization checks)

6. TESTS:
   - Client CRUD tests (including default client protection)
   - Project CRUD tests (including multiple clients)
   - Search and filter tests
   - Status workflow tests
   - Authorization tests

CRITICAL BUSINESS RULES:
- Default "Tarqumi" client CANNOT be deleted
- Projects can have multiple clients (1-10)
- If no client specified, defaults to "Tarqumi"
- Project code auto-generated and immutable
- Priority must be 1-10 (not High/Medium/Low)
- 6 SDLC status phases
- Manager cannot be deleted if managing projects
- Projects preserved when client deleted


SECURITY:
- Authorization on all endpoints
- Input validation on ALL fields
- Audit logging (created_by, updated_by)
- SQL injection prevention (Eloquent ORM)

OUTPUT:
1. List all files created
2. API endpoints summary
3. How to test client management
4. How to test project management
5. Any important notes

Generate complete Day 4 backend code.
```

---

## 🚀 DAY 5: Landing Page CMS & Blog APIs

**Copy this entire prompt:**

```
CONTEXT:
Backend Developer - Day 5 of 7. Days 1-4 complete (foundation, auth, team, clients, projects).

READ THESE FILES:
- /tasks/07-landing-page-cms.md
- /tasks/08-blog-system.md
- /user-stories/05-landing-page-cms.md
- /user-stories/06-blog-system.md
- /docs/coding_rules.md
- /docs/meeting_notes.md

DAY 5 TASKS - LANDING PAGE CMS & BLOG:

1. SEO SETTINGS MANAGEMENT:
   - SEOSetting model and migration
   - SEOSettingsController
   - UpdateSEOSettingsRequest
   - Per-page SEO settings (home, about, services, projects, blog, contact)
   - Bilingual support (AR/EN)
   - Validation (title 10-60 chars, description 50-160 chars)
   - OG image upload
   - Revalidation trigger to Next.js


2. HERO SECTION MANAGEMENT:
   - HeroSection model and migration
   - HeroSectionController
   - Background image/video upload (image max 10MB, video max 50MB)
   - CTA buttons (primary + secondary)
   - Bilingual content
   - Revalidation trigger

3. SERVICES MANAGEMENT:
   - Service model and migration
   - ServiceController with full CRUD
   - Icon selection (library or custom SVG)
   - Image upload (optional)
   - Display order (drag-and-drop)
   - Status (published/draft)
   - Bilingual content
   - Home page preview settings

4. SHOWCASE PROJECTS MANAGEMENT:
   - ShowcaseProject model and migration
   - ShowcaseProjectController with full CRUD
   - Gallery management (up to 10 images)
   - Category management
   - Technology tags
   - Featured projects selection
   - Bilingual content
   - Display order

5. BLOG SYSTEM:
   
   A. BLOG POSTS:
   - BlogPost model and migration
   - BlogPostController with full CRUD
   - Rich text content (bilingual)
   - Slug auto-generation (bilingual)
   - Featured image upload
   - Status (draft/published/scheduled)
   - SEO fields (meta_title, meta_description, meta_keywords)
   - Version history
   - Scheduling system
   
   B. CATEGORIES:
   - BlogCategory model and migration
   - CategoryController with full CRUD
   - Hierarchical categories (parent-child)
   - Bilingual content
   - Display order
   
   C. TAGS:
   - BlogTag model and migration
   - TagController with full CRUD
   - Autocomplete endpoint
   - Tag suggestions
   - Bilingual content
   
   D. BLOG SEO ANALYSIS:
   - SEO score calculation (0-100)
   - Keyword analysis
   - Readability score
   - Suggestions engine
   - Real-time analysis endpoint

6. ABOUT PAGE CONTENT:
   - AboutPageContent model and migration
   - AboutPageController
   - Mission, vision, company story
   - Core values (up to 6)
   - Statistics (4 stats)
   - Bilingual content

7. FOOTER SETTINGS:
   - FooterSetting model and migration
   - FooterSettingsController
   - Social media links (JSON)
   - Quick links (JSON)
   - Contact information
   - Bilingual content
   - Copyright year auto-update

8. MEDIA LIBRARY:
   - Media model and migration
   - MediaLibraryController
   - Upload endpoint (drag-and-drop, multiple files)
   - Image optimization
   - Thumbnail generation
   - Search and filter
   - Usage tracking
   - Alt text management


9. REVALIDATION SYSTEM:
   - RevalidationService
   - Trigger Next.js on-demand revalidation
   - Revalidation status tracking
   - Manual revalidation endpoint
   - Error logging

10. TESTS:
    - SEO settings tests
    - Hero section tests
    - Services CRUD tests
    - Showcase projects CRUD tests
    - Blog posts CRUD tests
    - Categories and tags tests
    - SEO analysis tests
    - Media library tests
    - Revalidation tests

CRITICAL BUSINESS RULES:
- Only CTO founder can edit landing page
- All content bilingual (AR/EN)
- Instant revalidation when admin updates
- Max image upload: 20MB
- Blog SEO must be exceptional
- Auto-generate slugs from titles
- Version history for blog posts
- Scheduling system for blog posts

SECURITY:
- Authorization (only CTO can edit landing page)
- Input validation on ALL fields
- Image upload validation
- XSS prevention (sanitize rich text)
- SQL injection prevention

OUTPUT:
1. List all files created
2. API endpoints summary
3. How to test CMS operations
4. How to test blog system
5. Revalidation setup instructions

Generate complete Day 5 backend code.
```


---

## 🚀 DAY 6: Contact Form & Email System

**Copy this entire prompt:**

```
CONTEXT:
Backend Developer - Day 6 of 7. Days 1-5 complete (foundation, auth, team, clients, projects, CMS, blog).

READ THESE FILES:
- /tasks/09-contact-form-email.md
- /user-stories/07-contact-communication.md
- /docs/coding_rules.md
- /docs/meeting_notes.md

DAY 6 TASKS - CONTACT FORM & EMAIL SYSTEM:

1. CONTACT FORM SUBMISSION:
   - ContactSubmission model and migration
   - ContactSubmissionController
   - StoreContactSubmissionRequest validation
   - Rate limiting (5 submissions per IP per minute)
   - Spam protection (honeypot, submission time tracking)
   - IP address and user agent tracking
   - Status (new/read/replied/archived/spam)
   - Queue email sending

2. SMTP CONFIGURATION:
   - SMTPSetting model and migration
   - SMTPSettingsController
   - Provider presets (Gmail, SendGrid, AWS SES, Mailgun, Custom)
   - Password encryption
   - Test email endpoint
   - Connection status indicator

3. EMAIL QUEUE SYSTEM:
   - EmailQueue model and migration
   - EmailQueueService
   - Background job (runs every 1 minute)
   - Process up to 50 emails per batch
   - Retry logic (5 attempts: 1min, 5min, 15min, 1hr, 6hr)
   - Failure handling
   - Dead letter queue
   - Monitoring dashboard


4. EMAIL RECIPIENT MANAGEMENT:
   - EmailRecipient model and migration
   - EmailRecipientController
   - Multiple recipients support
   - Primary recipient designation
   - Subject-based routing
   - Notification preferences (immediate/daily/weekly)
   - Minimum 1 active recipient required

5. CONTACT SUBMISSION MANAGEMENT:
   - Submission list endpoint
   - Search and filters (status, date range, subject)
   - Status management (new/read/replied/archived/spam)
   - Detail view
   - Bulk actions
   - Export functionality

6. EMAIL TEMPLATE SYSTEM:
   - EmailTemplate model and migration
   - EmailTemplateController
   - Template editor (HTML with variables)
   - Bilingual templates
   - Variable insertion ({name}, {email}, {message}, etc.)
   - Default templates (contact notification, auto-reply, digests)
   - Preview functionality

7. AUTO-REPLY SYSTEM:
   - Auto-reply settings
   - Send immediately after submission
   - Bilingual auto-reply
   - Rate limiting (max 1 per email per hour)
   - Delivery tracking

8. EMAIL DELIVERY MONITORING:
   - EmailLog model and migration
   - Log all email attempts
   - Success/failure tracking
   - Error logging
   - Monitoring dashboard (sent today/week/month, success rate, failure rate)
   - Alerts (failure rate > 10%, queue size > 1000)
   - Health checks


9. SPAM DETECTION:
   - SpamPattern model and migration
   - BlockedIP model and migration
   - Spam detection (keyword matching, URL patterns, email patterns)
   - IP blocking (auto-block after 5 spam submissions)
   - Spam management UI
   - False positive handling

10. EMAIL DIGEST SYSTEM:
    - Daily digest (9 AM)
    - Weekly digest (Monday 9 AM)
    - Configurable time and timezone
    - Digest preferences per recipient
    - Unsubscribe option

11. TESTS:
    - Contact form submission tests
    - Rate limiting tests
    - Spam protection tests
    - SMTP configuration tests
    - Email queue tests
    - Recipient management tests
    - Template system tests
    - Auto-reply tests
    - Monitoring tests
    - Spam detection tests

CRITICAL BUSINESS RULES:
- Rate limit: 5 emails per minute per IP
- NO CAPTCHA
- Multiple recipients supported
- Submissions stored in database
- SMTP with queue (not direct sending)
- Retry up to 5 times
- Auto-reply rate limited (1 per email per hour)
- Auto-block after 5 spam submissions

SECURITY:
- Input validation on ALL fields
- Rate limiting enforced
- Spam protection (honeypot, time tracking, IP blocking)
- SMTP password encrypted
- XSS prevention
- SQL injection prevention


OUTPUT:
1. List all files created
2. API endpoints summary
3. How to configure SMTP
4. How to test contact form
5. How to test email queue
6. Any important notes

Generate complete Day 6 backend code.
```

---

## 🚀 DAY 7: SEO, Testing & Deployment

**Copy this entire prompt:**

```
CONTEXT:
Backend Developer - Day 7 of 7 (FINAL DAY). Days 1-6 complete. Today: SEO, testing, deployment.

READ THESE FILES:
- /tasks/10-seo-testing-deployment.md
- /user-stories/09-seo-technical-infrastructure.md
- /user-stories/10-quality-assurance-testing.md
- /docs/benchmarks_and_tests.md
- /docs/coding_rules.md
- /docs/meeting_notes.md

DAY 7 TASKS - SEO, TESTING & DEPLOYMENT:

1. SITEMAP.XML GENERATION:
   - SitemapController
   - Dynamic sitemap generation
   - Include all public pages (home, about, services, projects, blog, contact)
   - Include all published blog posts
   - Both languages (AR/EN)
   - Proper XML format
   - Priority and changefreq
   - Last modified dates

2. ROBOTS.TXT CONFIGURATION:
   - Dynamic robots.txt route
   - Allow all public pages
   - Disallow admin routes (/login, /admin, /api, /dashboard)
   - Include sitemap URL


3. SECURITY TESTING:
   
   A. SQL INJECTION TESTING:
   - Test all input fields
   - Test all API endpoints
   - Verify Eloquent ORM usage
   - Test with SQLMap
   - Document findings
   
   B. XSS TESTING:
   - Test all input fields
   - Test rich text content
   - Verify output encoding
   - Test CSP headers
   
   C. CSRF TESTING:
   - Test all state-changing requests
   - Verify CSRF tokens
   - Test without tokens
   - Test with invalid tokens

4. PERFORMANCE OPTIMIZATION:
   - Database query optimization
   - Add missing indexes
   - Implement eager loading
   - Add caching (Redis/Memcached)
   - Optimize images
   - Implement API response caching

5. COMPREHENSIVE TESTING:
   
   A. UNIT TESTS:
   - All models (scopes, accessors, relationships)
   - All services (business logic)
   - All helpers
   - All validation rules
   
   B. FEATURE TESTS:
   - Authentication (login, logout, refresh, invalid credentials, inactive user)
   - Team management (CRUD, search, filters, authorization)
   - Client management (CRUD, default client protection, search, filters)
   - Project management (CRUD, multiple clients, status workflow, search, filters)
   - Landing page CMS (all CRUD operations, revalidation)
   - Blog system (posts, categories, tags, SEO analysis, scheduling)
   - Contact form (submission, rate limiting, spam protection, email queue)
   - RBAC (all permission checks)
   
   C. INTEGRATION TESTS:
   - End-to-end workflows
   - Multi-step processes
   - Cross-module interactions

6. DEPLOYMENT CONFIGURATION:
   
   A. PRODUCTION .ENV:
   - APP_ENV=production
   - APP_DEBUG=false
   - Database credentials
   - SMTP configuration
   - Sanctum configuration
   - Next.js URL
   - Revalidation secret
   
   B. DATABASE:
   - Run migrations
   - Run seeders (AdminSeeder, DefaultClientSeeder)
   - Verify indexes
   - Backup strategy
   
   C. FILE PERMISSIONS:
   - storage/ writable
   - bootstrap/cache/ writable
   - .env protected
   
   D. SECURITY:
   - HTTPS enforced
   - CORS configured
   - Rate limiting enabled
   - CSRF protection enabled
   - Sanctum configured

7. FINAL TESTING CHECKLIST:
   - Run full test suite (php artisan test)
   - Generate coverage report
   - Verify minimum 80% coverage
   - Test all API endpoints manually
   - Test authentication flow
   - Test authorization (all roles)
   - Test file uploads
   - Test email sending
   - Test revalidation
   - Test rate limiting
   - Test error handling


8. DOCUMENTATION:
   - API documentation (Postman collection or OpenAPI spec)
   - Deployment guide
   - Environment variables documentation
   - Database schema documentation
   - Testing guide

9. FINAL CHECKS:
   - All migrations run successfully
   - All seeders run successfully
   - All tests pass
   - No console errors
   - No PHP warnings/notices
   - Security scan clean
   - Performance benchmarks met

OUTPUT:
1. Test results summary (total tests, passed, failed, coverage %)
2. Security audit report
3. Performance audit report
4. Deployment checklist
5. API documentation
6. Any critical issues found
7. Recommendations for production

Generate complete Day 7 backend code and run all tests.
```

---

## ✅ COMPLETION CHECKLIST

After completing all 7 days, verify:

- [ ] All 300+ tasks completed
- [ ] All tests passing (minimum 80% coverage)
- [ ] Security audit clean (SQL injection, XSS, CSRF)
- [ ] Performance benchmarks met (< 500ms API responses)
- [ ] All business rules implemented
- [ ] All RBAC permissions working
- [ ] Default "Tarqumi" client protected
- [ ] 30-day inactivity tracking working
- [ ] Project code auto-generation working
- [ ] Multiple clients per project working
- [ ] Blog SEO analysis working
- [ ] Contact form rate limiting working
- [ ] Email queue working
- [ ] Revalidation system working
- [ ] Sitemap.xml generating
- [ ] Robots.txt configured
- [ ] Production .env configured
- [ ] Database migrations ready
- [ ] Seeders ready
- [ ] API documentation complete


---

## 🎉 YOU'RE DONE!

Backend is complete! Now the frontend developer can use FRONTEND_PROMPTS.md to build the Next.js application.

**Next Steps:**
1. Push all code to GitHub
2. Deploy backend to production server
3. Configure domain and SSL
4. Run migrations and seeders
5. Test production environment
6. Hand off to frontend developer

---

**Last Updated:** 2024  
**Version:** 1.0  
**Status:** Ready for Development 🚀
