# Frontend Developer - Kiro AI Prompts (7 Days)

> **Copy each day's prompt and paste into Kiro AI**
> **Kiro will read all project files and generate complete frontend code**

---

## 📋 How to Use This File

1. **Each day:** Copy the entire prompt for that day
2. **Paste into Kiro AI** in a new or continuing chat
3. **Kiro will:**
   - Read all referenced project files
   - Understand requirements and business rules
   - Generate complete frontend code
   - Create tests
   - Report what was created
4. **You:** Review, test, commit, move to next day

---

## 🚀 DAY 1: Next.js Setup & Authentication UI

**Copy this entire prompt:**

```
CONTEXT:
I'm the frontend developer for Tarqumi CRM. This is Day 1 of 7-day development.
Backend API is ready (or will be ready soon).

READ THESE FILES FIRST:
- /docs/coding_rules.md (React, Next.js, TypeScript, i18n standards)
- /docs/meeting_notes.md (Business requirements and rules)
- /tasks/01-project-setup-infrastructure.md
- /tasks/03-authentication-authorization.md
- /user-stories/01-authentication-security.md

TECH STACK:
- Next.js 14 (App Router)
- TypeScript
- Tailwind CSS
- next-intl (Arabic + English with RTL)
- React Query (API state management)
- react-hook-form + zod (forms)
- Axios (API client)

DAY 1 TASKS - CREATE COMPLETE FRONTEND FOUNDATION:

1. PROJECT SETUP:
   - Create Next.js 14 project with TypeScript
   - Install dependencies: tailwindcss, next-intl, @tanstack/react-query, axios, react-hook-form, zod, framer-motion
   - Configure Tailwind CSS with CSS variables
   - Setup folder structure: app/, components/, lib/, hooks/, services/, types/, messages/
   - Configure .env.local with API_URL

2. I18N CONFIGURATION:
   - Setup next-intl with Arabic and English
   - Create messages/ar.json and messages/en.json
   - Configure middleware for locale routing (/ar/*, /en/*)
   - Setup RTL support in layout
   - Create LanguageSwitcher component

3. CSS VARIABLES & THEME:
   - Define all colors as CSS variables in globals.css
   - Black & white theme with shades
   - No dark mode toggle
   - Responsive breakpoints
   - Animation utilities

4. API CLIENT SETUP:
   - Create lib/axios.ts with base configuration
   - Setup interceptors for auth token
   - Error handling
   - Request/response transformers
   - Create services/auth.service.ts

5. AUTHENTICATION SYSTEM:
   
   A. LOGIN PAGE (/login):
   - Login form with email and password
   - Form validation (react-hook-form + zod)
   - Error messages
   - Loading states
   - Remember me checkbox
   - Bilingual support
   - RTL layout for Arabic
   
   B. AUTH CONTEXT:
   - Create contexts/AuthContext.tsx
   - Store user and token
   - Login, logout, refresh methods
   - Protected route wrapper
   - Permission checks
   
   C. AUTH HOOKS:
   - useAuth() hook
   - usePermissions() hook
   - useRequireAuth() hook
   
   D. PROTECTED LAYOUT:
   - Admin layout with sidebar and header
   - User menu with profile and logout
   - Breadcrumbs
   - Mobile responsive

6. TYPES:
   - Create types/user.ts
   - Create types/auth.ts
   - Create types/api.ts
   - All API response types

7. COMPONENTS:
   - Button component (variants: primary, secondary, danger, ghost)
   - Input component (with error states)
   - Form components
   - Loading spinner
   - Toast notifications
   - Modal component

8. TESTS:
   - Login form tests
   - Auth context tests
   - Protected route tests
   - API client tests

CRITICAL BUSINESS RULES:
- Bilingual (AR/EN) with full RTL support
- URL structure: /ar/page and /en/page
- Zero hardcoded strings (all text in translation files)
- Black & white design
- Inactive users cannot login
- Token stored in localStorage
- Auto-refresh token before expiry

SECURITY:
- HTTPS only in production
- Secure token storage
- CSRF protection
- XSS prevention
- Input validation on all forms

OUTPUT:
1. List all files created
2. How to run the project
3. How to test authentication
4. Any important notes

Let's start! Generate complete Day 1 frontend code.
```

---

## 🚀 DAY 2: Team Management UI

**Copy this entire prompt:**

