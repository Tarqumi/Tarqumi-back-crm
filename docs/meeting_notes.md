# Tarqumi CRM – Meeting Notes (Updated with Q&A)

**Date:** Extracted from meeting transcript  
**Subject:** Building a CRM system + Landing Page for Tarqumi  
**Attendees:** Project lead (speaker), development team (Hassan + others)  
**Last Updated:** 2026-02-17

---

## 1. Project Overview

**Tarqumi** (ترقمي) is a **software development company** that builds websites, systems, and mobile apps for businesses across any industry. Team size is approximately **10–50 people**.

The project is a **CRM (Customer Relationship Management) system** for Tarqumi. The current system has many bugs and issues, so this is a **complete rebuild from scratch** — no code from the old system will be reused.

The old CRM was built in ~1 day to rush it out, resulting in many bugs, errors, and a lack of proper analysis, so it was failing. This rebuild is meant to do it properly.

The project is **internal** (not going to an external client yet), so there's room for mistakes and iterations without external pressure.

---

## 2. Project Components

The project has **two main parts**:

### A. Landing Page (Public Website)
A public-facing website for Tarqumi with the following pages:
- **Home** – Hero section, highlights, 3 featured projects + "Read More", ~6 service cards + "Read More"
- **About** – Company info, hero section
- **Services** – Admin-editable service cards (icon + title + description only, NO detail pages)
- **Projects** – Showcase of completed/live projects
- **Blog** – ⚡ **Phase 1** – Generic articles with titles, descriptions, images. **SEO is a HUGE priority for blogs.**
- **Contact** – Contact form (Name, Email, Phone, Message) that sends emails via SMTP

#### Landing Page Key Requirements:
- **Bilingual**: Arabic and English only (no other languages). Admin writes both translations manually (no auto-translation).
- **RTL Support**: Full right-to-left layout when in Arabic. **Mandatory.**
- **URL Structure**: `/ar/about`, `/en/about` format
- **Design**: Black and white color scheme with shades in between. Simple but with animations/micro-interactions. No dark mode toggle.
- **SEO (CRITICAL!)**: Must use **SSR (Server-Side Rendering)** for proper SEO
  - Each page needs: Title, Description, Keywords — all editable by admin
  - OG Image support (Open Graph) — for WhatsApp/social media link previews
  - Proper semantic HTML (correct class names, div naming, etc.)
  - **Blog SEO must be exceptional** — structured data, meta tags, proper headings, etc.
- **Dynamic Content**: ALL text, images, icons, logos, footer content, social media links — everything visible on the landing page is **editable by the admin**
  - Admin selects which page → edits title, description, keywords, content
  - Social media links: only the ones the admin adds will be displayed
  - Footer: editable text, logo, email, social media links
  - Copyright year: auto-update based on current year
- **Contact Form**: 
  - Fields: Name, Email, Phone, Message
  - Sends emails using **SMTP** (Google App Passwords)
  - Admin decides which email(s) receive submissions — **can add multiple recipient emails**
  - Submissions are **also stored in the database**
  - **Rate limit: 5 emails per minute, NO CAPTCHA**
- **Blog Management**:
  - Anyone with landing page edit access can create/edit blogs
  - Blogs are bilingual (Arabic + English translations)
  - Generic articles: title, description, images
  - **Maximum SEO optimization** is required
- **Services**: Just cards (icon + title + description), no detail pages. Admin can add/edit/remove them.
- **Sections**: Admin can edit the TEXT within existing sections but NOT add/remove sections (section structure changes require code changes)
- **Images/Icons**: Admin can upload images or provide online links. **Max image size: 20MB.** Photos stored locally.
- **Caching**: SSR with caching is fine. **BUT** when admin updates, it must be **instantly reflected** (on-demand revalidation).

### B. CRM System (Admin Panel / Internal)
The internal CRM system for managing projects, clients, and team.

---

## 3. User Roles

There are **3 main roles**:

### 1. Admin / Super Admin
- Full control over the entire system
- **Only Super Admin can delete other Admins**
- Can create other admins and users
- Can edit landing page content
- Can manage everything
- **First admin account is seeded in the database**

### 2. Founder
- Sub-roles (final list):
  - **CEO**
  - **CTO** — The CTO is the ONLY founder role that can edit the landing page
  - **CFO**
