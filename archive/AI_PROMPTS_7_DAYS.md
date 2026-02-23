# Tarqumi CRM - 7-Day AI Development Guide

> **Complete the entire project in 7 days using AI coding assistants**
> **Use these exact prompts with Cursor AI, Claude, or ChatGPT**

---

## 🎯 Overview

This guide provides **copy-paste AI prompts** to build the complete Tarqumi CRM in 7 days. Each prompt is optimized for maximum code generation with minimal human intervention.

**Timeline:**
- Day 1: Foundation & Database (Setup + Auth + RBAC)
- Day 2: Core CRUD (Team + Clients + Projects - Full Stack)
- Day 3: Landing Page & CMS (Public Site + Admin Panel)
- Day 4: Blog System & Contact Form (With SEO + Email)
- Day 5: Advanced Features (30-day inactive + Permissions + Business Logic)
- Day 6: SEO + Performance + Testing (Optimization + Tests)
- Day 7: Polish + Deploy + Documentation (Production Ready)

---

## 📋 DAY 1: Foundation & Database

### 🔧 Prompt 1.1: Laravel Backend Setup (30 min)

```
I need you to set up a Laravel 11 backend project with the following exact requirements:

PROJECT NAME: tarqumi-backend
PHP VERSION: 8.2+
DATABASE: MySQL with UTF8MB4

SETUP STEPS:
1. Create Laravel 11 project
2. Install Laravel Sanctum for API authentication
3. Configure CORS to allow http://localhost:3000
4. Create custom folder structure:
   - app/Services (for business logic)
   - app/Enums (for role enums)
   - app/Policies (for authorization)
   - app/Traits (for shared behavior)
   - app/Observers (for model events)

CONFIGURATION FILES NEEDED:
1. config/cors.php - Allow frontend URL, support credentials
2. config/sanctum.php - Configure stateful domains
3. bootstrap/app.php - Add Sanctum middleware
4. .env - Database config, frontend URL, Sanctum domains
5. .env.example - Template with all variables

SECURITY REQUIREMENTS:
- .env must be in .gitignore
- CORS restricted to frontend URL only
- CSRF protection enabled
- SQL injection prevention (use Eloquent ORM)

Provide:
- Complete setup commands
- All configuration file contents
- Git initialization commands
- Database creation SQL
```

### 🔧 Prompt 1.2: Next.js Frontend Setup (30 min)

```
I need you to set up a Next.js 14 frontend project with TypeScript:

PROJECT NAME: tarqumi-frontend
FRAMEWORK: Next.js 14 with App Router
LANGUAGE: TypeScript
STYLING: Tailwind CSS
I18N: next-intl (Arabic + English with RTL)

DEPENDENCIES TO INSTALL:
- next-intl (internationalization)
- axios (API calls)
- @tanstack/react-query (state management)
- framer-motion (animations)
- react-hook-form (forms)
- zod (validation)
- date-fns (date formatting)
- clsx + tailwind-merge (className utilities)

FOLDER STRUCTURE:
src/
├── app/[locale]/          # Locale-based routing
├── components/
│   ├── common/            # Shared components
│   ├── layout/            # Header, Footer, Nav
│   ├── landing/           # Landing page components
│   ├── admin/             # Admin panel components
│   └── ui/                # Base UI primitives
├── lib/                   # Utilities
├── hooks/                 # Custom React hooks
├── services/              # API service layer
├── types/                 # TypeScript types
└── messages/              # Translation files
    ├── en.json
    └── ar.json

CONFIGURATION FILES NEEDED:
1. src/i18n.ts - i18n configuration
2. src/middleware.ts - Locale routing + RTL support
3. next.config.js - Image domains, env variables
4. tailwind.config.ts - CSS variables for colors
5. src/app/globals.css - CSS variables (black & white theme)
6. .env.local - API URL
7. .env.example - Template

RTL SUPPORT:
- Middleware sets dir="rtl" for Arabic
- CSS uses logical properties (margin-inline-start)
- Layout component handles direction

API CLIENT:
- Create src/lib/api-client.ts with axios
- Request interceptor: add Bearer token
- Response interceptor: handle 401 (redirect to login)
- Base URL from environment variable

REACT QUERY:
- Create src/lib/react-query.ts with QueryClient
- Create src/components/providers/query-provider.tsx
- Add to root layout

Provide:
- Complete setup commands
- All configuration files
- API client implementation
- Translation file structure
```