```
CONTEXT:
Frontend Developer - Day 2 of 7. Day 1 (Next.js setup + auth) is complete.

READ THESE FILES:
- /tasks/04-team-management-crud.md
- /user-stories/02-team-management.md
- /docs/coding_rules.md
- /docs/meeting_notes.md

DAY 2 TASKS - TEAM MANAGEMENT UI:

1. TEAM MANAGEMENT PAGES:
   
   A. TEAM LIST PAGE (/admin/team):
   - Data table with pagination
   - Search bar (name, email, phone, department)
   - Filters (role, status, department)
   - Sorting (name, email, role, last_active)
   - Actions (view, edit, delete)
   - Add new member button
   - Export to CSV button
   - Responsive design
   
   B. CREATE TEAM MEMBER MODAL:
   - Form with all fields (name, email, password, role, etc.)
   - Profile picture upload with preview
   - Role selection (super_admin, admin, founder, hr, employee)
   - Founder role selection (CEO, CTO, CFO) - conditional
   - Department input with autocomplete
   - Form validation
   - Success/error handling
   
   C. EDIT TEAM MEMBER MODAL:
   - Same as create but pre-filled
   - Password optional
   - Cannot edit Super Admin (unless you are Super Admin)
   - Cannot escalate own privileges
   
   D. DELETE CONFIRMATION MODAL:
   - Warning if user manages projects
   - Project reassignment selector
   - Confirmation required
   - Cannot delete last Super Admin
   
   E. TEAM MEMBER DETAIL PAGE (/admin/team/[id]):
   - All user information
   - Profile picture
   - Managed projects list
   - Activity history
   - Edit and delete buttons

2. COMPONENTS:
   - DataTable component (reusable)
   - SearchBar component
   - FilterDropdown component
   - Pagination component
   - UserAvatar component
   - RoleBadge component
   - StatusBadge component
   - ConfirmDialog component

3. SERVICES:
   - services/team.service.ts
   - All CRUD operations
   - Search and filter
   - Export functionality

4. TYPES:
   - types/team.ts
   - User type
   - Role enums
   - Filter types

5. HOOKS:
   - useTeamMembers() hook (React Query)
   - useCreateTeamMember() hook
   - useUpdateTeamMember() hook
   - useDeleteTeamMember() hook
   - useDepartments() hook

6. FORMS:
   - Team member form schema (zod)
   - Form validation rules
   - Error messages (bilingual)

7. TESTS:
   - Team list tests
   - Create form tests
   - Edit form tests
   - Delete confirmation tests
   - Search and filter tests

CRITICAL BUSINESS RULES:
- Only Super Admin can delete other Admins
- Employee cannot edit own profile
- Admin cannot escalate own privileges
- Cannot delete last Super Admin
- Manager cannot be deleted if managing projects (must reassign first)
- Profile picture max 5MB
- All text bilingual with RTL support

SECURITY:
- Authorization checks on all actions
- Form validation on all inputs
- Secure file upload
- XSS prevention

OUTPUT:
1. List all files created
2. How to test team management
3. Any important notes

Generate complete Day 2 frontend code.
```

---

## 🚀 DAY 3: Client & Project Management UI

**Copy this entire prompt:**

