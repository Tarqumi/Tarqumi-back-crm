---
inclusion: manual
priority: 10
---

# User Stories Reference (Phase 1)

This file contains references to all 55 user stories for Phase 1.
For full details, see `docs/user_stories.md`.

## Module Summary

### 1. Authentication & Session Management (6 stories)
- US-1.1 🔴 Admin Login
- US-1.2 🔴 First Admin Seed
- US-1.3 🔴 Logout
- US-1.4 🟠 Password Reset
- US-1.5 🔴 Route Protection
- US-1.6 🔴 Role-Based Access Control

### 2. Team Management (5 stories)
- US-2.1 🔴 Create Team Member
- US-2.2 🟠 View Team List
- US-2.3 🟠 Edit Team Member
- US-2.4 🟠 Delete Team Member
- US-2.5 🟡 Auto-Inactive After 30 Days

### 3. Client Management (6 stories)
- US-3.1 🔴 Create Client
- US-3.2 🟠 View Client List
- US-3.3 🟠 Edit Client
- US-3.4 🟠 Delete Client
- US-3.5 🔴 Default "Tarqumi" Client
- US-3.6 🟡 Toggle Client Active/Inactive

### 4. Project Management (5 stories)
- US-4.1 🔴 Create Project
- US-4.2 🟠 View Project List
- US-4.3 🟠 View Project Details
- US-4.4 🟠 Edit Project
- US-4.5 🟠 Toggle Project Active/Inactive

### 5. Landing Page CMS (8 stories)
- US-5.1 🔴 Edit Page SEO Settings
- US-5.2 🔴 Edit Home Page Content
- US-5.3 🔴 Edit About Page
- US-5.4 🔴 Manage Service Cards (CRUD)
- US-5.5 🔴 Manage Showcase Projects (CRUD)
- US-5.6 🔴 Edit Footer Content
- US-5.7 🟠 Configure Contact Form Emails
- US-5.8 🔴 Instant Content Revalidation

### 6. Blog System (5 stories)
- US-6.1 🔴 Create Blog Post
- US-6.2 🟠 Edit Blog Post
- US-6.3 🟠 Delete Blog Post
- US-6.4 🟠 View Blog List (Admin)
- US-6.5 🔴 Blog SEO Requirements

### 7. Contact Form (2 stories)
- US-7.1 🔴 Submit Contact Form
- US-7.2 🟠 View Contact Submissions (Admin)

### 8. Public Landing Page (9 stories)
- US-8.1 🔴 Home Page Visit
- US-8.2 🔴 Language Switching
- US-8.3 🟠 Navigation Experience
- US-8.4 🟠 Services Page
- US-8.5 🟠 Projects Page
- US-8.6 🟠 Blog List Page
- US-8.7 🟠 Blog Post Page
- US-8.8 🟠 About Page
- US-8.9 🔴 Footer

### 9. SEO & Technical (4 stories)
- US-9.1 🔴 Server-Side Rendering
- US-9.2 🔴 Dynamic Sitemap
- US-9.3 🟠 Robots.txt
- US-9.4 🟠 Hreflang Tags

### 10. File Upload (1 story)
- US-10.1 🔴 Image Upload

### Cross-Cutting Concerns (4 stories)
- US-CC.1 🔴 Consistent API Responses
- US-CC.2 🔴 Bilingual System
- US-CC.3 🟠 Loading, Error & Empty States
- US-CC.4 🟠 Responsive Design

## Priority Breakdown
- 🔴 Critical: 24 stories
- 🟠 High: 27 stories
- 🟡 Medium: 4 stories

## Key Business Rules to Remember

### Default Client "Tarqumi"
- Cannot be deleted
- Can be edited (email, phone) but NOT renamed
- Projects without client default to "Tarqumi"

### Project Manager Deletion
- Must reassign all projects before deletion
- System shows: "This member manages X projects"
- Admin selects new PM for each

### 30-Day Inactivity
- Scheduled job runs daily
- Members inactive 30+ days → auto-marked inactive
- Logging in reactivates them

### Landing Page Edit Permission
- Only Admin, Super Admin, and CTO can edit
- CEO, CFO, HR, Employee cannot

### Super Admin Protection
- Only Super Admin can delete other Admins
- Last Super Admin cannot be deleted

### Employee Profile
- Employee cannot edit their own profile
- Only admins can manage employee data

### Multiple Clients per Project
- Projects can have multiple clients
- Stored in pivot table `client_project`

### Project Statuses (6 SDLC Phases)
1. Planning
2. Analysis
3. Design
4. Implementation
5. Testing
6. Deployment

### Priority Scale
- 1-10 slider (not High/Medium/Low)
- Visual indicators: 1-3 green, 4-6 yellow, 7-10 red

### Contact Form Rate Limit
- 5 submissions per minute
- 6th attempt → 429 Too Many Requests
- No CAPTCHA

### Contact Submissions (Admin Inbox)
- Admin can view all submissions with read/unread status
- Admin can search by name/email, sort by date
- Admin can delete submissions (with confirmation)

### Supported Currencies
- 20 currencies: USD, EUR, GBP, SAR, AED, EGP, KWD, QAR, BHD, OMR, JOD, TRY, INR, CNY, JPY, CAD, AUD, CHF, SEK, NOK

### Phase 1 Permission Scope
- Only Admin/Super Admin can CRUD clients and projects
- All Founder roles can VIEW CRM data but not modify
- CTO is the only Founder who can edit landing page
- HR can only manage team members (create, edit, deactivate)
- Employee has read-only access to own tasks

### Blog SEO
- Maximum optimization required
- JSON-LD Article schema
- Proper meta tags
- Clean URLs (slugified)
- Bilingual with hreflang

### Instant Revalidation
- Admin saves CMS change
- Next.js on-demand revalidation triggered
- Changes visible within seconds
- No manual cache clearing
