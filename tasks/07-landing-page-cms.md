# Module 7: Landing Page CMS Operations

## Overview
Complete CMS operations for managing all landing page content including SEO settings, hero sections, services, projects showcase, blog system, and dynamic content management with instant revalidation.

---

## Task 7.1: Page SEO Settings Management

**Priority:** 🔴 Critical  
**Estimated Time:** 4 hours  
**Dependencies:** Task 1.3  
**Assigned To:** Full-Stack Developer

**Objective:**
Implement comprehensive SEO settings management for all public pages with bilingual support and instant revalidation.

**Detailed Steps:**

1. **Create SEO settings table migration:**
   - page_identifier (home, about, services, projects, blog, contact)
   - meta_title (bilingual)
   - meta_description (bilingual)
   - meta_keywords (bilingual)
   - og_image
   - canonical_url
   - robots_meta

2. **Create SEOSettingsController**

3. **Implement SEO settings CRUD:**
   - Get settings per page
   - Update settings
   - Preview in Google/Facebook/Twitter format

4. **Add validation:**
   - Title: 10-60 characters
   - Description: 50-160 characters
   - Keywords: max 10
   - OG Image: 1200x630px, max 5MB

5. **Implement instant revalidation:**
   - Trigger Next.js revalidation on save
   - Clear cache for affected page

6. **Create admin UI with live preview**

7. **Add character counters and warnings**

8. **Create tests**

**Acceptance Criteria:**
- [ ] SEO settings editable per page
- [ ] Bilingual support (AR/EN)
- [ ] Character counters with color coding
- [ ] Live preview shows Google/social media appearance
- [ ] Instant revalidation works
- [ ] Validation enforced
- [ ] Tests pass

**Files Created:**
- `database/migrations/YYYY_MM_DD_create_seo_settings_table.php`
- `app/Models/SEOSetting.php`
- `app/Http/Controllers/Api/V1/SEOSettingsController.php`
- `app/Http/Requests/UpdateSEOSettingsRequest.php`
- `tests/Feature/SEOSettingsTest.php`

---

## Task 7.2: Home Page Hero Section Management

**Priority:** 🔴 Critical  
**Estimated Time:** 5 hours  
**Dependencies:** Task 7.1  
**Assigned To:** Full-Stack Developer

**Objective:**
Implement hero section content management with background image/video support and CTA buttons.

**Detailed Steps:**

1. **Create hero_sections table:**
   - headline (bilingual)
   - subheadline (bilingual)
   - primary_cta_text (bilingual)
   - primary_cta_link
   - secondary_cta_text (bilingual, optional)
   - secondary_cta_link (optional)
   - background_image
   - background_video (optional)
   - overlay_opacity (0-100)

2. **Create HeroSectionController**

3. **Implement image/video upload:**
   - Image: max 10MB, JPG/PNG/WebP, 1920x1080px
   - Video: max 50MB, MP4/WebM
   - Auto-optimization

4. **Add CTA button customization:**
   - Colors
   - Hover effects
   - Border radius

5. **Implement live preview**

6. **Add instant revalidation**

7. **Create tests**

**Acceptance Criteria:**
- [ ] Hero section editable
- [ ] Background image/video upload works
- [ ] CTA buttons customizable
- [ ] Live preview shows actual appearance
- [ ] Instant revalidation works
- [ ] Tests pass

---

## Task 7.3: Services Section Management

**Priority:** 🔴 Critical  
**Estimated Time:** 6 hours  
**Dependencies:** Task 7.2  
**Assigned To:** Full-Stack Developer

**Objective:**
Implement complete CRUD for service cards with icon selection, reordering, and home page preview control.

**Detailed Steps:**

1. **Create services table:**
   - title (bilingual)
   - short_description (bilingual)
   - long_description (bilingual, optional)
   - icon (SVG or icon library)
   - image (optional)
   - display_order
   - status (published/draft)

2. **Create ServiceController with full CRUD**

3. **Implement icon selection:**
   - Icon library (Font Awesome, Material Icons)
   - Custom SVG upload
   - Icon preview

4. **Add drag-and-drop reordering**

5. **Implement home page preview settings:**
   - Select which services to show (3, 4, 6, or 8)
   - Reorder for home page
   - Layout options (grid, carousel)

6. **Add duplicate service feature**

7. **Create comprehensive tests**

**Acceptance Criteria:**
- [ ] Service CRUD works
- [ ] Icon selection works (library + custom)
- [ ] Drag-and-drop reordering works
- [ ] Home page preview configurable
- [ ] Duplicate feature works
- [ ] Tests pass

---

## Task 7.4: Showcase Projects Management