```
CONTEXT:
Frontend Developer - Day 3 of 7. Days 1-2 (auth + team) complete.

READ THESE FILES:
- /tasks/05-client-management-crud.md
- /tasks/06-project-management-crud.md
- /user-stories/03-client-management.md
- /user-stories/04-project-management.md
- /docs/coding_rules.md
- /docs/meeting_notes.md

DAY 3 TASKS - CLIENT & PROJECT MANAGEMENT UI:

1. CLIENT MANAGEMENT:
   
   A. CLIENT LIST PAGE (/admin/clients):
   - Data table with pagination
   - Search (name, company, email, phone)
   - Filters (status, industry, has_projects)
   - Sorting
   - Actions (view, edit, delete, restore)
   - Add new client button
   - Export to CSV
   
   B. CREATE/EDIT CLIENT MODAL:
   - All fields (name, company, email, phone, whatsapp, address, website, industry, notes)
   - Form validation
   - Default "Tarqumi" client protection (name/email cannot change)
   - Success/error handling
   
   C. DELETE CLIENT CONFIRMATION:
   - Warning about projects
   - Soft delete explanation
   - Cannot delete default "Tarqumi" client
   
   D. CLIENT DETAIL PAGE (/admin/clients/[id]):
   - All client information
   - Associated projects list
   - Contact history
   - Change history
   - Edit and delete buttons

2. PROJECT MANAGEMENT:
   
   A. PROJECT LIST PAGE (/admin/projects):
   - Data table with pagination
   - Search (name, code, client, manager)
   - Filters (status, priority, manager, client, budget_range, date_range, active)
   - Sorting
   - View toggle (table/kanban)
   - Actions (view, edit, delete, restore)
   - Add new project button
   - Export to CSV
   
   B. KANBAN VIEW:
   - 6 columns (6 SDLC phases)
   - Drag-and-drop status change
   - Project cards with key info
   - Filter and search
   
   C. CREATE/EDIT PROJECT MODAL:
   - All fields (name, description, clients, manager, budget, currency, priority, dates, status)
   - Multiple clients selector (1-10 clients)
   - Manager selector (active team members only)
   - Priority slider (1-10)
   - Date pickers
   - Budget with currency selector
   - Status selector (6 SDLC phases)
   - Form validation
   - Auto-generate project code (display only)
   
   D. PROJECT DETAIL PAGE (/admin/projects/[id]):
   - All project information
   - All clients (with primary indicator)
   - Manager details
   - Timeline visualization
   - Budget tracking
   - Progress indicator
   - Activity log
   - Edit and delete buttons
   - Status change button

3. COMPONENTS:
   - ClientCard component
   - ProjectCard component
   - KanbanBoard component
   - KanbanColumn component
   - PrioritySlider component
   - StatusBadge component (6 SDLC phases with colors)
   - BudgetDisplay component
   - TimelineChart component
   - ProgressBar component
   - MultiSelect component (for clients)

4. SERVICES:
   - services/client.service.ts
   - services/project.service.ts
   - All CRUD operations
   - Search, filter, export

5. TYPES:
   - types/client.ts
   - types/project.ts
   - Status enums (6 SDLC phases)
   - Priority type (1-10)

6. HOOKS:
   - useClients() hook
   - useCreateClient() hook
   - useUpdateClient() hook
   - useDeleteClient() hook
   - useProjects() hook
   - useCreateProject() hook
   - useUpdateProject() hook
   - useDeleteProject() hook
   - useKanbanData() hook

7. TESTS:
   - Client CRUD tests
   - Project CRUD tests
   - Kanban view tests
   - Multiple clients selector tests
   - Default client protection tests

CRITICAL BUSINESS RULES:
- Default "Tarqumi" client CANNOT be deleted
- Projects can have multiple clients (1-10)
- If no client specified, defaults to "Tarqumi"
- Project code auto-generated (PROJ-2024-0001)
- Priority must be 1-10 (slider)
- 6 SDLC status phases with specific colors
- Manager cannot be deleted if managing projects
- All text bilingual with RTL support

SECURITY:
- Authorization checks
- Form validation
- XSS prevention

OUTPUT:
1. List all files created
2. How to test client management
3. How to test project management
4. Any important notes

Generate complete Day 3 frontend code.
```

---

## 🚀 DAY 4: Landing Page CMS UI

**Copy this entire prompt:**