### 🔧 Prompt 1.3: Users Table - Complete Implementation (1 hour)

```
Create a comprehensive Laravel users table with complete implementation:

MIGRATION (database/migrations/YYYY_MM_DD_create_users_table.php):
Table: users
Fields:
- id (bigint, primary key, auto increment)
- name (string, 100 chars)
- email (string, 255 chars, unique, indexed)
- email_verified_at (timestamp, nullable)
- password (string, hashed)
- phone (string, 20 chars, nullable)
- whatsapp (string, 20 chars, nullable)
- department (string, 100 chars, nullable)
- job_title (string, 100 chars, nullable)
- profile_picture (string, nullable - path to storage)
- role (enum: super_admin, admin, founder, hr, employee, default: employee)
- founder_role (enum: ceo, cto, cfo, nullable - only for founders)
- is_active (boolean, default true, indexed)
- last_login_at (timestamp, nullable)
- last_active_at (timestamp, nullable, indexed)
- inactive_days (integer, default 0)
- remember_token (string, nullable)
- timestamps (created_at, updated_at)
- soft deletes (deleted_at)

Indexes:
- email (unique)
- role
- is_active
- last_active_at
- Composite: (role, is_active)

MODEL (app/Models/User.php):
- Extend Authenticatable
- Use traits: HasApiTokens, HasFactory, Notifiable, SoftDeletes
- $fillable: all fields except id, timestamps
- $hidden: password, remember_token
- $casts: email_verified_at, last_login_at, last_active_at => datetime, is_active => boolean, password => hashed

Scopes:
- active(): where is_active = true
- inactive(): where is_active = false
- byRole($role): where role = $role
- admins(): whereIn role (super_admin, admin)
- founders(): where role = founder

Accessors:
- is_founder: role === 'founder'
- is_super_admin: role === 'super_admin'
- is_admin: in_array(role, ['super_admin', 'admin'])
- can_edit_landing_page: is_admin OR (is_founder AND founder_role === 'cto')

Relationships:
- managedProjects(): hasMany(Project::class, 'manager_id')
- createdClients(): hasMany(Client::class, 'created_by')
- createdProjects(): hasMany(Project::class, 'created_by')

ENUMS (app/Enums/UserRole.php):
enum UserRole: string {
  case SUPER_ADMIN = 'super_admin';
  case ADMIN = 'admin';
  case FOUNDER = 'founder';
  case HR = 'hr';
  case EMPLOYEE = 'employee';
  
  public function label(): string
  public function canEditLandingPage(): bool
  public function canManageTeam(): bool
  public function canManageClients(): bool
  public function canManageProjects(): bool
}

ENUMS (app/Enums/FounderRole.php):
enum FounderRole: string {
  case CEO = 'ceo';
  case CTO = 'cto';
  case CFO = 'cfo';
  
  public function label(): string
  public function canEditLandingPage(): bool (only CTO returns true)
}

FACTORY (database/factories/UserFactory.php):
- Default state: random employee
- States: superAdmin(), admin(), founder($role), hr(), inactive()

SEEDER (database/seeders/AdminSeeder.php):
Create 3 users:
1. Super Admin (admin@tarqumi.com, password: password, role: super_admin)
2. CTO Founder (cto@tarqumi.com, password: password, role: founder, founder_role: cto)
3. CEO Founder (ceo@tarqumi.com, password: password, role: founder, founder_role: ceo)

SECURITY:
- Passwords hashed with bcrypt
- Email unique constraint
- Soft deletes preserve audit trail
- Role validation in requests

Provide complete code for: migration, model, both enums, factory, and seeder.
```

### 🔧 Prompt 1.4: Clients Table - Complete Implementation (45 min)