**Priority:** 🔴 Critical  
**Estimated Time:** 7 hours  
**Dependencies:** Task 7.3  
**Assigned To:** Full-Stack Developer

**Objective:**
Implement complete CRUD for showcase projects with gallery, categories, and home page featuring.

**Detailed Steps:**

1. **Create showcase_projects table:**
   - title (bilingual)
   - client_name
   - client_logo
   - description (bilingual)
   - challenge (bilingual, optional)
   - solution (bilingual, optional)
   - results (bilingual, optional)
   - thumbnail
   - gallery_images (up to 10)
   - category
   - technologies (tags)
   - project_url
   - project_date
   - is_featured
   - display_order
   - status (published/draft)

2. **Create ShowcaseProjectController**

3. **Implement gallery management:**
   - Upload multiple images
   - Drag-and-drop reordering
   - Image optimization

4. **Add category management**

5. **Implement featured projects:**
   - Mark as featured for home page
   - Select which to show (3, 4, 6, or 8)

6. **Add technology tags**

7. **Create comprehensive tests**

**Acceptance Criteria:**
- [ ] Showcase project CRUD works
- [ ] Gallery management works (up to 10 images)
- [ ] Categories work
- [ ] Featured projects selectable
- [ ] Technology tags work
- [ ] Tests pass

---

## Task 7.5: Blog System - Post Management

**Priority:** 🔴 Critical  
**Estimated Time:** 8 hours  
**Dependencies:** Task 7.4  
**Assigned To:** Full-Stack Developer

**Objective:**
Implement complete blog post management with rich text editor, SEO optimization, and bilingual support.

**Detailed Steps:**

1. **Create blog_posts table:**
   - title (bilingual)
   - slug (bilingual, auto-generated)
   - excerpt (bilingual)
   - content (bilingual, rich text)
   - featured_image
   - author_id
   - status (draft/published/scheduled)
   - publish_date
   - meta_title (bilingual)
   - meta_description (bilingual)
   - meta_keywords (bilingual)
   - focus_keyword

2. **Create BlogPostController**

3. **Implement rich text editor:**
   - Full formatting support
   - Image upload
   - Code blocks with syntax highlighting
   - Tables
   - Embeds (YouTube, etc.)

4. **Add SEO analysis:**
   - SEO score (0-100)
   - Readability score
   - Keyword density
   - Suggestions

5. **Implement scheduling**

6. **Add version history**

7. **Create comprehensive tests**

**Acceptance Criteria:**
- [ ] Blog post CRUD works
- [ ] Rich text editor fully functional
- [ ] SEO analysis works
- [ ] Scheduling works
- [ ] Version history tracked
- [ ] Tests pass

---

## Task 7.6: Blog Categories and Tags

**Priority:** 🟠 High  
**Estimated Time:** 4 hours  
**Dependencies:** Task 7.5  
**Assigned To:** Backend Developer

**Objective:**
Implement category and tag management for blog organization.

**Detailed Steps:**

1. **Create blog_categories table:**
   - name (bilingual)
   - slug (bilingual)
   - description (bilingual)
   - parent_id (for hierarchical)
   - icon
   - display_order

2. **Create blog_tags table:**
   - name (bilingual)
   - slug (bilingual)

3. **Create pivot tables:**
   - blog_post_category
   - blog_post_tag

4. **Create CategoryController and TagController**

5. **Implement hierarchical categories**

6. **Add tag autocomplete**

7. **Create tests**

**Acceptance Criteria:**
- [ ] Category CRUD works
- [ ] Tag CRUD works
- [ ] Hierarchical categories work
- [ ] Posts can have multiple categories/tags
- [ ] Tests pass

---

## Task 7.7: About Page Content Management

**Priority:** 🟠 High  
**Estimated Time:** 5 hours  
**Dependencies:** Task 7.2  
**Assigned To:** Full-Stack Developer

**Objective:**
Implement About page content management with mission, vision, team, and statistics.

**Detailed Steps:**

1. **Create about_page_content table:**
   - mission_statement (bilingual)
   - vision_statement (bilingual)
   - company_story (bilingual)
   - core_values (JSON, up to 6)
   - statistics (JSON, 4 stats)

2. **Create AboutPageController**

3. **Implement core values management:**
   - Icon selection
   - Title and description
   - Reordering

4. **Add statistics management:**
   - Number, label, icon
   - Count-up animation settings

5. **Implement team member selection:**
   - Select from team members
   - Reorder for display

6. **Create tests**

**Acceptance Criteria:**
- [ ] About page content editable
- [ ] Core values manageable (up to 6)
- [ ] Statistics configurable (4 stats)
- [ ] Team member selection works
- [ ] Tests pass

---

## Task 7.8: Footer Content Management