```
CONTEXT:
Frontend Developer - Day 4 of 7. Days 1-3 (auth, team, clients, projects) complete.

READ THESE FILES:
- /tasks/07-landing-page-cms.md
- /user-stories/05-landing-page-cms.md
- /docs/coding_rules.md
- /docs/meeting_notes.md

DAY 4 TASKS - LANDING PAGE CMS UI:

1. CMS DASHBOARD (/admin/cms):
   - Overview of all editable sections
   - Quick links to each section
   - Last updated timestamps
   - Preview button (opens public site)
   - Only accessible to CTO founder

2. SEO SETTINGS PAGE (/admin/cms/seo):
   - Page selector (home, about, services, projects, blog, contact)
   - Meta title input (10-60 chars with counter)
   - Meta description textarea (50-160 chars with counter)
   - Meta keywords input (max 10)
   - OG image upload (1200x630px)
   - Canonical URL input
   - Robots meta selector
   - Live preview (Google/Facebook/Twitter format)
   - Save button with instant revalidation
   - Bilingual tabs (AR/EN)

3. HERO SECTION PAGE (/admin/cms/hero):
   - Headline input (bilingual)
   - Subheadline textarea (bilingual)
   - Primary CTA text and link
   - Secondary CTA text and link (optional)
   - Background image upload (max 10MB)
   - Background video upload (max 50MB, optional)
   - Overlay opacity slider (0-100)
   - Live preview
   - Save with revalidation

4. SERVICES MANAGEMENT PAGE (/admin/cms/services):
   - Services list with drag-and-drop reordering
   - Add new service button
   - Edit/delete actions
   - Create/Edit Service Modal:
     * Title (bilingual)
     * Short description (bilingual)
     * Long description (bilingual, optional)
     * Icon selector (library or custom SVG upload)
     * Image upload (optional)
     * Status (published/draft)
   - Home page preview settings:
     * Select how many to show (3, 4, 6, 8)
     * Reorder for home page
     * Layout selector (grid, carousel)

5. SHOWCASE PROJECTS PAGE (/admin/cms/showcase-projects):
   - Projects list with drag-and-drop
   - Add new project button
   - Edit/delete actions
   - Create/Edit Project Modal:
     * Title (bilingual)
     * Client name and logo upload
     * Description (bilingual)
     * Challenge, solution, results (bilingual, optional)
     * Thumbnail upload
     * Gallery images (up to 10, drag-and-drop)
     * Category selector
     * Technology tags
     * Project URL
     * Project date
     * Featured checkbox
     * Status (published/draft)
   - Featured projects selector

6. ABOUT PAGE EDITOR (/admin/cms/about):
   - Mission statement (bilingual, rich text)
   - Vision statement (bilingual, rich text)
   - Company story (bilingual, rich text)
   - Core values editor (up to 6):
     * Icon selector
     * Title (bilingual)
     * Description (bilingual)
     * Drag-and-drop reordering
   - Statistics editor (4 stats):
     * Number
     * Label (bilingual)
     * Icon selector
   - Team member selector (from team members)
   - Live preview
   - Save with revalidation

7. FOOTER SETTINGS PAGE (/admin/cms/footer):
   - Logo upload
   - Company email
   - Company phone
   - Company address (bilingual)
   - Footer text (bilingual)
   - Social media links editor:
     * Add/remove platforms
     * URL input
     * Icon selector
     * Drag-and-drop reordering
   - Quick links editor:
     * Add/remove links
     * Text (bilingual) and URL
     * Drag-and-drop reordering
   - Copyright year (auto-update, display only)
   - Save with revalidation

8. MEDIA LIBRARY PAGE (/admin/cms/media):
   - Grid view of all uploaded media
   - Upload area (drag-and-drop, multiple files)
   - Search and filter
   - Image details modal:
     * Preview
     * Filename
     * File size
     * Dimensions
     * Alt text editor
     * Usage count and locations
     * Delete button
   - Bulk actions (select multiple, delete)
   - Image editor (crop, resize, rotate)

9. COMPONENTS:
   - RichTextEditor component (TipTap or similar)
   - ImageUploader component (with preview and crop)
   - IconSelector component
   - DragDropList component
   - SEOPreview component
   - LivePreview component
   - CharacterCounter component
   - MediaPicker component

10. SERVICES:
    - services/cms.service.ts
    - services/media.service.ts
    - All CMS operations
    - Revalidation triggers

11. TYPES:
    - types/cms.ts
    - types/media.ts
    - All CMS content types

12. HOOKS:
    - useSEOSettings() hook
    - useHeroSection() hook
    - useServices() hook
    - useShowcaseProjects() hook
    - useAboutPage() hook
    - useFooterSettings() hook
    - useMediaLibrary() hook
    - useRevalidate() hook

13. TESTS:
    - SEO settings tests
    - Hero section tests
    - Services CRUD tests
    - Showcase projects tests
    - Media library tests
    - Revalidation tests

CRITICAL BUSINESS RULES:
- Only CTO founder can access CMS
- All content bilingual (AR/EN)
- Instant revalidation on save
- Max image upload: 20MB
- Character counters with color coding (green/yellow/red)
- Live preview before saving
- Auto-optimize images
- All text in translation files

SECURITY:
- Authorization (only CTO)
- File upload validation
- XSS prevention (sanitize rich text)
- Image size limits

OUTPUT:
1. List all files created
2. How to test CMS operations
3. How to test revalidation
4. Any important notes

Generate complete Day 4 frontend code.
```

---

## 🚀 DAY 5: Blog System UI

**Copy this entire prompt:**