```
Create complete Laravel clients table implementation:

MIGRATION (database/migrations/YYYY_MM_DD_create_clients_table.php):
Table: clients
Fields:
- id (bigint, primary key)
- name (string, 100 chars, indexed)
- company_name (string, 150 chars, indexed, nullable)
- email (string, 255 chars, unique, indexed)
- phone (string, 20 chars, nullable)
- whatsapp (string, 20 chars, nullable)
- address (text, nullable)
- website (string, 255 chars, nullable)
- industry (string, 100 chars, nullable)
- notes (text, nullable)
- is_active (boolean, default true, indexed)
- is_default (boolean, default false, indexed) - for "Tarqumi" client protection
- created_by (foreign key to users, nullable, on delete set null)
- updated_by (foreign key to users, nullable, on delete set null)
- timestamps
- soft deletes

Indexes:
- email (unique)
- name
- company_name
- is_active
- is_default

MODEL (app/Models/Client.php):
- Use SoftDeletes trait
- $fillable: all fields except id, timestamps
- $casts: is_active, is_default => boolean

Scopes:
- active(): where is_active = true
- inactive(): where is_active = false
- default(): where is_default = true
- search($term): where name LIKE OR company_name LIKE OR email LIKE

Relationships:
- projects(): belongsToMany(Project::class, 'client_project')
- creator(): belongsTo(User::class, 'created_by')
- updater(): belongsTo(User::class, 'updated_by')

Accessors:
- display_name: company_name ?? name
- projects_count: projects()->count()

Methods:
- canBeDeleted(): return !is_default (default client cannot be deleted)

POLICY (app/Policies/ClientPolicy.php):
- viewAny: super_admin, admin, founder
- view: super_admin, admin, founder
- create: super_admin, admin
- update: super_admin, admin (but NOT if is_default)
- delete: super_admin, admin (but NOT if is_default)
- restore: super_admin, admin
- forceDelete: super_admin only (and NOT if is_default)

FACTORY (database/factories/ClientFactory.php):
- Default state: random client
- States: default() (Tarqumi client), inactive()

SEEDER (database/seeders/DefaultClientSeeder.php):
Create default "Tarqumi" client:
- name: Tarqumi
- company_name: Tarqumi
- email: info@tarqumi.com
- phone: +966 XX XXX XXXX
- website: https://tarqumi.com
- industry: Technology
- notes: Default client for internal projects
- is_active: true
- is_default: true

BUSINESS RULES:
- Default "Tarqumi" client CANNOT be deleted
- Default client name and email cannot be changed
- When client deleted (soft delete), projects are preserved
- Email must be unique

Provide complete code for: migration, model, policy, factory, and seeder.
```

### 🔧 Prompt 1.5: Projects Table - Complete Implementation (1 hour)

```
Create complete Laravel projects table with multiple clients support:

MIGRATION 1 (database/migrations/YYYY_MM_DD_create_projects_table.php):
Table: projects
Fields:
- id (bigint, primary key)
- code (string, 50 chars, unique, indexed) - auto-generated: PROJ-2024-0001
- name (string, 150 chars, indexed)
- description (text, nullable)
- manager_id (foreign key to users, on delete restrict)
- budget (decimal 15,2, default 0)
- currency (string, 3 chars, default SAR) - USD, EUR, SAR, AED, EGP
- priority (tinyint, 1-10 scale)
- start_date (date, indexed)
- end_date (date, nullable, indexed)
- estimated_hours (integer, nullable)
- status (enum: planning, analysis, design, implementation, testing, deployment, default: planning)
- is_active (boolean, default true, indexed)
- created_by (foreign key to users, nullable, on delete set null)
- updated_by (foreign key to users, nullable, on delete set null)
- timestamps
- soft deletes

Indexes:
- code (unique)
- name
- manager_id
- status
- is_active
- start_date
- end_date
- Composite: (status, is_active)

MIGRATION 2 (database/migrations/YYYY_MM_DD_create_client_project_table.php):
Table: client_project (pivot)
Fields:
- id (bigint, primary key)
- client_id (foreign key to clients, on delete cascade)
- project_id (foreign key to projects, on delete cascade)
- is_primary (boolean, default false)
- timestamps

Unique constraint: (client_id, project_id)
Indexes: client_id, project_id

MODEL (app/Models/Project.php):
- Use SoftDeletes trait
- $fillable: all fields except id, code, timestamps
- $casts: budget => decimal:2, priority => integer, start_date, end_date => date, is_active => boolean

Boot method:
- Auto-generate code on creation: PROJ-{YEAR}-{NUMBER}
- Example: PROJ-2024-0001, PROJ-2024-0002
- Number increments per year

Scopes:
- active(): where is_active = true
- inactive(): where is_active = false
- byStatus($status): where status = $status
- byPriority($min, $max): whereBetween priority
- byManager($managerId): where manager_id = $managerId
- overdue(): where end_date < today AND status != 'deployment'

Relationships:
- clients(): belongsToMany(Client::class, 'client_project')->withPivot('is_primary')->withTimestamps()
- manager(): belongsTo(User::class, 'manager_id')
- creator(): belongsTo(User::class, 'created_by')
- updater(): belongsTo(User::class, 'updated_by')

Accessors:
- is_overdue: end_date < today AND status != 'deployment'
- days_remaining: end_date->diffInDays(today())
- completion_percentage: calculate based on status

ENUM (app/Enums/ProjectStatus.php):
enum ProjectStatus: string {
  case PLANNING = 'planning';
  case ANALYSIS = 'analysis';
  case DESIGN = 'design';
  case IMPLEMENTATION = 'implementation';
  case TESTING = 'testing';
  case DEPLOYMENT = 'deployment';
  
  public function label(): string
  public function order(): int (0-5)
  public function percentage(): int (0, 20, 40, 60, 80, 100)
}

FACTORY (database/factories/ProjectFactory.php):
- Default state: random project with 1-3 clients
- States: active(), inactive(), overdue()

BUSINESS RULES:
- Project code auto-generated on creation
- If no clients specified, attach default "Tarqumi" client
- Manager cannot be deleted if managing projects (must reassign first)
- Priority must be 1-10
- Projects can have multiple clients
- One client can be marked as primary

Provide complete code for: both migrations, model, enum, and factory.
```


