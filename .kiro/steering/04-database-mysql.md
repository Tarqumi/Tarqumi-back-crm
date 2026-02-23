---
inclusion: always
priority: 5
---

# Database Rules (MySQL)

## MySQL Configuration
- Use **InnoDB** engine for all tables (supports transactions and foreign keys)
- Use **UTF8MB4** character set (supports Arabic, emoji, etc.)
- Use **relational data model** — NO NoSQL

## Indexing Strategy
- **Primary keys**: auto-increment BIGINT UNSIGNED
- **Foreign keys**: always indexed
- **Search columns**: indexed (email, name, status, etc.)
- **Composite indexes** for common query patterns
- **Date range columns**: indexed

## Naming Conventions
- **Tables**: plural, snake_case (`projects`, `team_members`, `contact_submissions`)
- **Columns**: snake_case (`project_name`, `start_date`, `is_active`)
- **Foreign keys**: `{related_table_singular}_id` (`client_id`, `manager_id`)
- **Pivot tables**: alphabetical order (`client_project`, not `project_client`)

## Data Types
- Use **ENUM** or separate tables for fixed value sets (roles, statuses)
- Use **soft deletes** for: clients, team members, projects
- Use **BIGINT UNSIGNED** for IDs
- Use **DECIMAL** for currency/budget (not FLOAT)
- Use **TIMESTAMP** for dates with timezone support

## Foreign Key Constraints
Set proper **ON DELETE** constraints:
- Client deleted → projects keep existing (SET NULL on client_id)
- Team member deleted → reassign project manager first (handled in application logic)
- Default "Tarqumi" client → cannot be deleted (application-level check)

## Migration Rules
- **NEVER** modify existing migrations after they've been run — create new migrations for changes
- Add **indexes** on:
  - All foreign keys
  - Columns used in WHERE clauses frequently
  - Columns used in ORDER BY
  - Status columns
  - Email columns
  - Date columns used in ranges
- Use `->unsigned()` for foreign key integer columns
- Always define `->onDelete()` behavior for foreign keys
- Include `created_at` and `updated_at` timestamps on every table
- Use `softDeletes()` on tables where data should be preserved

## Data Integrity
- Define ALL foreign key constraints in migrations
- Use database-level constraints where possible (UNIQUE, NOT NULL, etc.)
- Use Laravel model events/observers for business rules
- Always wrap multi-table operations in **transactions**

## Key Tables

### users (team members)
- id, name, email, password, role (enum), founder_sub_role (nullable), last_login_at, is_active, created_at, updated_at, deleted_at

### clients
- id, name, company_name, email, phone, whatsapp, is_active, created_at, updated_at, deleted_at
- Default "Tarqumi" client seeded

### projects (internal)
- id, name, description, manager_id (FK users), budget, currency, priority (1-10), status (enum: 6 SDLC phases), start_date, end_date, is_active, created_at, updated_at, deleted_at

### client_project (pivot)
- client_id, project_id

### showcase_projects (landing page)
- id, name_ar, name_en, description_ar, description_en, url, image, is_live, is_active, created_at, updated_at

### blog_posts
- id, title_ar, title_en, slug_ar, slug_en, excerpt_ar, excerpt_en, content_ar, content_en, featured_image, seo_title_ar, seo_title_en, seo_description_ar, seo_description_en, seo_keywords_ar, seo_keywords_en, author_id (FK users), status (draft/published), published_at, created_at, updated_at, deleted_at

### services
- id, icon, title_ar, title_en, description_ar, description_en, order, is_active, created_at, updated_at

### contact_submissions
- id, name, email, phone, message, is_read, created_at

### page_seo
- id, page_slug, title_ar, title_en, description_ar, description_en, keywords_ar, keywords_en, og_image, created_at, updated_at

### site_settings
- id, key, value_ar, value_en, created_at, updated_at
- For footer text, tagline, logo image, contact email, etc.

### contact_emails (recipients for contact form)
- id, email, created_at, updated_at
- Multiple rows = multiple recipients for contact form submissions
- At least one email must always exist

### social_links
- id, platform (facebook, twitter, instagram, linkedin, youtube, tiktok, github, whatsapp, telegram), url, order, is_active, created_at, updated_at
- Only active social links display on the landing page

### page_content (CMS blocks)
- id, page_slug (home, about), section_key (hero_title, hero_subtitle, etc.), value_ar, value_en, image (nullable), created_at, updated_at
- Stores all editable landing page text/content blocks

## Query Optimization
- Use **eager loading** to avoid N+1 problems
- Use **chunking** for large datasets
- Check EXPLAIN on complex queries
- Use indexes effectively
- Paginate all list endpoints (default 15 per page)