```
CONTEXT:
Frontend Developer - Day 5 of 7. Days 1-4 (auth, team, clients, projects, CMS) complete.

READ THESE FILES:
- /tasks/08-blog-system.md
- /user-stories/06-blog-system.md
- /docs/coding_rules.md
- /docs/meeting_notes.md

DAY 5 TASKS - BLOG SYSTEM UI:

1. BLOG POSTS LIST PAGE (/admin/blog/posts):
   - Data table with pagination
   - Search (title, content, author)
   - Filters (status, category, tag, date_range)
   - Sorting (title, date, status, views)
   - Actions (view, edit, delete, duplicate)
   - Add new post button
   - Bulk actions (publish, draft, delete)
   - Status badges (draft/published/scheduled)

2. CREATE/EDIT BLOG POST PAGE (/admin/blog/posts/new, /admin/blog/posts/[id]/edit):
   - Title input (bilingual)
   - Slug input (auto-generated, editable, bilingual)
   - Rich text editor (bilingual):
     * Full formatting toolbar
     * Image upload
     * Code blocks with syntax highlighting
     * Tables
     * Embeds (YouTube, etc.)
   - Excerpt textarea (bilingual)
   - Featured image upload
   - Category selector (multiple, hierarchical)
   - Tag input (autocomplete, create new)
   - Status selector (draft/published/scheduled)
   - Publish date picker (for scheduling)
   - SEO section:
     * Meta title (bilingual)
     * Meta description (bilingual)
     * Meta keywords (bilingual)
     * Focus keyword
     * SEO score indicator (0-100)
     * Readability score
     * SEO suggestions list
   - Preview button
   - Save draft button
   - Publish button
   - Schedule button
   - Bilingual tabs (AR/EN)

3. BLOG POST DETAIL PAGE (/admin/blog/posts/[id]):
   - All post information
   - View count
   - Comments count (Phase 2)
   - Version history
   - Edit and delete buttons
   - Duplicate button
   - View on site button

4. CATEGORIES PAGE (/admin/blog/categories):
   - Hierarchical tree view
   - Drag-and-drop reordering
   - Add new category button
   - Edit/delete actions
   - Create/Edit Category Modal:
     * Name (bilingual)
     * Slug (auto-generated, bilingual)
     * Description (bilingual)
     * Parent category selector
     * Icon selector
     * Display order

5. TAGS PAGE (/admin/blog/tags):
   - Tags list with usage count
   - Search tags
   - Add new tag button
   - Edit/delete actions
   - Merge tags feature
   - Create/Edit Tag Modal:
     * Name (bilingual)
     * Slug (auto-generated, bilingual)

6. BLOG ANALYTICS PAGE (/admin/blog/analytics):
   - Total posts
   - Published vs draft
   - Views over time chart
   - Top posts by views
   - Top categories
   - Top tags
   - Recent posts
   - Scheduled posts
   - Export button

7. SEO ANALYSIS COMPONENT:
   - Real-time SEO score calculation
   - Keyword density analysis
   - Readability score
   - Suggestions list with priorities
   - Traffic light indicators (red/yellow/green)
   - Progress bars for each metric

8. VERSION HISTORY MODAL:
   - List of all versions
   - Version comparison (side-by-side diff)
   - Restore to version button
   - Version metadata (date, author, changes)

9. COMPONENTS:
   - BlogPostCard component
   - RichTextEditor component (with all features)
   - SEOAnalyzer component
   - CategoryTree component
   - TagInput component (with autocomplete)
   - VersionHistory component
   - SchedulePublish component
   - BlogPreview component

10. SERVICES:
    - services/blog.service.ts
    - services/category.service.ts
    - services/tag.service.ts
    - All blog operations
    - SEO analysis
    - Version history

11. TYPES:
    - types/blog.ts
    - types/category.ts
    - types/tag.ts
    - Post status enums

12. HOOKS:
    - useBlogPosts() hook
    - useCreatePost() hook
    - useUpdatePost() hook
    - useDeletePost() hook
    - useCategories() hook
    - useTags() hook
    - useSEOAnalysis() hook
    - useVersionHistory() hook
    - useSchedulePost() hook

13. TESTS:
    - Blog post CRUD tests
    - Rich text editor tests
    - SEO analysis tests
    - Category hierarchy tests
    - Tag autocomplete tests
    - Version history tests
    - Scheduling tests

CRITICAL BUSINESS RULES:
- Blog SEO must be exceptional
- Auto-generate slugs from titles
- Version history for all changes
- Scheduling system
- Real-time SEO analysis
- Bilingual content (AR/EN)
- Rich text with image upload
- Category hierarchy support
- Tag autocomplete and suggestions
- All text in translation files

SECURITY:
- Authorization checks
- XSS prevention (sanitize rich text)
- Image upload validation
- Form validation

OUTPUT:
1. List all files created
2. How to test blog system
3. How to test SEO analysis
4. Any important notes

Generate complete Day 5 frontend code.
```