### 🔧 Prompt 1.6: Authentication System - Complete (1.5 hours)

```
Create complete Laravel Sanctum authentication system with frontend:

BACKEND:

1. AuthController (app/Http/Controllers/Api/V1/AuthController.php):

Methods:
- login(LoginRequest $request): JsonResponse
  * Find user by email
  * Check password with Hash::check()
  * Check if user is_active
  * Update last_login_at and last_active_at
  * Create Sanctum token
  * Return user data + token
  
- logout(Request $request): JsonResponse
  * Delete current access token
  * Return success message
  
- user(Request $request): JsonResponse
  * Return current authenticated user with permissions
  
- refresh(Request $request): JsonResponse
  * Delete old token
  * Create new token
  * Return new token

Response format:
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "Admin",
      "email": "admin@tarqumi.com",
      "role": "super_admin",
      "founder_role": null,
      "can_edit_landing_page": true
    },
    "token": "1|xxxxx"
  },
  "message": "Login successful"
}

2. LoginRequest (app/Http/Requests/LoginRequest.php):
Validation rules:
- email: required, email, max:255
- password: required, string, min:8

Custom error messages in Arabic and English

3. UpdateLastActive Middleware (app/Http/Middleware/UpdateLastActive.php):
- Update last_active_at on every authenticated request
- Register in bootstrap/app.php

4. Routes (routes/api.php):
Route::prefix('v1')->group(function () {
    // Public
    Route::post('/auth/login', [AuthController::class, 'login']);
    
    // Protected
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/user', [AuthController::class, 'user']);
        Route::post('/auth/refresh', [AuthController::class, 'refresh']);
    });
});

FRONTEND:

1. API Client (src/lib/api-client.ts):
- Axios instance with baseURL from env
- Request interceptor: add Bearer token from localStorage
- Response interceptor: handle 401 (redirect to /login)
- withCredentials: true

2. Auth Service (src/services/auth.service.ts):
class AuthService {
  async login(email: string, password: string)
  async logout()
  async getCurrentUser()
  async refreshToken()
  getToken(): string | null
  isAuthenticated(): boolean
}

3. Auth Types (src/types/auth.ts):
- LoginCredentials
- AuthUser
- LoginResponse
- ApiResponse<T>

4. Login Page (src/app/[locale]/login/page.tsx):
- Form with email and password
- react-hook-form + zod validation
- Loading state
- Error display
- Redirect to /dashboard on success
- Bilingual with next-intl
- RTL support

5. useAuth Hook (src/hooks/use-auth.ts):
- Use React Query
- Provide: user, login, logout, isLoading, error
- Auto-fetch user on mount if token exists

6. Protected Route Component (src/components/auth/protected-route.tsx):
- Check authentication
- Redirect to /login if not authenticated
- Show loading spinner

SECURITY:
- Passwords hashed with bcrypt
- Token stored in localStorage
- 401 responses clear token and redirect
- CSRF protection enabled
- Rate limiting on login (5 attempts per minute)

TESTING:
- Test login with valid credentials
- Test login with invalid credentials
- Test login with inactive user
- Test logout
- Test token refresh
- Test protected routes

Provide complete code for all backend and frontend files.
```

### 🔧 Prompt 1.7: Role-Based Access Control - Complete (1.5 hours)