- **HR is NOT a founder role** — it's a separate role
- Specific permissions for each sub-role will be detailed in **Phase 2**

### 3. Employee
- **Cannot edit their own profile** — only admins manage team members
- Phase 1: can view their assigned tasks and time tracking (basic)
- Future (desktop app): window and keyboard activity detection for time tracking
- Specific permissions to be defined in **Phase 2**

#### User Creation Fields:
- Name
- Email
- Password (with easy password reset button)
- Role selection (Admin / Founder / Employee / HR)
- If Founder → select sub-role (CEO, CTO, CFO)

#### Member Deletion Rules:
- When deleting a team member who is a Project Manager → **must choose a new project leader**
- If a member is inactive for **30 days** → automatically marked as inactive

---

## 4. CRM Features (Phase 1)

### A. Team Management
- Add team members with: Name, Email, Password, Role
- Assign roles and sub-roles
- Password reset functionality
- Employees cannot edit their own profiles

### B. Client Management
- **Fields**: Name, Company Name, Email, Phone, WhatsApp
- Clients can be linked to projects
- **A project can have MULTIPLE clients**
- **There is a default client ("Tarqumi") that CANNOT be removed**
- **Clients can be deleted** — but their linked projects are NOT deleted
- Active/inactive status

### C. Project Management (Internal Projects)
These are **internal projects** (NOT the landing page showcase projects). They are separate.

- **Project Name**
- **Description**
- **Client(s)** — linked from client list. Can have multiple. If no client specified, defaults to "Tarqumi"
- **Project Manager** — selected from existing team members
- **Budget** — no limit, from 0 upward
- **Currency** — standard list of most used currencies globally
- **Priority** — Scale of 1-10 (slider), NOT High/Medium/Low
- **Start Date**
- **End Date**
- **Status** — The **6 Software Development Lifecycle (SDLC) phases**:
  1. Planning
  2. Analysis
  3. Design
  4. Implementation
  5. Testing
  6. Deployment/Maintenance
- **Active/Inactive** — Inactive projects are **hidden from view** in the CRM
- **NO archived projects** — just active and inactive

### D. Landing Page Projects (Showcase)
Separate from internal projects. Simpler:
- Project Name
- Description
- URL/Domain link
- Live status (yes/no)
- Active status (show on landing page or not)

---

## 5. Phase Roadmap

### Phase 1 (Current):
- Complete landing page with all pages (Home, About, Services, Projects, Blog, Contact)
- All landing page CRUD operations
- Blog system with full SEO
- CRM: Client management, Project management, Team management
- Authentication with Sanctum
- Bilingual support (AR/EN) with RTL

### Phase 2 (Later):
- Tasks system
- Time tracking
- Finance management
- Detailed permissions for each role
- Dashboard with analytics

### Phase 3 (Future):
- Project history/activity logs (who changed what, when)

---

## 6. Technical Architecture

### Tech Stack:
- **Frontend**: **Next.js** (SSR for SEO)
- **Backend**: **Laravel** (PHP)
  - **Authentication**: Laravel **Sanctum**
  - **ORM**: **Eloquent**
  - **Database**: **MySQL** (relational — no NoSQL)
  - **File Storage**: Local filesystem (Laravel storage)
- **Full-Stack in one folder**: Keep frontend and backend in the same project/folder so AI coding tools can see both

### Database Requirements:
- **MySQL** with **proper indexing**
- **Correct search queries** — optimized
- **Relational data model** — no NoSQL
- ⚠️ **SQL INJECTION PROTECTION IS CRITICAL** — validate EVERY input, even button-type inputs

### Project Structure:
- **One Next.js project** for the frontend (both landing page and CRM panel)
- **One Laravel project** for the backend API
- Both in the same workspace folder
- Admin login: accessible via `/login` or a long/obscure URL slug (no visible login button on landing page)
- The project lead will set up domain routing for admin subdomain

### Development Approach:
1. Work **locally** (localhost) — don't worry about deployment yet
2. Backend runs on localhost
3. Push to **GitHub** when done
4. **Deployment** will be handled by the project owner (not the dev team)

