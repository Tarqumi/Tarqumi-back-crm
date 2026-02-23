# Tarqumi CRM – User Stories (Phase 1)

> **Format**: Each story follows the pattern:
> `As a [role], I want to [action], so that [benefit].`
>
> **Priority Levels**: 🔴 Critical | 🟠 High | 🟡 Medium | 🟢 Nice-to-have
>
> **Acceptance Criteria (AC)**: Numbered conditions that MUST be true for the story to be complete.
>
> **Edge Cases (EC)**: Scenarios that must be handled gracefully.

---

## Module 1: Authentication & Session Management

### US-1.1 🔴 Admin Login
**As an** Admin, **I want to** log in to the CRM system using my email and password, **so that** I can access the admin panel securely.

**Acceptance Criteria:**
1. Login page is accessible at `/login` (or configurable obscure slug)
2. No login button or link is visible on the public landing page
3. Email and password fields are present with proper validation
4. On successful login, user is redirected to the CRM dashboard
5. On failed login, a generic error message appears: "Invalid credentials" (don't reveal which field was wrong)
6. Auth token (Sanctum) is stored securely and included in all subsequent API requests
7. Login page works in both Arabic and English

**Edge Cases:**
- EC-1: User enters SQL injection payload (`' OR 1=1 --`) → rejected, no data leak
- EC-2: User enters XSS payload (`<script>alert(1)</script>`) → sanitized, not executed
- EC-3: User enters empty email/password → inline validation errors shown
- EC-4: User enters a valid email but wrong password 10+ times → rate limited (429 response)
- EC-5: User is already logged in and visits `/login` → redirected to dashboard

---

### US-1.2 🔴 First Admin Seed
**As a** system, **I want to** seed the first Super Admin account during database setup, **so that** there is always at least one admin who can bootstrap the system.

**Acceptance Criteria:**
1. Running `php artisan db:seed` creates a Super Admin with credentials from `.env` (or a secure default)
2. The seeded admin has full Super Admin privileges
3. If re-seeded, it does NOT create duplicates (idempotent)
4. Password is hashed with bcrypt — never stored in plaintext

---

### US-1.3 🔴 Logout
**As an** authenticated user, **I want to** log out of the CRM, **so that** my session is terminated and the system is secure.

**Acceptance Criteria:**
1. A "Logout" button is visible in the admin panel header/sidebar
2. Clicking logout invalidates the Sanctum token on the server
3. User is redirected to the login page
4. All subsequent API requests with the old token return 401 Unauthorized
5. Browser back button after logout does NOT show cached protected pages

---

### US-1.4 🟠 Password Reset
**As an** Admin, **I want to** reset a team member's password, **so that** they can regain access if they forget their credentials.

**Acceptance Criteria:**
1. Admin navigates to Team Management → selects a member → clicks "Reset Password"
2. Admin can set a new password for the member
3. The new password is hashed with bcrypt before saving
4. The member can immediately login with the new password
5. Old password no longer works

**Edge Cases:**
- EC-1: New password is too short (< 8 chars) → validation error
- EC-2: Admin tries to reset their own password → allowed
- EC-3: Non-admin tries to access this feature → 403 Forbidden

---

### US-1.5 🔴 Route Protection
**As a** system, **I want to** protect all CRM routes behind authentication, **so that** unauthenticated users cannot access internal data.

**Acceptance Criteria:**
1. Every CRM API endpoint requires a valid Sanctum token
2. Every CRM frontend page checks authentication on load
3. Unauthenticated access to any admin URL → redirect to `/login`
4. API calls without token → 401 JSON response
5. Expired tokens → 401 response, frontend redirects to login

---

### US-1.6 🔴 Role-Based Access Control
**As a** system, **I want to** enforce permissions based on user roles, **so that** each user can only access features they're authorized for.

**Acceptance Criteria:**
1. **Super Admin**: Full access to everything, can delete other admins
2. **Admin**: Full access except cannot delete Super Admin
3. **Founder (CTO)**: Can edit landing page content + view CRM data
4. **Founder (CEO/CFO)**: Can view CRM data, CANNOT edit landing page
5. **HR**: Can manage team members (create, edit, deactivate)
6. **Employee**: Can only view their own assigned tasks (Phase 1: read-only dashboard)
7. Unauthorized API requests return 403 Forbidden
8. Frontend hides UI elements the user cannot access (but backend still enforces)

**Edge Cases:**
- EC-1: User modifies frontend to show hidden buttons → backend rejects with 403
- EC-2: User role changes while logged in → permissions update on next API call
- EC-3: Last Super Admin cannot be deleted → system prevents it

---

## Module 2: Team Management

### US-2.1 🔴 Create Team Member
**As an** Admin, **I want to** add a new team member with their details and role, **so that** they can access the CRM system.

**Acceptance Criteria:**
1. Form fields: Name (required), Email (required, unique), Password (required, min 8 chars), Role (required dropdown)
2. Role options: Admin, Founder, Employee, HR
3. If "Founder" is selected → a sub-role dropdown appears: CEO, CTO, CFO
4. On save → member is created, password hashed, success toast shown
5. The new member can immediately log in
6. Admin sees the new member in the team list
7. All fields validated on both frontend and backend

**Edge Cases:**
- EC-1: Duplicate email → backend returns 422 "Email already exists"
- EC-2: Password with Arabic characters → accepted (no charset restriction)
- EC-3: Very long name (500+ chars) → truncated or rejected with max length validation
- EC-4: Creating a member with role "Founder" but no sub-role → validation error

---

### US-2.2 🟠 View Team List
**As an** Admin, **I want to** see a list of all team members with their roles and status, **so that** I can manage the team effectively.

**Acceptance Criteria:**
1. Table/list shows: Name, Email, Role (+ sub-role if founder), Status (active/inactive), Date Added
2. List is paginated (15 per page default)
3. Search by name or email
4. Filter by role and by status (active/inactive)
5. Sort by name, role, date added
6. Active members shown by default, toggle to see inactive

---

### US-2.3 🟠 Edit Team Member
**As an** Admin, **I want to** edit a team member's details (name, email, role), **so that** I can keep team information up to date.

**Acceptance Criteria:**
1. Click on a member → edit form opens with pre-filled data
2. Can edit: Name, Email, Role/Sub-role
3. Cannot edit: Password from this form (use separate reset)
4. On save → changes persisted, success toast, list updated
5. If role changed from PM-eligible to non-PM → check if they're managing projects

**Edge Cases:**
- EC-1: Changing email to one already in use → 422 validation error
- EC-2: Employee tries to edit their own profile → blocked (403)
- EC-3: Editing a member who is currently logged in → their permissions update on next request

---

### US-2.4 🟠 Delete Team Member
**As an** Admin, **I want to** delete a team member, **so that** they no longer have access to the system.

**Acceptance Criteria:**
1. Confirmation dialog appears: "Are you sure you want to delete [Name]?"
2. If member is a Project Manager on any project → system shows: "This member manages X projects. Please reassign them before deleting." with a reassignment form
3. On confirm → member is soft-deleted, can no longer login
4. Deleted member's data is preserved in the database (for historical reference)
5. Token invalidated immediately

**Edge Cases:**
- EC-1: Trying to delete the default Super Admin → blocked with error
- EC-2: Trying to delete yourself → blocked: "You cannot delete your own account"
- EC-3: Only Super Admin can delete other Admins → regular Admin gets 403
- EC-4: Member manages 5 projects → must reassign all 5 before deletion proceeds

---

### US-2.5 🟡 Auto-Inactive After 30 Days
**As a** system, **I want to** automatically mark team members as inactive if they haven't logged in for 30 days, **so that** the team list stays current.

**Acceptance Criteria:**
1. A scheduled job (Laravel Scheduler / Cron) runs daily
2. Checks `last_login_at` for all active members
3. If `last_login_at` is > 30 days ago → status set to "inactive"
4. Inactive members can still log in (logging in reactivates them)
5. Admin can manually reactivate a member
6. Admin receives no notification (just status change) — notifications are Phase 2

---

## Module 3: Client Management

### US-3.1 🔴 Create Client
**As an** Admin, **I want to** add a new client to the system, **so that** I can link them to projects.

**Acceptance Criteria:**
1. Form fields: Name (required), Company Name (optional), Email (required, valid format), Phone (optional), WhatsApp Number (optional)
2. On save → client created, success toast, appears in client list
3. All inputs validated (email format, phone format, max lengths)
4. Client is "active" by default

**Edge Cases:**
- EC-1: Client with same email already exists → allow it (different clients can share emails, e.g., same company)
- EC-2: WhatsApp number with country code (+966...) → accepted
- EC-3: Arabic name input → accepted and rendered correctly in RTL
- EC-4: SQL injection in company name → sanitized and safely stored

---

### US-3.2 🟠 View Client List
**As an** Admin, **I want to** see all clients with their details, **so that** I can manage client relationships.

**Acceptance Criteria:**
1. Table shows: Name, Company, Email, Phone, WhatsApp, Status, Number of linked projects
2. Paginated (15 per page)
3. Search by name, company, or email
4. Filter by status (active/inactive)
5. Sort by name, company, date added
6. Click on client → view/edit details

---

### US-3.3 🟠 Edit Client
**As an** Admin, **I want to** update a client's information, **so that** their data stays current.

**Acceptance Criteria:**
1. All fields editable: Name, Company, Email, Phone, WhatsApp
2. Pre-filled with current values
3. On save → updated, success toast
4. Changes reflected everywhere client is referenced (project views, etc.)

---

### US-3.4 🟠 Delete Client
**As an** Admin, **I want to** delete a client, **so that** I can remove outdated client records.

**Acceptance Criteria:**
1. Confirmation dialog: "Are you sure? This client is linked to X projects. The projects will NOT be deleted."
2. On confirm → client soft-deleted
3. Linked projects remain — their client reference is set to NULL (or shows "Deleted client")
4. Client no longer appears in active client list or in new project client dropdowns

**Edge Cases:**
- EC-1: Trying to delete the default "Tarqumi" client → blocked: "The default client cannot be deleted"
- EC-2: Client is linked to 10 projects → all 10 projects survive, client column shows empty/null
- EC-3: Deleting a client then creating a new one with same email → allowed

---

### US-3.5 🔴 Default "Tarqumi" Client
**As a** system, **I want to** ensure there is always a default "Tarqumi" client that cannot be removed, **so that** internal projects always have a client.

**Acceptance Criteria:**
1. Seeded during `db:seed` — name: "Tarqumi"
2. Cannot be deleted by any user (including Super Admin)
3. Delete button is hidden/disabled for this client (and backend rejects if attempted)
4. Can be edited (e.g., update email or phone) but NOT renamed
5. When creating a project without selecting a client → defaults to "Tarqumi"

---

### US-3.6 🟡 Toggle Client Active/Inactive
**As an** Admin, **I want to** mark a client as active or inactive, **so that** I can filter out clients I'm no longer working with.

**Acceptance Criteria:**
1. Toggle switch or button on client detail/list
2. Inactive clients are hidden from the default list view (toggle to show)
3. Inactive clients are still available in project views (for existing links)
4. Inactive clients do NOT appear in the "assign client to project" dropdown

---

## Module 4: Project Management (Internal)

### US-4.1 🔴 Create Project
**As an** Admin, **I want to** create a new internal project with all relevant details, **so that** I can track work for the team.

**Acceptance Criteria:**
1. Form fields:
   - Project Name (required, max 255 chars)
   - Description (optional, rich text or textarea)
   - Client(s) (multi-select dropdown from active clients, defaults to "Tarqumi" if none selected)
   - Project Manager (dropdown from active team members, required)
   - Budget (number input, min 0, no max, required)
   - Currency (dropdown with standard currencies: USD, EUR, GBP, SAR, AED, EGP, KWD, QAR, BHD, OMR, JOD, TRY, INR, CNY, JPY, CAD, AUD, CHF, SEK, NOK — required)
   - Priority (slider 1-10, required, default 5)
   - Start Date (date picker, required)
   - End Date (date picker, required, must be ≥ start date)
   - Status (dropdown: Planning, Analysis, Design, Implementation, Testing, Deployment — default: Planning)
2. On save → project created as "active", success toast
3. Appears in project list

**Edge Cases:**
- EC-1: End date before start date → validation error: "End date must be on or after start date"
- EC-2: Budget of 0 → allowed (e.g., internal/pro-bono project)
- EC-3: Selecting 5 clients → all 5 linked via pivot table
- EC-4: Project manager who gets deleted later → handled by US-2.4 reassignment
- EC-5: Very large budget (999,999,999.99) → accepted, formatted with commas

---

### US-4.2 🟠 View Project List
**As an** Admin, **I want to** see all active projects at a glance, **so that** I can monitor project progress.

**Acceptance Criteria:**
1. Table/cards show: Name, Client(s), PM, Status, Priority, Budget+Currency, Start/End Dates
2. Only **active** projects shown by default
3. Toggle to show inactive projects
4. Paginated (15 per page)
5. Search by project name or client name
6. Filter by: status (6 SDLC phases), priority range, project manager, client
7. Sort by: name, priority, start date, end date, status, budget
8. Priority displayed visually (color-coded: 1-3 green, 4-6 yellow, 7-10 red)

---

### US-4.3 🟠 View Project Details
**As an** Admin, **I want to** view a single project's full details, **so that** I can see all information in one place.

**Acceptance Criteria:**
1. Shows all project fields with formatted values
2. Client names are clickable links to client details
3. PM name is a clickable link to member details
4. Budget displayed with currency symbol and thousands separator
5. Status shown with visual indicator (badge/progress bar)
6. Priority shown with visual indicator (color + number)
7. Dates formatted according to locale (AR/EN)

---

### US-4.4 🟠 Edit Project
**As an** Admin, **I want to** update a project's details, **so that** I can keep project information current.

**Acceptance Criteria:**
1. All fields editable (same validation rules as creation)
2. Pre-filled with current values
3. Can change status through SDLC phases (no restriction on sequence — admin might need to go back)
4. Can add/remove clients from the project
5. Can change PM
6. On save → updated, success toast

**Edge Cases:**
- EC-1: Changing PM to a member who was just deleted → dropdown only shows active members
- EC-2: Removing all clients → defaults back to "Tarqumi"
- EC-3: Changing dates so end < start → validation error

---

### US-4.5 🟠 Toggle Project Active/Inactive
**As an** Admin, **I want to** mark a project as inactive, **so that** completed or paused projects don't clutter the active view.

**Acceptance Criteria:**
1. Toggle switch/button on project detail page
2. Inactive projects disappear from the default project list
3. Toggle "Show inactive" to view them
4. Inactive projects can be reactivated
5. No "archive" feature — just active/inactive

---

## Module 5: Landing Page Content Management (CMS)

### US-5.1 🔴 Edit Page SEO Settings
**As an** Admin (or CTO), **I want to** edit the SEO title, description, keywords, and OG image for each landing page, **so that** the site ranks well in search engines.

**Acceptance Criteria:**
1. Navigate to CMS → select page (Home, About, Services, Projects, Blog, Contact)
2. Edit fields: Title (AR + EN), Description (AR + EN), Keywords (AR + EN), OG Image (upload)
3. Title shows character count with warning at 60 chars
4. Description shows character count with warning at 160 chars
5. On save → data persisted, on-demand revalidation triggered, changes reflected on landing page immediately
6. Preview: admin can see how the page will look in Google search results

**Edge Cases:**
- EC-1: Title exceeds 60 chars → warning shown but save still allowed (soft limit)
- EC-2: OG image > 20MB → rejected with file size error
- EC-3: OG image is .exe file → rejected, only image formats accepted (jpg, png, webp, svg)

---

### US-5.2 🔴 Edit Home Page Content
**As an** Admin, **I want to** edit the home page hero text, service preview cards, and featured projects, **so that** visitors see the latest company information.

**Acceptance Criteria:**
1. Hero section: editable title (AR + EN), subtitle (AR + EN), CTA button text (AR + EN)
2. Service cards section: displays 6 cards pulled from services (auto from top 6 active services)
3. "Read More" button links to `/services`
4. Featured projects: displays 3 projects (auto from top 3 active showcase projects)
5. "Read More" button links to `/projects`
6. On save → instant revalidation, changes live immediately

---

### US-5.3 🔴 Edit About Page
**As an** Admin, **I want to** edit the About page content, **so that** visitors understand who Tarqumi is.

**Acceptance Criteria:**
1. Editable sections: Hero title (AR + EN), Hero subtitle (AR + EN), Body content (AR + EN), Company image/team photo
2. Each field saved per language
3. Instant revalidation on save

---

### US-5.4 🔴 Manage Service Cards (CRUD)
**As an** Admin, **I want to** create, edit, reorder, and delete service cards, **so that** the Services page accurately represents our offerings.

**Acceptance Criteria:**
1. Each card has: Icon (upload or URL), Title (AR + EN), Description (AR + EN)
2. **Create**: Add new service card with all fields → appears on Services page
3. **Edit**: Change any field on existing card → instant update
4. **Delete**: Remove card with confirmation → disappears from page
5. **Reorder**: Drag-and-drop or order number to set display sequence
6. No "detail page" per service — just cards
7. Home page automatically shows top 6 active services

**Edge Cases:**
- EC-1: Deleting a service that's in the top 6 → home page auto-adjusts to next available
- EC-2: Icon uploaded as .php → rejected, only image formats allowed
- EC-3: Only 2 services exist → home page shows 2, no empty slots

---

### US-5.5 🔴 Manage Showcase Projects (CRUD)
**As an** Admin, **I want to** manage the project showcase on the landing page, **so that** we display our best work to potential clients.

**Acceptance Criteria:**
1. Each showcase project: Name (AR + EN), Description (AR + EN), URL/Domain, Image/Screenshot, Live Status (yes/no), Active Status (show/hide)
2. **Create**: Add new showcase project → appears on Projects page if active
3. **Edit**: Update any field → instant update
4. **Delete**: Remove with confirmation
5. **Active toggle**: Only active projects appear on landing page
6. Home page shows top 3 active showcase projects
7. These are SEPARATE from internal CRM projects — different database table

---

### US-5.6 🔴 Edit Footer Content
**As an** Admin, **I want to** edit the footer text, logo, email, and social media links, **so that** visitors have up-to-date contact information.

**Acceptance Criteria:**
1. Editable fields: Footer text/tagline (AR + EN), Logo (image upload), Contact email, Social media links
2. Social media: Admin provides platform name + URL — only configured ones display
3. Supported platforms: Facebook, Twitter/X, Instagram, LinkedIn, YouTube, TikTok, GitHub, WhatsApp, Telegram
4. Copyright year auto-updates (© 2026 Tarqumi)
5. Adding a social link → it appears with the correct icon
6. Removing a social link → it disappears immediately

---

### US-5.7 🟠 Configure Contact Form Emails
**As an** Admin, **I want to** set which email address(es) receive contact form submissions, **so that** the right people get notified.

**Acceptance Criteria:**
1. Navigate to CMS → Contact Settings
2. Add one or more email addresses
3. Each email validated for format
4. When a visitor submits the contact form, ALL configured emails receive the submission
5. Can add, edit, or remove recipient emails at any time
6. At least one email must always be configured (cannot remove the last one)

---

### US-5.8 🔴 Instant Content Revalidation
**As an** Admin, **I want** changes I make in the CMS to appear immediately on the public website, **so that** visitors always see the latest content.

**Acceptance Criteria:**
1. After saving ANY CMS change, the system triggers Next.js on-demand revalidation
2. The landing page regenerates the affected page(s)
3. Within seconds, the public page shows the new content
4. No manual cache clearing required
5. Revalidation is targeted (only the changed page, not the entire site)

---

## Module 6: Blog System

### US-6.1 🔴 Create Blog Post
**As an** Admin (or CTO), **I want to** create a new blog post in both Arabic and English, **so that** I can publish content that attracts visitors and improves SEO.

**Acceptance Criteria:**
1. Form fields per language (AR + EN tabs):
   - Title (required, max 255 chars)
   - Slug (auto-generated from title, editable, URL-safe)
   - Excerpt/Description (required, max 300 chars — used for meta description)
   - Content (rich text editor — headings, paragraphs, images, links, bold, italic, lists)
   - Featured Image (upload, max 20MB)
   - SEO Title (optional, defaults to post title)
   - SEO Description (optional, defaults to excerpt)
   - SEO Keywords
2. Status: Draft / Published
3. Publish Date: auto-set to now, or scheduled for future
4. Author: auto-set to current user
5. On publish → appears on landing page Blog section
6. On-demand revalidation triggered
7. Blog URL format: `/en/blog/slug-en`, `/ar/blog/slug-ar`

**Edge Cases:**
- EC-1: Slug with Arabic characters → transliterated to Latin for URL safety
- EC-2: Duplicate slug → auto-append number (e.g., `my-post-2`)
- EC-3: Content with `<script>` tags → sanitized/stripped
- EC-4: Publishing without English content → save as draft, warn "Both languages required for publish"

---

### US-6.2 🟠 Edit Blog Post
**As an** Admin, **I want to** edit an existing blog post, **so that** I can fix errors or update content.

**Acceptance Criteria:**
1. All fields editable (same as creation)
2. Changing slug updates the URL (old URL should 301 redirect — nice-to-have)
3. Changing content triggers revalidation
4. Edit history not tracked (Phase 3 feature)

---

### US-6.3 🟠 Delete Blog Post
**As an** Admin, **I want to** delete a blog post, **so that** outdated content is removed.

**Acceptance Criteria:**
1. Confirmation dialog: "Are you sure you want to delete this post?"
2. Post soft-deleted — removed from public view
3. Blog list and sitemap updated
4. Revalidation triggered

---

### US-6.4 🟠 View Blog List (Admin)
**As an** Admin, **I want to** see all blog posts with their status, **so that** I can manage content.

**Acceptance Criteria:**
1. Table: Title, Author, Status (Draft/Published), Publish Date, Views (if tracked)
2. Filter by status
3. Search by title
4. Sort by date, title
5. Paginated

---

### US-6.5 🔴 Blog SEO Requirements
**As a** system, **I want** every blog post to have maximum SEO optimization, **so that** blog content ranks high in search results.

**Acceptance Criteria:**
1. Each blog page generates: unique `<title>`, `<meta description>`, `<meta keywords>`
2. JSON-LD structured data with `Article` schema: headline, author, datePublished, dateModified, image
3. Open Graph tags: og:title, og:description, og:image, og:url, og:type=article
4. Twitter Card tags
5. `<time datetime="...">` for publish/update dates
6. Proper heading hierarchy: `<h1>` for title, `<h2>` for sections
7. All images have descriptive `alt` attributes
8. Clean URL: `/en/blog/descriptive-slug`
9. `<link rel="canonical">` pointing to self
10. `<link rel="alternate" hreflang="ar">` and `hreflang="en">` for bilingual versions
11. Blog posts included in `sitemap.xml`

---

## Module 7: Contact Form (Public)

### US-7.1 🔴 Submit Contact Form
**As a** website visitor, **I want to** send a message through the contact form, **so that** I can reach Tarqumi for inquiries.

**Acceptance Criteria:**
1. Contact page shows form: Name (required), Email (required, valid format), Phone (optional), Message (required, min 10 chars)
2. Real-time inline validation on all fields
3. On submit → loading spinner, then success message: "Your message has been sent successfully!"
4. Email sent via SMTP to all admin-configured recipient emails
5. Submission stored in database (`contact_submissions` table)
6. Form resets after successful submission
7. Works in both Arabic and English

**Edge Cases:**
- EC-1: Network error during submit → show "Something went wrong. Please try again."
- EC-2: User submits 6 times in 1 minute → 6th attempt shows "Too many messages. Please wait a minute."
- EC-3: SQL injection in message field → safely stored via Eloquent
- EC-4: XSS in name field → sanitized before display anywhere
- EC-5: Very long message (10,000+ chars) → max length validated
- EC-6: Phone number with special chars (+, -, spaces) → accepted and stored as-is
- EC-7: Email in Arabic domain (user@مثال.com) → validated as valid or rejected gracefully

---

### US-7.2 🟠 View Contact Submissions (Admin)
**As an** Admin, **I want to** view all contact form submissions, **so that** I can follow up with potential clients.

**Acceptance Criteria:**
1. Admin panel section: "Contact Submissions" or "Inbox"
2. Table: Name, Email, Phone, Message (truncated), Date, Read/Unread status
3. Click to expand full message
4. Mark as read/unread
5. Paginated, sortable by date
6. Search by name or email
7. Delete submissions (with confirmation)

---

## Module 8: Public Landing Page (Visitor Experience)

### US-8.1 🔴 Home Page Visit
**As a** website visitor, **I want to** land on an impressive home page, **so that** I understand what Tarqumi does and want to learn more.

**Acceptance Criteria:**
1. Hero section loads with dynamic text and subtle animations
2. Particle/atom background effects (or equivalent micro-interactions)
3. ~6 service cards with icons, titles, descriptions + "See All Services" link
4. ~3 featured project cards + "See All Projects" link
5. Contact CTA section: "Get in touch" with button linking to Contact page
6. All text comes from admin CMS (no hardcoded content)
7. Page renders via SSR — full HTML in page source
8. Page loads in < 3 seconds
9. Animations are smooth (60fps)
10. Fully responsive (mobile 375px → desktop 1440px+)

---

### US-8.2 🔴 Language Switching
**As a** website visitor, **I want to** switch between Arabic and English, **so that** I can read the site in my preferred language.

**Acceptance Criteria:**
1. Language switcher visible in the header (flag icon or AR/EN text toggle)
2. Clicking switches the language AND updates the URL (`/en/about` ↔ `/ar/about`)
3. Layout direction changes: LTR for English, RTL for Arabic
4. `<html lang="en" dir="ltr">` or `<html lang="ar" dir="rtl">` correctly set
5. All text, navigation, footer, and form labels switch language
6. User's language preference persisted (cookie or localStorage)
7. Default language: Arabic (or based on browser language detection)
8. All navigation links maintain the current locale prefix

**Edge Cases:**
- EC-1: User visits `/about` without locale → redirect to `/ar/about` (or detect browser lang)
- EC-2: Page content missing in one language → show available language with notice
- EC-3: Blog post exists only in Arabic → English version shows "Not available in English" or redirect

---

### US-8.3 🟠 Navigation Experience
**As a** visitor, **I want** clear navigation to all pages, **so that** I can find information easily.

**Acceptance Criteria:**
1. Header nav: Home, About, Services, Projects, Blog, Contact
2. Mobile: hamburger menu with slide-in nav panel
3. Active page is highlighted in the nav
4. Smooth scroll to sections on home page (if applicable)
5. Logo in header → links to home page
6. Footer nav mirrors main nav
7. All links maintain current language (AR/EN)

---

### US-8.4 🟠 Services Page
**As a** visitor, **I want to** see all services Tarqumi offers, **so that** I can decide if they match my needs.

**Acceptance Criteria:**
1. All active service cards displayed in a grid
2. Each card: Icon, Title, Description
3. Cards have hover animations
4. Responsive grid: 3 columns desktop, 2 tablet, 1 mobile
5. Content loaded from DB via SSR

---

### US-8.5 🟠 Projects Page
**As a** visitor, **I want to** browse Tarqumi's project portfolio, **so that** I can see their work quality.

**Acceptance Criteria:**
1. All active showcase projects displayed
2. Each project: Image, Name, Description, "Visit" button (if URL provided)
3. Live status indicator (if the project is currently live)
4. Hover effects on project cards
5. Responsive layout

---

### US-8.6 🟠 Blog Listing Page
**As a** visitor, **I want to** browse blog articles, **so that** I can read Tarqumi's content.

**Acceptance Criteria:**
1. Published posts displayed in grid/list format
2. Each post: Featured Image, Title, Excerpt, Publish Date, Author
3. Click → full blog post page
4. Pagination (10 per page)
5. SEO-friendly pagination (prev/next links, canonical URLs)

---

### US-8.7 🟠 Blog Post Page
**As a** visitor, **I want to** read a full blog post, **so that** I can learn from Tarqumi's expertise.

**Acceptance Criteria:**
1. Full article content rendered with proper heading hierarchy
2. Featured image at top
3. Author name and publish date
4. All SEO tags present (from US-6.5)
5. Share buttons (optional nice-to-have)
6. Related posts section (optional)
7. `hreflang` links to the other language version

---

### US-8.8 🟠 About Page
**As a** visitor, **I want to** learn about Tarqumi, **so that** I can trust them with my project.

**Acceptance Criteria:**
1. Company info, mission, team photo (if provided)
2. All content from admin CMS
3. Animations/visual elements consistent with site design
4. SSR rendered

---

### US-8.9 🔴 Footer
**As a** visitor, **I want to** find contact info and social links in the footer, **so that** I can connect with Tarqumi.

**Acceptance Criteria:**
1. Logo displayed
2. Contact email clickable (`mailto:`)
3. Social media icons with links (only configured ones appear)
4. Copyright: "© 2026 Tarqumi" (auto-updates year)
5. All content from admin CMS
6. Consistent across all pages

---

## Module 9: SEO & Technical Infrastructure

### US-9.1 🔴 Server-Side Rendering
**As a** system, **I want** all landing pages rendered on the server, **so that** search engines can index all content.

**Acceptance Criteria:**
1. `curl` any landing page → full HTML including all text content (not empty `<div id="root">`)
2. Meta tags present in source
3. SSR for: Home, About, Services, Projects, Blog (list + individual), Contact
4. CRM admin pages can be client-side rendered (no SEO needed)

---

### US-9.2 🔴 Dynamic Sitemap
**As a** system, **I want** an auto-generated `sitemap.xml`, **so that** search engines discover all pages.

**Acceptance Criteria:**
1. Accessible at `/sitemap.xml`
2. Includes all pages in both languages (12+ URLs minimum)
3. Includes all published blog posts (both AR and EN URLs)
4. Includes all active showcase project pages (if they have dedicated pages)
5. Updates automatically when blogs are added/removed
6. Excludes admin/CRM pages
7. Proper `<lastmod>` dates

---

### US-9.3 🟠 Robots.txt
**As a** system, **I want** a `robots.txt` that guides search engines, **so that** only public pages are crawled.

**Acceptance Criteria:**
1. Accessible at `/robots.txt`
2. Allows crawling of all public pages
3. Disallows: `/login`, `/admin`, `/api`
4. References sitemap URL

---

### US-9.4 🟠 Hreflang Tags
**As a** system, **I want** proper `hreflang` tags on every page, **so that** Google serves the right language to each user.

**Acceptance Criteria:**
1. Every page has `<link rel="alternate" hreflang="ar" href="/ar/...">`
2. Every page has `<link rel="alternate" hreflang="en" href="/en/...">`
3. Blog posts link to their bilingual counterpart

---

## Module 10: File Upload System

### US-10.1 🔴 Image Upload
**As an** Admin, **I want to** upload images for logos, OG images, blog posts, service icons, and project screenshots, **so that** the site has visual content.

**Acceptance Criteria:**
1. Upload accepts: JPG, JPEG, PNG, WebP, SVG, GIF
2. Maximum file size: 20MB
3. Files stored in Laravel's local storage (`storage/app/public/uploads/`)
4. Files served via public URL
5. Upload shows progress indicator
6. After upload → preview shown
7. Previous image can be replaced

**Edge Cases:**
- EC-1: Upload .exe disguised as .jpg → server validates MIME type, rejects
- EC-2: Upload 25MB file → rejected: "Maximum file size is 20MB"
- EC-3: Upload file with Arabic filename → filename sanitized/renamed
- EC-4: Concurrent uploads → each gets unique filename (UUID-based)
- EC-5: Storage full → graceful error message

---

## Cross-Cutting Concerns

### US-CC.1 🔴 Consistent API Responses
**As a** developer, **I want** all API endpoints to return a consistent JSON structure, **so that** the frontend can handle responses predictably.

**Acceptance Criteria:**
1. Success: `{ "success": true, "data": {...}, "message": "..." }`
2. Error: `{ "success": false, "message": "...", "errors": {...} }`
3. Proper HTTP status codes (200, 201, 204, 400, 401, 403, 404, 422, 429, 500)
4. Validation errors include field-level details
5. No stack traces in production error responses

---

### US-CC.2 🔴 Bilingual System
**As a** user (admin or visitor), **I want** the entire system to work in Arabic and English, **so that** I can use it in my language.

**Acceptance Criteria:**
1. Landing page: fully bilingual with RTL/LTR
2. Admin panel: fully bilingual with RTL/LTR
3. All text from i18n translation files (zero hardcoded strings)
4. Validation error messages in the active language
5. Email notifications in the recipient's language (or default language)
6. Dates formatted per locale
7. Numbers formatted per locale

---

### US-CC.3 🟠 Loading, Error & Empty States
**As a** user, **I want to** see appropriate feedback for loading, errors, and empty data, **so that** I always know what's happening.

**Acceptance Criteria:**
1. **Loading**: Skeleton loaders or spinners during API calls
2. **Error**: User-friendly error message with retry option
3. **Empty**: Contextual message ("No projects yet. Create your first project!" with CTA button)
4. **Success**: Toast/notification for create, update, delete actions
5. **Confirmation**: Modal dialog before destructive actions (delete)

---

### US-CC.4 🟠 Responsive Design
**As a** visitor, **I want** the website to look great on any device, **so that** I can browse from phone, tablet, or desktop.

**Acceptance Criteria:**
1. Mobile-first CSS approach
2. Breakpoints: 375px (mobile), 768px (tablet), 1024px (laptop), 1440px (desktop)
3. No horizontal scroll on any breakpoint
4. Text readable without zooming on mobile
5. Touch targets ≥ 44px on mobile
6. Images scale properly
7. Navigation collapses to hamburger on mobile

---

## Summary

| Module | Stories | Priority Breakdown |
|--------|---------|-------------------|
| 1. Authentication | 6 stories | 4🔴 1🟠 1🟡 |
| 2. Team Management | 5 stories | 1🔴 3🟠 1🟡 |
| 3. Client Management | 6 stories | 2🔴 3🟠 1🟡 |
| 4. Project Management | 5 stories | 1🔴 4🟠 |
| 5. Landing Page CMS | 8 stories | 5🔴 2🟠 1🟡 |
| 6. Blog System | 5 stories | 2🔴 3🟠 |
| 7. Contact Form | 2 stories | 1🔴 1🟠 |
| 8. Public Pages | 9 stories | 3🔴 6🟠 |
| 9. SEO & Technical | 4 stories | 2🔴 2🟠 |
| 10. File Upload | 1 story | 1🔴 |
| Cross-Cutting | 4 stories | 2🔴 2🟠 |
| **TOTAL** | **55 stories** | **24🔴 27🟠 4🟡** |