```
Create comprehensive RBAC system for Laravel + Next.js:

BACKEND:

1. RoleMiddleware (app/Http/Middleware/RoleMiddleware.php):
- Accept multiple roles as parameters
- Check if authenticated user has one of the roles
- Return 403 if unauthorized
- Usage: Route::middleware('role:super_admin,admin')

2. FounderRoleMiddleware (app/Http/Middleware/FounderRoleMiddleware.php):
- Check if user is founder with specific founder_role
- Return 403 if unauthorized
- Usage: Route::middleware('founder.role:cto')

3. HasPermissions Trait (app/Traits/HasPermissions.php):
Add to User model with methods:
- canManageTeam(): bool (super_admin, admin, hr)
- canCreateTeamMember(): bool (super_admin, admin, hr)
- canEditTeamMember(): bool (super_admin, admin, hr)
- canDeleteTeamMember(): bool (super_admin, admin)
- canDeleteAdmin(): bool (super_admin only)
- canManageClients(): bool (super_admin, admin)
- canViewClients(): bool (super_admin, admin, founder)
- canManageProjects(): bool (super_admin, admin)
- canViewProjects(): bool (super_admin, admin, founder)
- canEditLandingPage(): bool (super_admin, admin, OR founder with cto role)
- canManageBlog(): bool (same as canEditLandingPage)
- canViewContactSubmissions(): bool (super_admin, admin)
- canDeleteContactSubmissions(): bool (super_admin, admin)

4. PermissionsController (app/Http/Controllers/Api/V1/PermissionsController.php):
- GET /api/v1/permissions
- Return all permissions for current user
- Response includes role, founder_role, and all permission booleans

5. Permissions Config (config/permissions.php):
Define all roles and their permissions in structured format

6. Register Middleware (bootstrap/app.php):
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
        'founder.role' => \App\Http\Middleware\FounderRoleMiddleware::class,
        'update.last.active' => \App\Http\Middleware\UpdateLastActive::class,
    ]);
})

FRONTEND:

1. Permissions Context (src/contexts/permissions-context.tsx):
- Fetch permissions from /api/v1/permissions on login
- Store in React Context
- Provide usePermissions() hook

2. Permissions Types (src/types/permissions.ts):
interface Permissions {
  can_manage_team: boolean;
  can_create_team_member: boolean;
  can_edit_team_member: boolean;
  can_delete_team_member: boolean;
  can_delete_admin: boolean;
  can_manage_clients: boolean;
  can_view_clients: boolean;
  can_manage_projects: boolean;
  can_view_projects: boolean;
  can_edit_landing_page: boolean;
  can_manage_blog: boolean;
  can_view_contact_submissions: boolean;
  can_delete_contact_submissions: boolean;
}

3. Can Component (src/components/auth/can.tsx):
<Can permission="manage_team">
  <button>Create Team Member</button>
</Can>

4. usePermissions Hook (src/hooks/use-permissions.ts):
- Return permissions object
- Return hasPermission(permission: string) function

BUSINESS RULES:
- Only Super Admin can delete other Admins
- Only CTO founder can edit landing page
- CEO and CFO founders CANNOT edit landing page
- Employee cannot edit own profile
- HR can manage team but cannot delete admins
- All permissions checked on backend (frontend is UI only)

TESTING:
- Test each role's permissions
- Test founder sub-role permissions
- Test permission checks on routes
- Test UI hiding based on permissions

Provide complete code for all backend and frontend files.
```

**✅ DAY 1 DELIVERABLES:**
- Laravel backend fully setup with Sanctum
- Next.js frontend fully setup with i18n and RTL
- Complete database schema (users, clients, projects)
- Authentication system working (login/logout/refresh)
- RBAC system implemented (roles, permissions, middleware)
- All seeders run (3 admin users, default Tarqumi client)
- Both projects running and connected via API

---

## 📋 DAY 2: Core CRUD Operations (10-12 hours)

### 🔧 Prompt 2.1: Team Management - Complete CRUD (3 hours)