### Code Quality Requirements:
- **SOLID principles**
- **OOP** (Object-Oriented Programming)
- **Clean Code**
- **DRY** (Don't Repeat Yourself)
- Use **CSS variables** for colors (not hardcoded colors)
- **SRP** (Single Responsibility Principle)
- Proper semantic HTML class naming for SEO
- **No hardcoded strings** — use i18n translation files
- **Input validation on EVERYTHING** — every single input, no exceptions

### Security Requirements:
- ⚠️ **SQL Injection Prevention** — parameterized queries, Eloquent ORM
- ⚠️ **Input Validation** — on EVERY input field, even button types
- **CSRF Protection** — Laravel built-in
- **XSS Prevention** — sanitize all outputs
- **Rate Limiting** — 5 contact form emails per minute
- **Sanctum** for API authentication
- **Password hashing** — bcrypt
- **Environment variables** for secrets (.env)

---

## 7. Development Workflow (Instructions from Lead)

### Step 1: Analysis Document
- Write a detailed analysis/description of the project
- Include all business logic, features, pages, and requirements

### Step 2: Feed to AI (ChatGPT / Cursor)
- Give the analysis to an AI → create **project structure/steering**
- Review and validate the steering is correct

### Step 3: User Stories
- Generate **detailed user stories** for every part of the system
- Examples:
  - "Admin logs in → goes to landing page settings → edits SEO title and description → saves"
  - "A regular user visits the site → browses → sends a contact message"

### Step 4: Technical Tasks
- Split tasks into **full-stack modules** (e.g., "Client Module — Front & Back")
- Do NOT split front-end and back-end separately

### Step 5: Implementation
- Use AI coding tools with **hooks**:
  - Translation hook: ensure all text uses i18n
  - Business logic hook: validate against user stories
  - Code quality hook: enforce SOLID, clean code, DRY

### Timeline:
- **2 days** to complete the steering + user stories
- Communication via **WhatsApp** (Q&A chat)

---

## 8. Landing Page Structure Summary

| Page | Content |
|------|---------|
| **Home** | Hero section, ~6 service cards + read more, ~3 featured projects + read more, contact CTA |
| **About** | Company info, hero section |
| **Services** | All service cards (icon + title + description per card) — admin CRUD |
| **Projects** | All live/active showcase projects |
| **Blog** | Blog articles with best-in-class SEO — admin CRUD, bilingual |
| **Contact** | Contact form (Name, Email, Phone, Message) → emails to admin-configured addresses |

**Navigation**: Header with Home, About, Contact, Services, Blog  
**Footer**: Logo, email, social media links, copyright year (auto)

---

## 9. Key Decisions Made

1. ✅ Build from scratch (no old code reuse)
2. ✅ Next.js frontend + Laravel backend
3. ✅ MySQL database with proper indexing
4. ✅ Laravel Sanctum authentication
5. ✅ Eloquent ORM
6. ✅ SSR for SEO
7. ✅ Black & white design with animations (no dark mode toggle)
8. ✅ Arabic + English bilingual with full RTL support
9. ✅ Admin controls ALL landing page content
10. ✅ Blog is Phase 1 with maximum SEO
11. ✅ Priority scale 1-10 (not High/Medium/Low)
12. ✅ Project statuses = 6 SDLC phases
13. ✅ Projects can have multiple clients
14. ✅ Default client "Tarqumi" cannot be removed
15. ✅ Standard currency list
16. ✅ SMTP for contact form emails (multiple recipients, stored in DB)
17. ✅ Rate limit: 5 emails/min, no CAPTCHA
18. ✅ Max image upload: 20MB, stored locally
19. ✅ SQL injection protection is CRITICAL
20. ✅ Input validation on EVERY field
21. ✅ Work locally, deployment handled by owner
22. ✅ Admin login via `/login` slug (no button on landing page)
23. ✅ Phase 1: Landing Page + Blog + Basic CRM
24. ✅ Phase 2: Tasks, time tracking, finance, role permissions, dashboard
25. ✅ Phase 3: Activity logs / project history
26. ✅ Inactive projects are hidden from CRM view
27. ✅ No archived projects, just active/inactive
28. ✅ Employee cannot edit own profile
29. ✅ 30 days inactivity → auto-inactive member
30. ✅ Deleting PM → must reassign projects first
