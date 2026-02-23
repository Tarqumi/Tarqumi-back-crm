---
inclusion: always
priority: 1
---

# Tarqumi CRM - Project Overview

## About Tarqumi
**Tarqumi** (ترقمي) is a software development company that builds websites, systems, and mobile apps for businesses across any industry. Team size: 10-50 people.

## Project Mission
Complete rebuild of the internal CRM system + public landing page. The old CRM was rushed in 1 day and full of bugs. This is a fresh start with proper analysis, architecture, and quality standards.

## Project Components

### 1. Landing Page (Public Website)
- **Pages**: Home, About, Services, Projects, Blog, Contact
- **Bilingual**: Arabic & English (manual translation by admin, no auto-translate)
- **RTL Support**: Full right-to-left layout for Arabic
- **URL Structure**: `/ar/`, `/en/`
- **Design**: Black & white with animations, no dark mode
- **SEO**: CRITICAL priority - SSR via Next.js, admin-editable meta tags, OG images, JSON-LD for blogs
- **Dynamic Content**: ALL text, images, icons, social links, footer content is admin-editable
- **Contact Form**: Name, Email, Phone, Message → SMTP to admin-configured email(s), stored in DB, rate limit 5/min
- **Blog**: Phase 1 with maximum SEO optimization
- **Services**: Cards only (no detail pages), admin CRUD
- **Instant Updates**: Admin changes reflect immediately (on-demand revalidation)

### 2. CRM System (Admin Panel / Internal)
- **Team Management**: Name, Email, Password, Role (Admin/Super Admin, Founder with sub-roles CEO/CTO/CFO, Employee, HR)
  - **HR is a separate role** — NOT a founder sub-role
- **Client Management**: Name, Company, Email, Phone, WhatsApp. Default "Tarqumi" client cannot be deleted
- **Project Management**: Internal projects with multiple clients, PM, budget, currency (20 currencies supported), priority 1-10
  - **6 SDLC Status Phases**: Planning → Analysis → Design → Implementation → Testing → Deployment
- **Landing Page CMS**: Only Admin and CTO can edit landing page content
- **Role-Based Access**: Super Admin can delete other Admins, Employee cannot edit own profile

## Tech Stack
- **Frontend**: Next.js (SSR for SEO)
- **Backend**: Laravel (PHP)
- **Auth**: Laravel Sanctum
- **ORM**: Eloquent
- **Database**: MySQL (relational, proper indexing)
- **File Storage**: Local (Laravel storage)
- **Max Image Upload**: 20MB

## Phase Roadmap
- **Phase 1** (Current): Landing page + Blog + Basic CRM (Team, Client, Project management)
- **Phase 2**: Tasks, time tracking, finance, detailed role permissions, dashboard
- **Phase 3**: Activity logs, project history

## Critical Requirements
- ⚠️ **SQL Injection Prevention**: ALWAYS use Eloquent/Query Builder, NEVER raw SQL with concatenation
- ⚠️ **Input Validation**: Validate EVERY input on EVERY endpoint, even button-type inputs
- ⚠️ **SEO Excellence**: Blog SEO must be exceptional - structured data, meta tags, proper headings
- ⚠️ **Security First**: XSS prevention, CSRF protection, rate limiting, password hashing
- ⚠️ **Code Quality**: SOLID, OOP, DRY, Clean Code principles mandatory
- ⚠️ **Zero Hardcoded Strings**: ALL text must use i18n translation files
- ⚠️ **Instant Revalidation**: Admin CMS changes must reflect immediately on landing page

## Development Approach
1. Work locally (localhost)
2. Full-stack modules (don't split frontend/backend)
3. Test before commit (minimum 80/100 score, security ≥90/100)
4. Push to GitHub when done
5. Deployment handled by project owner