**Priority:** 🟠 High  
**Estimated Time:** 3 hours  
**Dependencies:** Task 7.1  
**Assigned To:** Backend Developer

**Objective:**
Implement footer content management with social media links and contact information.

**Detailed Steps:**

1. **Create footer_settings table:**
   - logo
   - company_email
   - company_phone
   - company_address (bilingual)
   - footer_text (bilingual)
   - social_media_links (JSON)
   - quick_links (JSON)

2. **Create FooterSettingsController**

3. **Implement social media management:**
   - Add/remove platforms
   - Reorder links
   - Icon selection

4. **Add quick links management**

5. **Implement copyright year auto-update**

6. **Create tests**

**Acceptance Criteria:**
- [ ] Footer content editable
- [ ] Social media links manageable
- [ ] Quick links configurable
- [ ] Copyright year auto-updates
- [ ] Tests pass

---

## Task 7.9: Media Library Management

**Priority:** 🟠 High  
**Estimated Time:** 6 hours  
**Dependencies:** Task 7.3  
**Assigned To:** Full-Stack Developer

**Objective:**
Implement centralized media library for managing all uploaded images and files.

**Detailed Steps:**

1. **Create media_library table:**
   - filename
   - original_filename
   - file_path
   - file_size
   - mime_type
   - dimensions (for images)
   - alt_text
   - uploaded_by
   - usage_count

2. **Create MediaLibraryController**

3. **Implement upload functionality:**
   - Drag-and-drop
   - Multiple file upload
   - Auto-optimization
   - Thumbnail generation

4. **Add file management:**
   - Search and filter
   - Bulk delete
   - Usage tracking

5. **Implement image editing:**
   - Crop
   - Resize
   - Rotate

6. **Create tests**

**Acceptance Criteria:**
- [ ] Media library works
- [ ] Upload supports drag-and-drop
- [ ] Images auto-optimized
- [ ] Search and filter work
- [ ] Usage tracking works
- [ ] Tests pass

---

## Task 7.10: Content Revalidation System

**Priority:** 🔴 Critical  
**Estimated Time:** 4 hours  
**Dependencies:** Task 7.1  
**Assigned To:** Backend Developer

**Objective:**
Implement instant content revalidation system for Next.js SSR pages.

**Detailed Steps:**

1. **Create revalidation service:**
   - Trigger Next.js on-demand revalidation
   - Handle revalidation errors
   - Queue revalidation requests

2. **Implement revalidation triggers:**
   - On content save
   - On content publish
   - On content delete
   - Manual trigger option

3. **Add revalidation status tracking:**
   - Last revalidated timestamp
   - Revalidation success/failure
   - Error logging

4. **Create admin UI for manual revalidation**

5. **Add cache clearing**

6. **Create tests**

**Acceptance Criteria:**
- [ ] Revalidation triggers automatically
- [ ] Manual revalidation works
- [ ] Status tracked correctly
- [ ] Errors logged and handled
- [ ] Tests pass

---

## Task 7.11: Content Preview System

**Priority:** 🟡 Medium  
**Estimated Time:** 5 hours  
**Dependencies:** Task 7.10  
**Assigned To:** Full-Stack Developer

**Objective:**
Implement content preview system for viewing unpublished changes.

**Detailed Steps:**

1. **Create preview token system**

2. **Implement preview mode:**
   - Generate preview URL
   - Bypass cache for preview
   - Show draft content

3. **Add preview expiration (24 hours)**

4. **Create preview UI:**
   - Desktop/tablet/mobile views
   - Side-by-side comparison

5. **Add share preview link**

6. **Create tests**

**Acceptance Criteria:**
- [ ] Preview mode works
- [ ] Preview URLs generated
- [ ] Preview expires after 24 hours
- [ ] Responsive preview works
- [ ] Tests pass

---

## Task 7.12: Content Scheduling System

**Priority:** 🟡 Medium  
**Estimated Time:** 4 hours  
**Dependencies:** Task 7.5  
**Assigned To:** Backend Developer

**Objective:**
Implement content scheduling for blog posts and other content.

**Detailed Steps:**

1. **Add scheduling fields to content tables**

2. **Create scheduled job:**
   - Check for scheduled content
   - Publish at scheduled time
   - Send notifications

3. **Implement scheduling UI:**
   - Date/time picker
   - Timezone support
   - Schedule preview

4. **Add schedule management:**
   - View scheduled content
   - Edit schedule
   - Cancel schedule

5. **Create tests**

**Acceptance Criteria:**
- [ ] Scheduling works correctly
- [ ] Content publishes at scheduled time
- [ ] Notifications sent
- [ ] Schedule manageable
- [ ] Tests pass

---