```
Create complete Team Management CRUD (backend + frontend):

BACKEND:

1. TeamController (app/Http/Controllers/Api/V1/TeamController.php):
Resource controller with methods:
- index(IndexTeamRequest): list with pagination, search, filters
- store(StoreTeamMemberRequest): create new team member
- show(User): get single team member
- update(UpdateTeamMemberRequest, User): update team member
- destroy(DeleteTeamMemberRequest, User): soft delete with project reassignment

2. StoreTeamMemberRequest (app/Http/Requests/StoreTeamMemberRequest.php):
Validation:
- name: required, string, min:2, max:100
- email: required, email, max:255, unique:users
- password: required, string, min:8, confirmed
- phone, whatsapp: nullable, string, max:20
- department, job_title: nullable, string, max:100
- role: required, in:super_admin,admin,founder,hr,employee
- founder_role: nullable, required_if:role,founder, in:ceo,cto,cfo
- is_active: boolean
- profile_picture: nullable, image, max:5120 (5MB)

Authorization: user must have canCreateTeamMember() permission

3. UpdateTeamMemberRequest (app/Http/Requests/UpdateTeamMemberRequest.php):
Same validation as Store but:
- All fields optional (sometimes)
- Email unique except current user
- Password optional (only if changing)
- Authorization: cannot edit super_admin unless you are super_admin
- Authorization: cannot escalate own privileges

4. DeleteTeamMemberRequest (app/Http/Requests/DeleteTeamMemberRequest.php):
Authorization:
- Only super_admin can delete super_admin
- Cannot delete last super_admin
- Must have canDeleteTeamMember() permission

5. IndexTeamRequest (app/Http/Requests/IndexTeamRequest.php):
Query parameters:
- page: integer, min:1
- per_page: integer, in:10,20,50,100
- search: nullable, string (searches name, email, phone, department)
- role: nullable, in:super_admin,admin,founder,hr,employee
- status: nullable, in:active,inactive
- department: nullable, string
- sort_by: nullable, in:name,email,role,created_at,last_active_at
- sort_order: nullable, in:asc,desc

6. TeamService (app/Services/TeamService.php):
Methods:
- createTeamMember(array $data): User
- updateTeamMember(User $user, array $data): User
- deleteTeamMember(User $user): bool
- reassignProjects(User $oldManager, User $newManager): int
- getTeamMembers(array $filters): LengthAwarePaginator
- getDepartments(): array
- uploadProfilePicture(UploadedFile $file): string

7. UserResource (app/Http/Resources/UserResource.php):
Transform user data for API responses

8. Routes (routes/api.php):
Route::middleware(['auth:sanctum', 'role:super_admin,admin,hr'])->group(function () {
    Route::get('/team', [TeamController::class, 'index']);
    Route::post('/team', [TeamController::class, 'store']);
    Route::get('/team/{user}', [TeamController::class, 'show']);
    Route::put('/team/{user}', [TeamController::class, 'update']);
    Route::delete('/team/{user}', [TeamController::class, 'destroy']);
    Route::get('/team/departments', [TeamController::class, 'departments']);
    Route::post('/team/{oldManager}/reassign/{newManager}', [TeamController::class, 'reassign']);
});

FRONTEND:

1. Team Service (src/services/team.service.ts):
class TeamService extends BaseService<User> {
  async getAll(filters)
  async getById(id)
  async create(data)
  async update(id, data)
  async delete(id)
  async getDepartments()
  async reassignProjects(oldManagerId, newManagerId)
}

2. Team List Page (src/app/[locale]/dashboard/team/page.tsx):
- Table with columns: Photo, Name, Email, Role, Department, Status, Actions
- Search input (debounced)
- Filter dropdowns: Role, Status, Department
- Sort controls
- Pagination
- Create button (if has permission)
- Actions: View, Edit, Delete (based on permissions)

3. Create Team Page (src/app/[locale]/dashboard/team/new/page.tsx):
- Form with all fields
- Role selection with conditional founder_role
- Profile picture upload with preview
- Password and password_confirmation
- react-hook-form + zod validation
- Submit to API
- Redirect to list on success

4. Edit Team Page (src/app/[locale]/dashboard/team/[id]/edit/page.tsx):
- Load existing data
- Pre-fill form
- Profile picture update
- Password optional
- Cannot edit super_admin unless you are super_admin
- Submit to API

5. Delete Modal (src/components/team/delete-team-member-modal.tsx):
- Confirmation dialog
- Check if user manages projects
- If yes, show project reassignment dropdown
- Submit delete + reassignment
- Show success/error message

6. Team Types (src/types/team.ts):
- User interface
- CreateTeamMemberData interface
- UpdateTeamMemberData interface
- TeamFilters interface

BILINGUAL:
- All text in translation files
- RTL support for Arabic
- Form labels and errors in both languages

TESTING:
- Test create team member
- Test update team member
- Test delete team member
- Test project reassignment
- Test search and filters
- Test permissions

Provide complete code for all backend and frontend files.
```