---

## 🚀 DAY 6: Contact Form & Public Landing Pages

**Copy this entire prompt:**

```
CONTEXT:
Frontend Developer - Day 6 of 7. Days 1-5 (auth, team, clients, projects, CMS, blog) complete.

READ THESE FILES:
- /tasks/09-contact-form-email.md
- /user-stories/07-contact-communication.md
- /user-stories/08-public-landing-page.md
- /docs/coding_rules.md
- /docs/meeting_notes.md

DAY 6 TASKS - CONTACT FORM & PUBLIC LANDING PAGES:

1. CONTACT SUBMISSIONS PAGE (/admin/contact):
   - Submissions list with pagination
   - Search (name, email, message)
   - Filters (status, date_range, subject)
   - Sorting (date, status, name)
   - Status badges (new/read/replied/archived/spam)
   - Actions (view, mark as read, mark as replied, archive, mark as spam, delete)
   - Bulk actions
   - Export to CSV

2. CONTACT SUBMISSION DETAIL MODAL:
   - All submission fields
   - IP address and user agent
   - Submission date/time
   - Email delivery status
   - Reply button (opens email client)
   - Mark as spam button
   - Archive button
   - Delete button
   - Status change dropdown

3. EMAIL SETTINGS PAGE (/admin/settings/email):
   - SMTP Configuration:
     * Provider selector (Gmail, SendGrid, AWS SES, Mailgun, Custom)
     * Host, port, username, password
     * Encryption (none/ssl/tls)
     * From address and name
     * Test email button
     * Connection status indicator
   - Recipients Management:
     * Add/remove recipients
     * Email, name, role
     * Primary recipient toggle
     * Active/inactive toggle
     * Notification preferences (immediate/daily/weekly)
   - Auto-Reply Settings:
     * Enable/disable toggle
     * Subject (bilingual)
     * Message (bilingual, rich text)
     * From address and name
   - Email Templates:
     * Template list
     * Edit template button
     * Preview button
     * Variables list

4. EMAIL TEMPLATE EDITOR MODAL:
   - Template name
   - Subject (bilingual)
   - Body (bilingual, HTML editor)
   - Variables insertion
   - Preview with sample data
   - Save button

5. EMAIL MONITORING PAGE (/admin/settings/email/monitoring):
   - Statistics cards:
     * Emails sent today/week/month
     * Success rate
     * Failure rate
     * Queue size
   - Charts:
     * Emails over time
     * Success vs failure
   - Failed emails list
   - Retry button
   - Clear queue button
   - Health status indicators

6. PUBLIC LANDING PAGES (SSR):
   
   A. HOME PAGE (/[locale]):
   - Hero section (from CMS)
   - Services section (featured services from CMS)
   - Projects section (featured projects from CMS)
   - CTA section
   - Animations and micro-interactions
   - SEO optimized
   
   B. ABOUT PAGE (/[locale]/about):
   - Hero section
   - Mission and vision
   - Company story
   - Core values
   - Statistics (with count-up animation)
   - Team section
   - SEO optimized
   
   C. SERVICES PAGE (/[locale]/services):
   - Hero section
   - All services (from CMS)
   - Service cards with icons
   - No detail pages (just cards)
   - SEO optimized
   
   D. PROJECTS PAGE (/[locale]/projects):
   - Hero section
   - Projects grid/list
   - Filter by category
   - Project cards with thumbnails
   - Project detail modal (or page)
   - SEO optimized
   
   E. BLOG PAGE (/[locale]/blog):
   - Hero section
   - Blog posts grid
   - Search and filters (category, tag)
   - Pagination
   - Featured posts
   - SEO optimized
   
   F. BLOG POST PAGE (/[locale]/blog/[slug]):
   - Post content (rich text)
   - Featured image
   - Author info
   - Publish date
   - Categories and tags
   - Related posts
   - Share buttons
   - JSON-LD structured data
   - Exceptional SEO
   
   G. CONTACT PAGE (/[locale]/contact):
   - Contact form:
     * Name (required)
     * Email (required)
     * Phone (optional)
     * Subject (optional)
     * Message (required)
     * Privacy policy checkbox (required)
     * Submit button
   - Rate limiting (5 per minute)
   - Success/error messages
   - Loading states
   - Contact information display
   - Map (optional)
   - SEO optimized

7. LAYOUT COMPONENTS:
   - Header component:
     * Logo
     * Navigation menu
     * Language switcher
     * Mobile menu
     * Sticky on scroll
   - Footer component:
     * Logo
     * Company info
     * Social media links
     * Quick links
     * Copyright (auto-update year)
   - SEO component (meta tags, OG tags, JSON-LD)

8. COMPONENTS:
   - ContactForm component
   - ServiceCard component
   - ProjectCard component
   - BlogPostCard component
   - HeroSection component
   - StatisticsCounter component
   - SocialShare component
   - Breadcrumbs component
   - BackToTop component

9. SERVICES:
   - services/contact.service.ts
   - services/public.service.ts
   - All public API calls

10. TYPES:
    - types/contact.ts
    - types/public.ts
    - All public content types

11. HOOKS:
    - useContactSubmissions() hook
    - useEmailSettings() hook
    - usePublicContent() hook
    - useContactForm() hook

12. TESTS:
    - Contact form tests
    - Rate limiting tests
    - Email settings tests
    - Public pages tests
    - SEO tests

CRITICAL BUSINESS RULES:
- Contact form rate limit: 5 per minute per IP
- NO CAPTCHA
- All public pages SSR for SEO
- Instant revalidation when CMS updated
- Bilingual (AR/EN) with RTL
- Black & white design with animations
- Blog SEO exceptional (JSON-LD, structured data)
- Auto-update copyright year
- All text in translation files

SECURITY:
- Rate limiting enforced
- Form validation
- XSS prevention
- CSRF protection

OUTPUT:
1. List all files created
2. How to test contact form
3. How to test public pages
4. How to test SEO
5. Any important notes

Generate complete Day 6 frontend code.
```

---

## 🚀 DAY 7: Dashboard, Testing & Deployment

**Copy this entire prompt:**

```
CONTEXT:
Frontend Developer - Day 7 of 7 (FINAL DAY). Days 1-6 complete. Today: Dashboard, testing, deployment.

READ THESE FILES:
- /tasks/10-seo-testing-deployment.md
- /user-stories/09-seo-technical-infrastructure.md
- /user-stories/10-quality-assurance-testing.md
- /docs/benchmarks_and_tests.md
- /docs/coding_rules.md
- /docs/meeting_notes.md

DAY 7 TASKS - DASHBOARD, TESTING & DEPLOYMENT:

1. ADMIN DASHBOARD (/admin/dashboard):
   - Welcome message with user name
   - Statistics cards:
     * Total team members
     * Total clients
     * Total projects
     * Active projects
     * Projects by status
     * Recent activities
   - Charts:
     * Projects over time
     * Projects by status (pie chart)
     * Projects by priority (bar chart)
     * Team workload (bar chart)
   - Recent activities feed
   - Quick actions (add team member, add client, add project)
   - Upcoming deadlines
   - Overdue projects alert

2. USER PROFILE PAGE (/admin/profile):
   - User information display
   - Profile picture
   - Edit profile button (if allowed)
   - Change password form
   - Activity history
   - Managed projects (if PM)

3. SETTINGS PAGE (/admin/settings):
   - General settings
   - Email settings (link to email settings page)
   - Notification preferences
   - Language preference
   - Theme preference (if dark mode added later)

4. NOTIFICATIONS SYSTEM:
   - Notification bell icon in header
   - Notification dropdown
   - Mark as read
   - Mark all as read
   - Notification types:
     * New team member
     * Project assigned
     * Project status changed
     * Project deadline approaching
     * Contact form submission
     * Email delivery failure

5. ERROR PAGES:
   - 404 Not Found page
   - 403 Forbidden page
   - 500 Server Error page
   - Offline page
   - All bilingual with RTL

6. LOADING STATES:
   - Page loading skeleton
   - Component loading states
   - Button loading states
   - Infinite scroll loading
   - Optimistic UI updates

7. ACCESSIBILITY:
   - Keyboard navigation
   - Focus indicators
   - ARIA labels
   - Alt text for images
   - Screen reader support
   - Color contrast (WCAG AA)

8. PERFORMANCE OPTIMIZATION:
   - Code splitting
   - Lazy loading components
   - Image optimization (Next.js Image)
   - API response caching
   - Debounce search inputs
   - Virtualized lists for large data

9. SEO OPTIMIZATION:
   - Dynamic meta tags on all pages
   - Open Graph tags
   - Twitter Card tags
   - JSON-LD structured data
   - Canonical URLs
   - Hreflang tags (AR/EN)
   - Sitemap.xml (generated by backend)
   - Robots.txt (generated by backend)

10. TESTING:
    
    A. UNIT TESTS:
    - All components
    - All hooks
    - All utilities
    - All services
    
    B. INTEGRATION TESTS:
    - Authentication flow
    - Team management flow
    - Client management flow
    - Project management flow
    - CMS operations flow
    - Blog system flow
    - Contact form flow
    
    C. E2E TESTS (Playwright or Cypress):
    - Complete user journeys
    - Login to dashboard
    - Create team member
    - Create client
    - Create project
    - Edit CMS content
    - Create blog post
    - Submit contact form
    
    D. ACCESSIBILITY TESTS:
    - axe-core tests
    - Keyboard navigation tests
    - Screen reader tests
    
    E. PERFORMANCE TESTS:
    - Lighthouse tests (target: 80+)
    - Core Web Vitals
    - Bundle size analysis

11. DEPLOYMENT CONFIGURATION:
    
    A. ENVIRONMENT VARIABLES:
    - NEXT_PUBLIC_API_URL
    - NEXT_PUBLIC_SITE_URL
    - REVALIDATION_SECRET
    
    B. BUILD OPTIMIZATION:
    - Production build
    - Bundle analysis
    - Image optimization
    - Font optimization
    
    C. VERCEL/SERVER DEPLOYMENT:
    - Configure deployment
    - Environment variables
    - Domain configuration
    - SSL certificate
    - CDN configuration

12. DOCUMENTATION:
    - Component documentation
    - API integration guide
    - Deployment guide
    - Environment variables guide
    - Testing guide

13. FINAL CHECKS:
    - All pages responsive (mobile, tablet, desktop)
    - All forms validated
    - All error states handled
    - All loading states implemented
    - All success messages shown
    - All translations complete (AR/EN)
    - RTL layout working
    - All images optimized
    - All links working
    - No console errors
    - No TypeScript errors
    - All tests passing
    - Lighthouse score 80+
    - Accessibility score 80+

CRITICAL BUSINESS RULES:
- All pages responsive
- All text bilingual (AR/EN) with RTL
- Zero hardcoded strings
- Black & white design
- Animations smooth (60fps)
- SEO optimized (Lighthouse 90+)
- Accessibility compliant (WCAG AA)
- Performance optimized (< 3s load time)

SECURITY:
- XSS prevention
- CSRF protection
- Secure token storage
- Input validation
- Rate limiting

OUTPUT:
1. List all files created
2. Test results summary (unit, integration, e2e, accessibility, performance)
3. Lighthouse scores (Performance, SEO, Accessibility, Best Practices)
4. Deployment checklist
5. Any critical issues found
6. Recommendations for production

Generate complete Day 7 frontend code and run all tests.
```

---

## ✅ COMPLETION CHECKLIST

After completing all 7 days, verify:

- [ ] All pages implemented and responsive
- [ ] All forms working with validation
- [ ] All CRUD operations working
- [ ] Authentication working
- [ ] Authorization working (all roles)
- [ ] Team management complete
- [ ] Client management complete
- [ ] Project management complete
- [ ] CMS complete (only CTO can access)
- [ ] Blog system complete
- [ ] Contact form working (rate limited)
- [ ] Public landing pages working (SSR)
- [ ] All pages bilingual (AR/EN)
- [ ] RTL layout working
- [ ] All translations complete
- [ ] Zero hardcoded strings
- [ ] All images optimized
- [ ] All tests passing
- [ ] Lighthouse Performance 80+
- [ ] Lighthouse SEO 90+
- [ ] Lighthouse Accessibility 80+
- [ ] Lighthouse Best Practices 80+
- [ ] No console errors
- [ ] No TypeScript errors
- [ ] Production build successful
- [ ] Deployment configured

---

## 🎉 YOU'RE DONE!

Frontend is complete! The full-stack Tarqumi CRM is ready for production.

**Next Steps:**
1. Push all code to GitHub
2. Deploy frontend to Vercel/server
3. Configure domain and SSL
4. Test production environment
5. Monitor performance and errors
6. Gather user feedback

---

**Last Updated:** 2024  
**Version:** 1.0  
**Status:** Ready for Development 🚀
