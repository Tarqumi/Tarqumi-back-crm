# Landing Page CMS User Stories

> **Format**: Each story follows the pattern:
> `As a [role], I want to [action], so that [benefit].`
>
> **Priority Levels**: 🔴 Critical | 🟠 High | 🟡 Medium | 🟢 Nice-to-have
>
> **Acceptance Criteria (AC)**: Numbered conditions that MUST be true for the story to be complete.
>
> **Edge Cases (EC)**: Scenarios that must be handled gracefully.

---

## US-CMS-5.1 🔴 Edit Page SEO Settings for All Pages
**As a** Super Admin, Admin, or Founder (CTO), **I want to** edit SEO settings for all public pages, **so that** the website ranks well in search engines and displays correctly on social media.

**Acceptance Criteria:**
1. SEO settings accessible from CMS dashboard for each page: Home, About, Services, Projects, Blog, Contact
2. Editable SEO fields per page:
   - Meta Title (max 60 characters with counter)
   - Meta Description (max 160 characters with counter)
   - Meta Keywords (comma-separated, max 10 keywords)
   - OG Image (Open Graph image for social sharing)
   - OG Title (defaults to Meta Title if empty)
   - OG Description (defaults to Meta Description if empty)
   - Twitter Card Type (Summary, Summary Large Image)
   - Canonical URL (auto-generated, editable)
   - Robots Meta (index/noindex, follow/nofollow)
3. SEO settings available in both Arabic and English (separate fields for bilingual content)
4. Character counters show remaining characters with color coding (green > 80%, yellow 50-80%, red < 50%)
5. OG Image upload: max 5MB, formats: JPG, PNG, WebP, recommended size 1200x630px
6. Image preview shown after upload with dimensions displayed
7. SEO preview panel shows how page appears in Google search results and social media
8. Real-time preview updates as user types
9. "Reset to Default" button restores system-generated SEO values
10. Save triggers instant revalidation of affected page
11. Success message: "SEO settings updated and page revalidated"
12. SEO settings logged in audit log with editor, timestamp, changed fields
13. JSON-LD structured data automatically generated based on SEO settings
14. Hreflang tags automatically configured for bilingual pages

**Edge Cases:**
- EC-1: Meta Title exceeds 60 characters → warning shown, Google truncates with "..."
- EC-2: Meta Description exceeds 160 characters → warning shown, truncation preview displayed
- EC-3: OG Image exceeds 5MB → validation error with file size shown
- EC-4: OG Image wrong dimensions → warning: "Recommended size is 1200x630px. Current: [width]x[height]"
- EC-5: No OG Image uploaded → default site logo used, warning shown
- EC-6: Meta Title empty → validation error: "Meta Title is required"
- EC-7: Meta Description empty → warning: "Meta Description recommended for better SEO"
- EC-8: Duplicate Meta Titles across pages → warning: "This title is used on [Page Name]"
- EC-9: Keywords with special characters → sanitized automatically
- EC-10: Canonical URL invalid format → validation error with format example
- EC-11: User navigates away with unsaved changes → confirmation prompt
- EC-12: Network error during save → error message, form data preserved, retry option

**Validation Rules:**
- Meta Title: required, 10-60 characters recommended
- Meta Description: optional, 50-160 characters recommended
- Meta Keywords: max 10 keywords, comma-separated
- OG Image: JPG/PNG/WebP, max 5MB, recommended 1200x630px
- Canonical URL: valid URL format
- Robots Meta: valid values only (index/noindex, follow/nofollow)

**Security Considerations:**
- Only Super Admin, Admin, and CTO can edit SEO settings
- All input sanitized to prevent XSS attacks
- OG Image scanned for malicious content
- SEO changes logged in audit log
- Canonical URL validated to prevent open redirect

**SEO Best Practices:**
- Meta Title: Include primary keyword, brand name, under 60 chars
- Meta Description: Compelling copy, call-to-action, under 160 chars
- OG Image: High quality, 1200x630px, under 1MB for fast loading
- Keywords: Relevant, not stuffed, 5-10 keywords max
- Canonical URL: Prevent duplicate content issues
- JSON-LD: Structured data for rich snippets

**Responsive Design:**
- Mobile (375px): Single column form, full-width inputs, collapsible preview
- Tablet (768px): Two-column layout (form left, preview right)
- Desktop (1024px+): Side-by-side layout with live preview panel

**Performance:**
- SEO settings load: < 500ms
- Save and revalidate: < 2 seconds
- OG Image upload and optimization: < 3 seconds
- Preview generation: < 200ms

**UX Considerations:**
- Character counters with color coding
- Real-time Google/Facebook preview
- Inline validation with helpful error messages
- "Copy from English" button for Arabic fields (translation helper)
- SEO score indicator (0-100) based on best practices
- Tooltips explaining each field's purpose
- "View Live Page" button to see published result
- Keyboard shortcuts: Ctrl+S to save

---

## US-CMS-5.2 🔴 Edit Home Page Hero Section Content
**As a** Super Admin, Admin, or Founder (CTO), **I want to** edit the Home page hero section content, **so that** I can update the main headline, subheadline, CTA buttons, and background image.

**Acceptance Criteria:**
1. Hero section editor accessible from CMS dashboard → Home Page → Hero Section
2. Editable fields (bilingual - Arabic and English):
   - Main Headline (max 100 characters)
   - Subheadline (max 200 characters)
   - Primary CTA Button Text (max 30 characters)
   - Primary CTA Button Link (URL or internal page)
   - Secondary CTA Button Text (max 30 characters, optional)
   - Secondary CTA Button Link (URL or internal page, optional)
   - Background Image (upload or select from media library)
   - Background Video (upload or YouTube/Vimeo URL, optional)
   - Overlay Opacity (0-100%, slider control)
3. Background Image upload: max 10MB, formats: JPG, PNG, WebP, recommended size 1920x1080px
4. Background Video upload: max 50MB, formats: MP4, WebM, recommended size 1920x1080px
5. Image/Video preview shown with actual dimensions
6. Live preview panel shows hero section as it will appear on website
7. Preview responsive: toggle between mobile, tablet, desktop views
8. CTA button style customization: color, hover color, border radius
9. Text alignment options: left, center, right
10. Animation options: fade in, slide up, none
11. Save triggers instant revalidation of home page
12. Success message: "Hero section updated and home page revalidated"
13. Changes logged in audit log with editor, timestamp
14. "Publish" and "Save as Draft" options
15. Version history: view and restore previous versions

**Edge Cases:**
- EC-1: Headline exceeds 100 characters → validation error with character count
- EC-2: Background Image exceeds 10MB → validation error, suggest compression
- EC-3: Background Video exceeds 50MB → validation error, suggest compression or external hosting
- EC-4: Invalid CTA link format → validation error with format example
- EC-5: Both background image and video uploaded → video takes priority, image used as fallback
- EC-6: No background image or video → solid color background used, warning shown
- EC-7: YouTube URL invalid → validation error, suggest correct format
- EC-8: CTA button text empty → validation error: "Button text required"
- EC-9: Primary CTA link empty → validation error: "Button link required"
- EC-10: Secondary CTA fields partially filled → validation: both text and link required
- EC-11: Overlay opacity 0% with light background → warning: "Text may not be readable"
- EC-12: User saves as draft → changes not visible on live site until published

**Validation Rules:**
- Headline: required, max 100 characters
- Subheadline: optional, max 200 characters
- CTA Button Text: required, max 30 characters
- CTA Button Link: required, valid URL or internal path
- Background Image: JPG/PNG/WebP, max 10MB, min 1280x720px
- Background Video: MP4/WebM, max 50MB, recommended 1920x1080px
- Overlay Opacity: 0-100%

**Security Considerations:**
- Only authorized roles can edit hero section
- All input sanitized to prevent XSS
- Media files scanned for malicious content
- External video URLs validated (YouTube/Vimeo only)
- CTA links validated to prevent open redirect

**Responsive Design:**
- Mobile (375px): Stacked layout, smaller headline, single CTA button
- Tablet (768px): Centered content, both CTA buttons visible
- Desktop (1024px+): Full-width hero, large headline, side-by-side CTAs

**Performance:**
- Hero section load: < 500ms
- Save and revalidate: < 2 seconds
- Background image optimization: < 3 seconds (auto-compress to WebP)
- Background video lazy load: loads after page render
- Live preview update: < 200ms

**UX Considerations:**
- WYSIWYG live preview
- Responsive preview toggle (mobile/tablet/desktop)
- Character counters for all text fields
- Media library integration for quick image selection
- "Copy from English" button for Arabic fields
- Undo/Redo functionality
- Auto-save draft every 30 seconds
- "Preview on Live Site" button (opens in new tab)
- Version history with restore option

---

## US-CMS-5.3 🔴 Edit Home Page Services Preview Section
**As a** Super Admin, Admin, or Founder (CTO), **I want to** edit the services preview section on the home page, **so that** I can control which services are highlighted and their display order.

**Acceptance Criteria:**
1. Services preview editor accessible from CMS dashboard → Home Page → Services Section
2. Editable fields (bilingual):
   - Section Title (max 100 characters)
   - Section Subtitle (max 200 characters)
   - Number of services to display (3, 4, 6, or 8)
   - Service selection: multi-select from available service cards
   - Display order: drag-and-drop reordering
   - "View All Services" button text (max 30 characters)
   - "View All Services" button link (defaults to /services)
3. Service card preview shows: Icon, Title, Short Description
4. Live preview panel shows section as it will appear on website
5. Preview responsive: toggle between mobile, tablet, desktop views
6. Layout options: Grid (2 columns, 3 columns, 4 columns), Carousel
7. Animation options: fade in, slide up, stagger, none
8. Background style: solid color, gradient, image
9. Save triggers instant revalidation of home page
10. Success message: "Services section updated and home page revalidated"
11. Changes logged in audit log
12. If no services available, warning shown: "Create service cards first"
13. Selected services must be published (draft services excluded)
14. Section can be hidden entirely (toggle switch)

**Edge Cases:**
- EC-1: Section title exceeds 100 characters → validation error
- EC-2: No services selected → validation error: "Select at least 1 service"
- EC-3: Selected service deleted → automatically removed from preview, warning shown
- EC-4: Selected service unpublished → automatically hidden from preview, warning shown
- EC-5: More services selected than display limit → only first N shown, others ignored
- EC-6: Drag-and-drop on touch device → touch-friendly reordering
- EC-7: Section hidden → not rendered on home page, SEO impact warning shown
- EC-8: Background image too large → auto-compressed, warning if quality reduced
- EC-9: Carousel with 1-2 services → carousel disabled, grid layout used
- EC-10: Grid layout with odd number of services → last row centered
- EC-11: Service card content too long → truncated with "Read more" link
- EC-12: Arabic content with RTL → layout mirrors correctly

**Validation Rules:**
- Section Title: required, max 100 characters
- Section Subtitle: optional, max 200 characters
- Number of services: 3, 4, 6, or 8
- At least 1 service must be selected
- Selected services must be published
- Button text: max 30 characters

**Security Considerations:**
- Only authorized roles can edit services section
- All input sanitized to prevent XSS
- Service selection validated (only published services)
- Background image scanned for malicious content

**Responsive Design:**
- Mobile (375px): 1 column, stacked services, swipe carousel
- Tablet (768px): 2 columns, grid or carousel
- Desktop (1024px+): 3-4 columns, grid or carousel with navigation

**Performance:**
- Section load: < 500ms
- Save and revalidate: < 2 seconds
- Live preview update: < 200ms
- Carousel animation: 60fps smooth

**UX Considerations:**
- Drag-and-drop reordering with visual feedback
- Service card preview with hover state
- Layout preview for each option
- "Select All Published Services" quick action
- "Reset to Default Order" button
- Live preview with responsive toggle
- Auto-save draft every 30 seconds
- Undo/Redo functionality

---

## US-CMS-5.4 🔴 Edit Home Page Projects Preview Section
**As a** Super Admin, Admin, or Founder (CTO), **I want to** edit the projects preview section on the home page, **so that** I can showcase featured projects and control their display.

**Acceptance Criteria:**
1. Projects preview editor accessible from CMS dashboard → Home Page → Projects Section
2. Editable fields (bilingual):
   - Section Title (max 100 characters)
   - Section Subtitle (max 200 characters)
   - Number of projects to display (3, 4, 6, or 8)
   - Project selection: multi-select from showcase projects
   - Display order: drag-and-drop reordering
   - "View All Projects" button text (max 30 characters)
   - "View All Projects" button link (defaults to /projects)
3. Project card preview shows: Image, Title, Client, Category, Short Description
4. Live preview panel shows section as it will appear on website
5. Preview responsive: toggle between mobile, tablet, desktop views
6. Layout options: Grid (2 columns, 3 columns, 4 columns), Masonry, Carousel
7. Filter options: Show all, Filter by category, Filter by client
8. Animation options: fade in, slide up, stagger, zoom in, none
9. Save triggers instant revalidation of home page
10. Success message: "Projects section updated and home page revalidated"
11. Changes logged in audit log
12. If no showcase projects available, warning shown: "Create showcase projects first"
13. Selected projects must be published (draft projects excluded)
14. Section can be hidden entirely (toggle switch)

**Edge Cases:**
- EC-1: Section title exceeds 100 characters → validation error
- EC-2: No projects selected → validation error: "Select at least 1 project"
- EC-3: Selected project deleted → automatically removed, warning shown
- EC-4: Selected project unpublished → automatically hidden, warning shown
- EC-5: More projects selected than display limit → only first N shown
- EC-6: Project without image → placeholder image used, warning shown
- EC-7: Section hidden → not rendered on home page, SEO impact warning
- EC-8: Masonry layout with varying image sizes → layout calculated correctly
- EC-9: Carousel with 1-2 projects → carousel disabled, grid layout used
- EC-10: Filter by category with no projects → empty state shown
- EC-11: Project card content too long → truncated with "View project" link
- EC-12: Arabic content with RTL → layout mirrors correctly

**Validation Rules:**
- Section Title: required, max 100 characters
- Section Subtitle: optional, max 200 characters
- Number of projects: 3, 4, 6, or 8
- At least 1 project must be selected
- Selected projects must be published showcase projects
- Button text: max 30 characters

**Security Considerations:**
- Only authorized roles can edit projects section
- All input sanitized to prevent XSS
- Project selection validated (only published showcase projects)
- Project images validated and optimized

**Responsive Design:**
- Mobile (375px): 1 column, stacked projects, swipe carousel
- Tablet (768px): 2 columns, grid or masonry
- Desktop (1024px+): 3-4 columns, grid, masonry, or carousel

**Performance:**
- Section load: < 500ms
- Save and revalidate: < 2 seconds
- Live preview update: < 200ms
- Masonry layout calculation: < 100ms
- Image lazy loading for better performance

**UX Considerations:**
- Drag-and-drop reordering with visual feedback
- Project card preview with hover effects
- Layout preview for each option
- "Select All Published Projects" quick action
- "Reset to Default Order" button
- Category filter with project count
- Live preview with responsive toggle
- Auto-save draft every 30 seconds
- Undo/Redo functionality

---

## US-CMS-5.5 🔴 Edit About Page Content
**As a** Super Admin, Admin, or Founder (CTO), **I want to** edit the About page content, **so that** I can update company information, team details, and mission statement.

**Acceptance Criteria:**
1. About page editor accessible from CMS dashboard → About Page
2. Editable sections (bilingual):
   - Page Title (max 100 characters)
   - Hero Section: Headline, Subheadline, Background Image
   - Mission Statement (rich text editor, max 1000 characters)
   - Vision Statement (rich text editor, max 1000 characters)
   - Company Story (rich text editor, max 3000 characters)
   - Core Values (list of values with icons, max 6 values)
   - Team Section: Title, Subtitle, Team member selection
   - Statistics Section: 4 stat cards (number, label, icon)
   - Call-to-Action Section: Headline, Button Text, Button Link
3. Rich text editor features: bold, italic, underline, lists, links, headings
4. Image upload for hero background: max 10MB, JPG/PNG/WebP, 1920x1080px
5. Core values: each value has icon (select from library), title (max 50 chars), description (max 200 chars)
6. Team member selection: multi-select from team members, drag-and-drop ordering
7. Statistics: number (auto-formatted with commas), label (max 50 chars), icon, animation (count-up effect)
8. Live preview panel shows page as it will appear on website
9. Preview responsive: toggle between mobile, tablet, desktop views
10. Save triggers instant revalidation of about page
11. Success message: "About page updated and revalidated"
12. Changes logged in audit log
13. "Publish" and "Save as Draft" options
14. Version history: view and restore previous versions

**Edge Cases:**
- EC-1: Mission statement exceeds 1000 characters → validation error with character count
- EC-2: Company story exceeds 3000 characters → validation error
- EC-3: More than 6 core values → validation error: "Maximum 6 core values allowed"
- EC-4: No team members selected → team section hidden on live page
- EC-5: Team member deleted → automatically removed from about page, warning shown
- EC-6: Statistics number invalid (non-numeric) → validation error
- EC-7: Statistics number too large (>1,000,000) → formatted with K/M suffix (e.g., 1.5M)
- EC-8: Hero background image missing → solid color background used
- EC-9: Rich text editor with malicious HTML → sanitized automatically
- EC-10: CTA button link invalid → validation error
- EC-11: Arabic content with RTL → rich text editor supports RTL
- EC-12: User saves as draft → changes not visible on live site until published

**Validation Rules:**
- Page Title: required, max 100 characters
- Mission Statement: optional, max 1000 characters
- Vision Statement: optional, max 1000 characters
- Company Story: optional, max 3000 characters
- Core Values: max 6 values, each with title (max 50 chars) and description (max 200 chars)
- Statistics: number (numeric), label (max 50 chars)
- Hero Background: JPG/PNG/WebP, max 10MB

**Security Considerations:**
- Only authorized roles can edit about page
- Rich text content sanitized to prevent XSS
- HTML tags whitelist: p, strong, em, u, ul, ol, li, a, h2, h3
- External links validated
- Image files scanned for malicious content

**Responsive Design:**
- Mobile (375px): Single column, stacked sections, smaller images
- Tablet (768px): Two-column layout for core values and statistics
- Desktop (1024px+): Multi-column layout, full-width hero

**Performance:**
- About page load: < 1 second
- Save and revalidate: < 2 seconds
- Rich text editor load: < 300ms
- Live preview update: < 200ms
- Image optimization: < 3 seconds

**UX Considerations:**
- Rich text editor with formatting toolbar
- Character counters for all text fields
- Icon picker for core values and statistics
- Team member selection with search and filter
- Drag-and-drop reordering for core values and team members
- Live preview with responsive toggle
- Auto-save draft every 30 seconds
- Undo/Redo functionality
- "Copy from English" button for Arabic fields
- Version history with visual diff

---

## US-CMS-5.6 🔴 Manage Service Cards (CRUD with Reordering)
**As a** Super Admin, Admin, or Founder (CTO), **I want to** create, read, update, delete, and reorder service cards, **so that** I can manage the services displayed on the website.

**Acceptance Criteria:**
1. Service cards management accessible from CMS dashboard → Services
2. Service card list displays: Icon, Title, Status (Published/Draft), Order, Last Modified, Actions
3. **Create Service Card:**
   - Required fields: Title (bilingual), Short Description (bilingual), Icon
   - Optional fields: Long Description (rich text, bilingual), Image, CTA Button Text, CTA Button Link, SEO fields
   - Icon selection: choose from icon library (Font Awesome, Material Icons) or upload custom SVG
   - Image upload: max 5MB, JPG/PNG/WebP, recommended 800x600px
   - Status: Draft or Published
   - Display order: auto-assigned (last position)
4. **View Service Cards:**
   - List view with pagination (20 per page)
   - Grid view with card previews
   - Search by title or description
   - Filter by status (All, Published, Draft)
   - Sort by: Order, Title, Last Modified, Created Date
5. **Update Service Card:**
   - Edit all fields
   - Change status (Draft ↔ Published)
   - Update display order
   - Version history tracked
6. **Delete Service Card:**
   - Confirmation dialog with warning
   - Soft delete (recoverable for 30 days)
   - If service used in home page preview → warning shown
   - Deletion logged in audit log
7. **Reorder Service Cards:**
   - Drag-and-drop reordering in list view
   - Numeric order input for precise positioning
   - Bulk reorder: select multiple, assign sequential order
   - "Move to Top" and "Move to Bottom" quick actions
   - Reorder changes saved automatically
   - Reordering triggers revalidation of affected pages
8. Live preview shows service card as it will appear on website
9. Save triggers instant revalidation of services page and home page (if used)
10. Success messages: "Service card created", "Service card updated", "Service card deleted"
11. Changes logged in audit log with editor, timestamp, changed fields
12. Duplicate service card feature (copies all fields, adds "Copy" suffix to title)
13. Export/Import service cards (JSON format) for backup or migration
14. Service card analytics: views, clicks on CTA button (if applicable)

**Edge Cases:**
- EC-1: Title exceeds character limit → validation error with character count
- EC-2: No icon selected → validation error: "Icon is required"
- EC-3: Image exceeds 5MB → validation error, suggest compression
- EC-4: Custom SVG icon with malicious code → sanitized automatically, warning shown
- EC-5: Delete service used in home page preview → warning: "This service is displayed on home page. Remove from home page first or it will be hidden automatically."
- EC-6: Publish service with empty required fields → validation error listing missing fields
- EC-7: Duplicate title (same language) → warning: "A service with this title already exists"
- EC-8: Reorder with drag-and-drop on touch device → touch-friendly reordering
- EC-9: Delete last published service → warning: "This is the last published service"
- EC-10: Soft-deleted service recovered → restored to draft status, order position at end
- EC-11: Rich text description with malicious HTML → sanitized automatically
- EC-12: CTA link invalid format → validation error with format example

**Validation Rules:**
- Title: required, max 100 characters per language
- Short Description: required, max 200 characters per language
- Long Description: optional, max 3000 characters per language
- Icon: required (library icon or custom SVG)
- Image: JPG/PNG/WebP, max 5MB, min 400x300px
- CTA Button Text: optional, max 30 characters
- CTA Button Link: required if CTA text provided, valid URL format
- Status: Draft or Published

**Security Considerations:**
- Only authorized roles can manage service cards
- All input sanitized to prevent XSS
- Rich text content sanitized (whitelist: p, strong, em, u, ul, ol, li, a, h3, h4)
- Custom SVG icons sanitized to remove scripts
- Image files scanned for malicious content
- Soft delete prevents accidental data loss
- All CRUD operations logged in audit log

**Responsive Design:**
- Mobile (375px): List view only, stacked cards, swipe to delete
- Tablet (768px): Grid view (2 columns), drag-and-drop reordering
- Desktop (1024px+): Grid view (3-4 columns), advanced filters, bulk actions

**Performance:**
- Service card list load: < 500ms
- Create/Update/Delete: < 1 second
- Drag-and-drop reorder: < 100ms response time
- Image upload and optimization: < 3 seconds
- Search and filter: < 200ms

**UX Considerations:**
- Inline editing for quick updates (title, status)
- Bulk actions: Publish, Unpublish, Delete, Reorder
- Quick filters: Published, Draft, Recently Modified
- Search with autocomplete
- Drag-and-drop with visual feedback (ghost element, drop zones)
- Confirmation dialogs for destructive actions
- Toast notifications for all actions
- Keyboard shortcuts: N (new), E (edit), D (delete), Ctrl+S (save)
- "Copy from English" button for Arabic fields
- Auto-save draft every 30 seconds
- Undo/Redo for reordering

---

## US-CMS-5.7 🔴 Manage Showcase Projects (CRUD with Reordering)
**As a** Super Admin, Admin, or Founder (CTO), **I want to** create, read, update, delete, and reorder showcase projects, **so that** I can manage the portfolio projects displayed on the website.

**Acceptance Criteria:**
1. Showcase projects management accessible from CMS dashboard → Projects
2. Project list displays: Thumbnail, Title, Client, Category, Status (Published/Draft), Featured, Order, Last Modified, Actions
3. **Create Showcase Project:**
   - Required fields: Title (bilingual), Client Name, Category, Project Date, Thumbnail Image
   - Optional fields: Client Logo, Description (rich text, bilingual), Challenge, Solution, Results, Technologies Used, Project URL, Gallery Images (up to 10), Video URL, Testimonial
   - Thumbnail upload: max 5MB, JPG/PNG/WebP, recommended 800x600px
   - Gallery images: max 5MB each, JPG/PNG/WebP, recommended 1200x900px
   - Category: select from predefined list or create new
   - Technologies: multi-select tags (e.g., React, Node.js, AWS)
   - Featured toggle: mark project as featured for home page
   - Status: Draft or Published
   - Display order: auto-assigned (last position)
4. **View Showcase Projects:**
   - List view with pagination (20 per page)
   - Grid view with project cards
   - Search by title, client, or category
   - Filter by: Status (All, Published, Draft), Category, Featured, Date Range
   - Sort by: Order, Title, Client, Date, Last Modified
5. **Update Showcase Project:**
   - Edit all fields
   - Change status (Draft ↔ Published)
   - Toggle featured status
   - Update display order
   - Replace or add gallery images
   - Version history tracked
6. **Delete Showcase Project:**
   - Confirmation dialog with warning
   - Soft delete (recoverable for 30 days)
   - If project used in home page preview → warning shown
   - Deletion logged in audit log
7. **Reorder Showcase Projects:**
   - Drag-and-drop reordering in list view
   - Numeric order input for precise positioning
   - Bulk reorder: select multiple, assign sequential order
   - "Move to Top" and "Move to Bottom" quick actions
   - Reorder changes saved automatically
8. Live preview shows project as it will appear on website
9. Save triggers instant revalidation of projects page and home page (if featured)
10. Success messages: "Project created", "Project updated", "Project deleted"
11. Changes logged in audit log
12. Duplicate project feature (copies all fields except gallery images)
13. Export/Import projects (JSON format)
14. Project analytics: views, clicks to project URL
15. Gallery image reordering with drag-and-drop

**Edge Cases:**
- EC-1: Title exceeds character limit → validation error
- EC-2: No thumbnail image → validation error: "Thumbnail is required"
- EC-3: Gallery image exceeds 5MB → validation error per image
- EC-4: More than 10 gallery images → validation error: "Maximum 10 images allowed"
- EC-5: Delete featured project used on home page → warning with auto-removal option
- EC-6: Publish project with empty required fields → validation error listing fields
- EC-7: Duplicate title (same language) → warning: "A project with this title already exists"
- EC-8: Project URL invalid format → validation error
- EC-9: Video URL not YouTube/Vimeo → validation error: "Only YouTube and Vimeo URLs supported"
- EC-10: Category deleted → projects with that category show "Uncategorized"
- EC-11: Client logo and name both provided → logo displayed with alt text as client name
- EC-12: Rich text description with malicious HTML → sanitized automatically

**Validation Rules:**
- Title: required, max 100 characters per language
- Client Name: required, max 100 characters
- Category: required, select from list
- Project Date: required, valid date format
- Thumbnail: required, JPG/PNG/WebP, max 5MB, min 400x300px
- Gallery Images: optional, max 10 images, 5MB each
- Description: optional, max 5000 characters per language
- Project URL: optional, valid URL format
- Video URL: optional, YouTube or Vimeo URL only
- Technologies: optional, max 15 tags

**Security Considerations:**
- Only authorized roles can manage projects
- All input sanitized to prevent XSS
- Rich text content sanitized
- Image files scanned for malicious content
- External URLs validated
- Soft delete prevents accidental data loss
- All CRUD operations logged

**Responsive Design:**
- Mobile (375px): List view, stacked cards, swipe actions
- Tablet (768px): Grid view (2 columns), drag-and-drop
- Desktop (1024px+): Grid view (3 columns), advanced filters, bulk actions

**Performance:**
- Project list load: < 500ms
- Create/Update/Delete: < 1 second
- Gallery upload (10 images): < 10 seconds with progress bar
- Image optimization: automatic WebP conversion
- Search and filter: < 200ms

**UX Considerations:**
- Inline editing for quick updates
- Bulk actions: Publish, Unpublish, Feature, Unfeature, Delete
- Quick filters with project count
- Drag-and-drop gallery image upload
- Gallery image reordering with preview
- Technology tags with autocomplete
- "Copy from English" button for Arabic fields
- Auto-save draft every 30 seconds
- Image cropping tool for thumbnails
- Keyboard shortcuts

---

## US-CMS-5.8 🔴 Edit Footer Content (Logo, Email, Social Links)
**As a** Super Admin, Admin, or Founder (CTO), **I want to** edit footer content including logo, contact email, and social media links, **so that** visitors can find contact information and follow us on social media.

**Acceptance Criteria:**
1. Footer editor accessible from CMS dashboard → Site Settings → Footer
2. Editable fields (bilingual where applicable):
   - Footer Logo (upload or select from media library)
   - Company Tagline (max 200 characters, bilingual)
   - Contact Email (validated email format)
   - Phone Number (with country code, formatted)
   - Address (rich text, bilingual, max 300 characters)
   - Copyright Text (auto-generated with year, editable)
   - Social Media Links: Facebook, Twitter, LinkedIn, Instagram, YouTube, GitHub, Behance
   - Newsletter Signup: Enable/Disable toggle, Placeholder text (bilingual)
   - Footer Menu Links: Quick Links, Services, Legal (Privacy Policy, Terms of Service)
3. Footer logo upload: max 2MB, PNG/SVG preferred, recommended 200x60px
4. Social media links: URL validation for each platform
5. Social media icons: choose icon style (solid, outline, colored)
6. Phone number: auto-formatted based on country code
7. Address: supports line breaks and basic formatting
8. Copyright text: auto-includes current year, company name editable
9. Footer menu links: drag-and-drop reordering, add/remove links
10. Newsletter signup: integrates with email service (Mailchimp, SendGrid)
11. Live preview shows footer as it will appear on all pages
12. Save triggers instant revalidation of all pages (footer is global)
13. Success message: "Footer updated and all pages revalidated"
14. Changes logged in audit log

**Edge Cases:**
- EC-1: Footer logo exceeds 2MB → validation error, suggest compression
- EC-2: Footer logo wrong dimensions → warning with recommended size
- EC-3: Contact email invalid format → validation error with format example
- EC-4: Phone number invalid format → validation error, suggest format
- EC-5: Social media URL invalid → validation error per platform
- EC-6: Social media URL for wrong platform → validation error: "This is not a valid Facebook URL"
- EC-7: No social media links provided → social icons section hidden
- EC-8: Newsletter signup enabled but no email service configured → warning shown
- EC-9: Footer menu link to non-existent page → validation warning
- EC-10: Copyright text empty → auto-generated default used
- EC-11: Address exceeds 300 characters → validation error
- EC-12: Arabic content with RTL → footer layout mirrors correctly

**Validation Rules:**
- Footer Logo: PNG/SVG/JPG, max 2MB, recommended 200x60px
- Company Tagline: max 200 characters per language
- Contact Email: required, valid email format
- Phone Number: optional, valid international format
- Address: optional, max 300 characters per language
- Social Media URLs: valid URL format, platform-specific validation
- Footer Menu Links: valid internal or external URLs

**Security Considerations:**
- Only authorized roles can edit footer
- All input sanitized to prevent XSS
- Email address obfuscated to prevent scraping
- External links validated
- Logo file scanned for malicious content
- Newsletter signup with CAPTCHA to prevent spam

**Responsive Design:**
- Mobile (375px): Stacked layout, centered logo, vertical social icons
- Tablet (768px): Two-column layout, horizontal social icons
- Desktop (1024px+): Multi-column layout, full footer menu

**Performance:**
- Footer load: < 200ms (cached globally)
- Save and revalidate all pages: < 5 seconds
- Logo optimization: automatic compression
- Social icons: SVG for crisp display

**UX Considerations:**
- Live preview with responsive toggle
- Social media URL auto-detection (paste any URL, system detects platform)
- Phone number auto-formatting as user types
- Email validation with suggestions (e.g., "Did you mean @gmail.com?")
- "Copy from English" button for bilingual fields
- Footer menu link builder with internal page selector
- Icon style preview for social media
- Auto-save draft every 30 seconds
- "Reset to Default" button for copyright text

---

## US-CMS-5.9 🟠 Configure Contact Form Email Recipients
**As a** Super Admin, Admin, or Founder (CTO), **I want to** configure email recipients for contact form submissions, **so that** inquiries are routed to the appropriate team members.

**Acceptance Criteria:**
1. Contact form settings accessible from CMS dashboard → Site Settings → Contact Form
2. Editable settings:
   - Primary recipient email (required, validated)
   - CC recipients (up to 5 emails, comma-separated)
   - BCC recipients (up to 5 emails, comma-separated)
   - Auto-reply enabled/disabled toggle
   - Auto-reply email template (rich text, bilingual)
   - Email subject prefix (e.g., "[Website Inquiry]")
   - Notification email template (rich text, includes form data)
   - Form fields configuration: enable/disable fields (Name, Email, Phone, Company, Message)
   - Required fields selection
   - CAPTCHA enabled/disabled toggle
   - Success message (bilingual, max 200 characters)
   - Error message (bilingual, max 200 characters)
3. Email validation: all recipient emails validated before save
4. Auto-reply template variables: {name}, {email}, {company}, {date}
5. Notification template variables: {name}, {email}, {phone}, {company}, {message}, {date}, {ip_address}
6. Test email feature: send test email to verify configuration
7. Email delivery status tracking: success, failed, pending
8. Failed email retry mechanism (up to 3 attempts)
9. Email logs: view sent emails, delivery status, timestamps
10. Save triggers configuration update (no page revalidation needed)
11. Success message: "Contact form settings updated"
12. Changes logged in audit log
13. Email service integration: SMTP, SendGrid, AWS SES, Mailgun
14. Spam protection: rate limiting (max 5 submissions per IP per hour)

**Edge Cases:**
- EC-1: Primary recipient email invalid → validation error
- EC-2: CC/BCC email invalid → validation error with specific email highlighted
- EC-3: More than 5 CC recipients → validation error: "Maximum 5 CC recipients allowed"
- EC-4: Auto-reply enabled but template empty → validation error
- EC-5: Template variable typo → warning: "Unknown variable {nam}, did you mean {name}?"
- EC-6: All form fields disabled → validation error: "At least Name, Email, and Message must be enabled"
- EC-7: No required fields selected → validation error: "Name, Email, and Message must be required"
- EC-8: Test email fails → error message with SMTP error details
- EC-9: Email service not configured → warning: "Configure email service in System Settings first"
- EC-10: CAPTCHA disabled → warning: "Disabling CAPTCHA may increase spam submissions"
- EC-11: Rate limit too high → warning: "High rate limit may allow spam attacks"
- EC-12: Email template exceeds size limit → validation error

**Validation Rules:**
- Primary recipient: required, valid email format
- CC/BCC recipients: optional, max 5 each, valid email format
- Auto-reply template: required if auto-reply enabled, max 2000 characters
- Notification template: required, max 3000 characters
- Success/Error messages: max 200 characters per language
- Email subject prefix: max 50 characters
- Rate limit: 1-10 submissions per hour per IP

**Security Considerations:**
- Only authorized roles can configure contact form
- All email addresses validated and sanitized
- Email templates sanitized to prevent injection
- CAPTCHA recommended to prevent spam
- Rate limiting to prevent abuse
- IP address logging for spam tracking
- Email content sanitized before sending
- Failed login attempts logged

**Responsive Design:**
- Mobile (375px): Single column form, full-width inputs
- Tablet (768px): Two-column layout for settings
- Desktop (1024px+): Multi-column layout with live preview

**Performance:**
- Settings load: < 300ms
- Save configuration: < 500ms
- Test email send: < 3 seconds
- Email delivery: < 5 seconds (async)
- Email logs load: < 500ms with pagination

**UX Considerations:**
- Email validation with suggestions
- Template variable autocomplete
- Live preview of email templates
- Test email with sample data
- Email delivery status indicators
- "Copy from English" button for bilingual templates
- Template variable helper (shows available variables)
- SMTP configuration wizard
- Email logs with search and filter
- Export email logs to CSV

---

## US-CMS-5.10 🔴 Content Versioning and History
**As a** Super Admin, Admin, or Founder (CTO), **I want to** view version history of content changes and restore previous versions, **so that** I can track changes and recover from mistakes.

**Acceptance Criteria:**
1. Version history accessible from any content editor (pages, services, projects)
2. Version history displays:
   - Version number (auto-incremented)
   - Editor name and role
   - Timestamp (date and time)
   - Change summary (auto-generated or manual)
   - Status at that version (Draft/Published)
   - Preview button
   - Restore button
3. Auto-save creates version every 30 seconds if changes detected
4. Manual save creates named version with optional change summary
5. Publish action creates version marked as "Published"
6. Version comparison: side-by-side diff view showing changes
7. Diff view highlights: additions (green), deletions (red), modifications (yellow)
8. Restore version: creates new version based on selected historical version
9. Restore confirmation dialog: "Restore version X? This will create a new version."
10. Version retention: keep all versions for 90 days, then keep monthly snapshots
11. Version search: filter by editor, date range, status
12. Version export: download specific version as JSON
13. Version limit: max 100 versions per content item, oldest auto-archived
14. Changes logged in audit log
15. Version preview: view content as it appeared in that version

**Edge Cases:**
- EC-1: Restore deleted content → content restored as draft with new version
- EC-2: Version limit reached → oldest version archived, warning shown
- EC-3: Multiple editors editing simultaneously → conflict detection, last save wins with warning
- EC-4: Restore version while unsaved changes exist → confirmation: "Discard unsaved changes?"
- EC-5: Version comparison with large content → diff view paginated
- EC-6: Version created by deleted user → shows "Deleted User" with original name
- EC-7: Restore version with missing media files → warning: "Some images may be missing"
- EC-8: Version preview with outdated styles → preview shows historical appearance
- EC-9: Export version with large media → export includes media URLs, not files
- EC-10: Version search with no results → empty state with clear message
- EC-11: Auto-save during network outage → saved locally, synced when online
- EC-12: Version comparison in Arabic → RTL diff view

**Validation Rules:**
- Change summary: optional, max 200 characters
- Version retention: 90 days for all, then monthly snapshots
- Version limit: max 100 versions per content item
- Restore: requires confirmation

**Security Considerations:**
- Only authorized roles can view version history
- Only Super Admin and Admin can restore versions
- Version data encrypted at rest
- Audit log tracks all restore actions
- Deleted versions archived, not permanently deleted
- Version access logged for compliance

**Responsive Design:**
- Mobile (375px): List view, stacked version cards, simplified diff
- Tablet (768px): Two-column layout, side-by-side diff
- Desktop (1024px+): Multi-column layout, detailed diff view

**Performance:**
- Version history load: < 500ms
- Version comparison: < 1 second
- Version restore: < 2 seconds
- Version preview: < 1 second
- Auto-save: < 200ms (background)

**UX Considerations:**
- Timeline view of versions with visual indicators
- Quick restore from version list
- Diff view with syntax highlighting for code
- Version tagging: mark important versions (e.g., "Before redesign")
- Version notes: add comments to versions
- Keyboard shortcuts: Ctrl+H (history), Ctrl+R (restore)
- Version comparison slider (drag to see changes)
- "Restore and Publish" quick action
- Version analytics: most restored versions, frequent editors
- Export version history to PDF report

---

## US-CMS-5.11 🟠 Media Library Management
**As a** Super Admin, Admin, or Founder (CTO), **I want to** manage all media files (images, videos, documents) in a centralized library, **so that** I can organize, reuse, and optimize media across the website.

**Acceptance Criteria:**
1. Media library accessible from CMS dashboard → Media Library
2. Media library displays: Thumbnail/Icon, Filename, Type, Size, Dimensions, Upload Date, Used In, Actions
3. **Upload Media:**
   - Drag-and-drop upload (single or multiple files)
   - File browser upload
   - Supported formats: Images (JPG, PNG, GIF, WebP, SVG), Videos (MP4, WebM), Documents (PDF)
   - Max file size: Images 10MB, Videos 50MB, Documents 20MB
   - Bulk upload: up to 20 files at once
   - Upload progress bar with cancel option
   - Auto-optimization: images converted to WebP, videos compressed
4. **Organize Media:**
   - Folders: create, rename, delete, move files between folders
   - Tags: add multiple tags per file, filter by tags
   - Search: by filename, tags, type, date range
   - Sort: by name, date, size, type, usage count
   - Bulk actions: move, tag, delete, download
5. **View Media Details:**
   - Full preview (images, videos, PDFs)
   - Metadata: filename, type, size, dimensions, upload date, uploader
   - Usage tracking: list of pages/content using this media
   - Alt text (bilingual, for images)
   - Caption (bilingual, optional)
   - SEO-friendly filename suggestion
6. **Edit Media:**
   - Rename file
   - Edit alt text and caption
   - Replace file (keeps same URL, updates all usages)
   - Image editing: crop, resize, rotate, flip
   - Generate responsive image sizes (thumbnail, small, medium, large)
7. **Delete Media:**
   - Confirmation dialog with usage warning
   - If media used in content → warning: "Used in X pages. Delete anyway?"
   - Soft delete (recoverable for 30 days)
   - Bulk delete with confirmation
8. Media library statistics: total files, total size, storage usage percentage
9. Storage quota: warning at 80%, error at 100%
10. Unused media detection: find files not used in any content
11. Duplicate detection: find duplicate files by content hash
12. CDN integration: auto-upload to CDN for faster delivery
13. Changes logged in audit log
14. Export media list to CSV

**Edge Cases:**
- EC-1: Upload file exceeds size limit → validation error per file
- EC-2: Upload unsupported format → validation error: "Format not supported"
- EC-3: Duplicate filename → auto-rename with suffix (e.g., image-1.jpg)
- EC-4: Delete media used in published content → warning with page list
- EC-5: Storage quota exceeded → upload blocked, warning shown
- EC-6: Folder name conflict → validation error: "Folder already exists"
- EC-7: Move file to non-existent folder → validation error
- EC-8: Replace file with different format → validation error: "Format must match"
- EC-9: Image editing on SVG → editing disabled, warning shown
- EC-10: Video preview not supported in browser → download link shown
- EC-11: Bulk delete with mixed usage → confirmation lists used files
- EC-12: CDN upload fails → fallback to local storage, retry option

**Validation Rules:**
- Images: JPG/PNG/GIF/WebP/SVG, max 10MB
- Videos: MP4/WebM, max 50MB
- Documents: PDF, max 20MB
- Filename: alphanumeric, hyphens, underscores, max 100 characters
- Alt text: max 200 characters per language
- Caption: max 500 characters per language
- Folder name: max 50 characters, no special characters

**Security Considerations:**
- Only authorized roles can upload/delete media
- File type validation (magic number check, not just extension)
- Virus scanning on upload
- SVG sanitization to remove scripts
- Media access logged for compliance
- Soft delete prevents accidental data loss
- CDN URLs signed for security

**Responsive Design:**
- Mobile (375px): List view, stacked cards, swipe actions
- Tablet (768px): Grid view (3 columns), drag-and-drop
- Desktop (1024px+): Grid view (4-6 columns), advanced filters, bulk actions

**Performance:**
- Media library load: < 1 second (paginated, 50 per page)
- Upload (single file): < 5 seconds
- Bulk upload (20 files): < 30 seconds with progress
- Image optimization: < 3 seconds per image
- Search and filter: < 300ms
- CDN upload: async, doesn't block user

**UX Considerations:**
- Drag-and-drop upload with visual feedback
- Grid/List view toggle
- Image preview on hover
- Quick actions: edit, delete, copy URL
- Bulk selection with checkboxes
- Folder breadcrumb navigation
- Tag autocomplete with suggestions
- Usage indicator (badge showing usage count)
- "Copy URL" button for quick sharing
- Keyboard shortcuts: Ctrl+U (upload), Del (delete)
- Image editor with undo/redo
- Responsive image size preview
- Storage usage chart

---

## US-CMS-5.12 🟠 Preview Before Publish
**As a** Super Admin, Admin, or Founder (CTO), **I want to** preview content changes before publishing, **so that** I can verify appearance and functionality across devices.

**Acceptance Criteria:**
1. Preview button available in all content editors (pages, services, projects)
2. Preview modes:
   - Desktop preview (1920px, 1440px, 1024px)
   - Tablet preview (768px, portrait and landscape)
   - Mobile preview (375px, 414px, portrait and landscape)
   - Custom size preview (enter width and height)
3. Preview opens in new tab/window with draft content
4. Preview URL: unique, temporary, expires after 24 hours
5. Preview banner at top: "Preview Mode - Changes not published" with "Exit Preview" button
6. Preview shows exact appearance: fonts, colors, images, layout
7. Preview interactive: test links, buttons, forms (non-functional, visual only)
8. Preview bilingual: toggle between Arabic and English
9. Preview with different user roles: view as public, logged-in user, admin
10. Share preview link: generate shareable link for stakeholder review
11. Preview comments: stakeholders can add comments on preview (optional feature)
12. Preview analytics: track preview views, devices used
13. Preview comparison: side-by-side current published vs. draft
14. Preview history: view previous preview sessions
15. Preview expires after 24 hours or when published

**Edge Cases:**
- EC-1: Preview with unsaved changes → prompt: "Save draft to preview?"
- EC-2: Preview link expired → message: "Preview expired. Generate new preview."
- EC-3: Preview on unsupported device size → closest supported size used
- EC-4: Preview with missing media → placeholder shown with warning
- EC-5: Preview with external links → links disabled, tooltip: "Link disabled in preview"
- EC-6: Preview with forms → form submission disabled, success message shown
- EC-7: Share preview link with unauthorized user → preview accessible (read-only)
- EC-8: Preview comments without login → prompt to enter name and email
- EC-9: Preview comparison with no published version → comparison disabled
- EC-10: Multiple preview sessions → each gets unique URL
- EC-11: Preview in Arabic with RTL → layout mirrors correctly
- EC-12: Preview with custom CSS → CSS applied correctly

**Validation Rules:**
- Preview URL: unique, random token, expires in 24 hours
- Custom preview size: min 320px, max 2560px width
- Share preview link: optional password protection
- Preview comments: max 500 characters per comment

**Security Considerations:**
- Preview URLs use secure random tokens
- Preview content not indexed by search engines (noindex meta tag)
- Preview links expire after 24 hours
- Optional password protection for shared previews
- Preview access logged for audit
- Preview comments moderated (optional)

**Responsive Design:**
- Preview frame responsive to selected device size
- Preview controls: device selector, orientation toggle, zoom
- Preview banner: fixed at top, doesn't interfere with content

**Performance:**
- Preview generation: < 2 seconds
- Preview load: < 1 second
- Device size switch: < 200ms
- Preview comparison: < 1 second
- Share link generation: < 500ms

**UX Considerations:**
- Device frame around preview (looks like actual device)
- Quick device switcher (icons for mobile, tablet, desktop)
- Zoom controls: 50%, 75%, 100%, 125%, 150%
- Orientation toggle for mobile/tablet
- "Copy Preview Link" button
- Preview QR code for mobile testing
- Side-by-side comparison slider
- Preview comments with threading
- "Approve and Publish" button for stakeholders
- Keyboard shortcuts: P (preview), Esc (exit preview)
- Preview loading indicator
- Preview error handling with helpful messages

---

## US-CMS-5.13 🟠 Instant Content Revalidation
**As a** Super Admin, Admin, or Founder (CTO), **I want to** see content changes reflected immediately on the live website, **so that** updates are visible without delay.

**Acceptance Criteria:**
1. Revalidation triggered automatically on:
   - Publish action (pages, services, projects)
   - SEO settings update
   - Footer content update
   - Service/Project reordering
   - Media file replacement
2. Revalidation process:
   - Identifies affected pages (e.g., home page if service updated)
   - Clears cache for affected pages
   - Regenerates static pages (if using SSG)
   - Updates CDN cache
   - Verifies revalidation success
3. Revalidation status indicator:
   - "Revalidating..." progress message
   - "Revalidation complete" success message
   - "Revalidation failed" error message with retry option
4. Revalidation time: < 5 seconds for single page, < 30 seconds for multiple pages
5. Revalidation log: timestamp, pages affected, status, duration
6. Manual revalidation: "Revalidate Now" button for any page
7. Bulk revalidation: "Revalidate All Pages" button (Super Admin only)
8. Revalidation queue: if multiple updates, queue and process sequentially
9. Revalidation notification: toast message with progress and completion
10. Revalidation webhook: notify external services (optional)
11. Revalidation analytics: track revalidation frequency, duration, failures
12. Revalidation retry: auto-retry up to 3 times on failure
13. Revalidation cache strategy: stale-while-revalidate for zero downtime
14. Revalidation preview: "View Live Page" button after revalidation

**Edge Cases:**
- EC-1: Revalidation during high traffic → queued, processed when server available
- EC-2: Revalidation fails → error message with details, manual retry option
- EC-3: Multiple revalidations triggered simultaneously → queued, processed sequentially
- EC-4: Revalidation timeout (>60 seconds) → marked as failed, retry option
- EC-5: CDN cache not cleared → warning: "CDN cache may take up to 5 minutes to clear"
- EC-6: Revalidation of deleted page → page removed from cache
- EC-7: Revalidation with network error → retry automatically, notify user
- EC-8: Revalidation log exceeds storage → old logs archived
- EC-9: Bulk revalidation with 100+ pages → progress bar, estimated time
- EC-10: Revalidation webhook fails → logged, doesn't block revalidation
- EC-11: Revalidation during deployment → queued until deployment complete
- EC-12: Revalidation in Arabic → both Arabic and English versions revalidated

**Validation Rules:**
- Revalidation timeout: 60 seconds per page
- Retry attempts: max 3 times
- Queue size: max 100 revalidation requests
- Bulk revalidation: Super Admin only

**Security Considerations:**
- Only authorized roles can trigger revalidation
- Revalidation API secured with authentication
- Rate limiting: max 10 revalidations per minute per user
- Revalidation logs access controlled
- Webhook URLs validated and secured

**Responsive Design:**
- Revalidation status: toast notification (all devices)
- Revalidation log: responsive table (mobile: stacked, desktop: table)

**Performance:**
- Single page revalidation: < 5 seconds
- Multiple pages revalidation: < 30 seconds
- Bulk revalidation (all pages): < 2 minutes
- Revalidation queue processing: < 1 second per page
- CDN cache clear: < 5 minutes (varies by CDN)

**UX Considerations:**
- Real-time revalidation status with progress bar
- Toast notifications for revalidation events
- "View Live Page" button after successful revalidation
- Revalidation log with search and filter
- Revalidation analytics dashboard
- Estimated time remaining for bulk revalidation
- "Revalidate and View" quick action
- Keyboard shortcut: Ctrl+Shift+R (revalidate)
- Revalidation history per page
- Revalidation failure troubleshooting guide

---

## US-CMS-5.14 🟡 Content Scheduling
**As a** Super Admin, Admin, or Founder (CTO), **I want to** schedule content to be published or unpublished at specific dates and times, **so that** I can plan content releases in advance.

**Acceptance Criteria:**
1. Scheduling available for: pages, services, projects, blog posts
2. Schedule options:
   - Publish at: date and time picker
   - Unpublish at: date and time picker (optional)
   - Timezone: auto-detected, editable
   - Recurring schedule: daily, weekly, monthly (optional)
3. Scheduled content status: "Scheduled" badge with publish date
4. Scheduled content list: view all scheduled items, sort by publish date
5. Schedule editor:
   - Calendar view: see scheduled content on calendar
   - List view: table with content, publish date, status
   - Filter by: content type, date range, status
6. Schedule actions:
   - Edit schedule: change publish/unpublish date
   - Cancel schedule: revert to draft
   - Publish now: override schedule, publish immediately
7. Schedule notifications:
   - Email notification 24 hours before publish
   - Email notification on successful publish
   - Email notification on failed publish
8. Schedule execution:
   - Cron job checks every 5 minutes
   - Publishes content at scheduled time (±5 minutes)
   - Triggers revalidation after publish
   - Logs publish action in audit log
9. Schedule conflicts: warn if multiple items scheduled at same time
10. Schedule preview: preview scheduled content before publish date
11. Schedule history: view past scheduled publishes
12. Schedule export: export schedule to calendar file (ICS)
13. Schedule permissions: only authorized roles can schedule
14. Schedule timezone handling: converts to server timezone for execution

**Edge Cases:**
- EC-1: Schedule in the past → validation error: "Publish date must be in the future"
- EC-2: Unpublish date before publish date → validation error
- EC-3: Schedule execution fails → retry 3 times, notify admin
- EC-4: Content edited after scheduling → schedule preserved, updated content published
- EC-5: Content deleted after scheduling → schedule cancelled automatically
- EC-6: Server time mismatch → uses UTC, converts to user timezone for display
- EC-7: Daylight saving time change → schedule adjusted automatically
- EC-8: Multiple schedules for same content → latest schedule overrides previous
- EC-9: Schedule during server maintenance → queued, executed after maintenance
- EC-10: Recurring schedule with end date → stops after end date
- EC-11: Schedule notification email fails → logged, doesn't block publish
- EC-12: Schedule in different timezone → converted correctly, displayed in user timezone

**Validation Rules:**
- Publish date: must be in the future
- Unpublish date: must be after publish date (if provided)
- Timezone: valid timezone identifier
- Recurring schedule: valid frequency (daily, weekly, monthly)
- Schedule limit: max 100 scheduled items per user

**Security Considerations:**
- Only authorized roles can schedule content
- Schedule execution logged in audit log
- Schedule notifications sent to authorized users only
- Schedule API secured with authentication
- Rate limiting: max 50 schedule actions per hour per user

**Responsive Design:**
- Mobile (375px): List view, date picker optimized for touch
- Tablet (768px): Calendar view with day/week view
- Desktop (1024px+): Calendar view with month view, side panel for details

**Performance:**
- Schedule list load: < 500ms
- Schedule creation: < 500ms
- Schedule execution: < 5 seconds (includes revalidation)
- Calendar view load: < 1 second
- Cron job execution: < 30 seconds per run

**UX Considerations:**
- Date and time picker with timezone selector
- Calendar view with drag-and-drop rescheduling
- Schedule preview with countdown timer
- "Schedule and Forget" workflow
- Schedule templates for recurring content
- Bulk scheduling for multiple items
- Schedule conflict warnings
- Email notification preferences
- Schedule analytics: most scheduled content types, peak scheduling times
- Keyboard shortcuts: Ctrl+Shift+S (schedule)
- Schedule reminder notifications
- ICS export for external calendar integration

---

## US-CMS-5.15 🔴 Multi-Language Content Management (Arabic/English)
**As a** Super Admin, Admin, or Founder (CTO), **I want to** manage content in both Arabic and English with proper RTL support, **so that** the website serves both Arabic and English audiences effectively.

**Acceptance Criteria:**
1. Language switcher in CMS: toggle between Arabic and English editing modes
2. All content fields support bilingual input:
   - Text fields: separate inputs for Arabic and English
   - Rich text editors: RTL support for Arabic, LTR for English
   - Media: shared across languages, alt text bilingual
   - URLs: language-specific slugs (e.g., /ar/services, /en/services)
3. Language-specific validation:
   - Arabic: RTL text direction, Arabic characters validation
   - English: LTR text direction, Latin characters validation
4. Translation workflow:
   - "Copy from English" button for Arabic fields (translation helper)
   - "Copy from Arabic" button for English fields
   - Translation status: Complete, Partial, Missing
   - Translation progress indicator per content item
5. Language fallback:
   - If Arabic content missing → show English content with language indicator
   - If English content missing → show Arabic content with language indicator
6. Language-specific SEO:
   - Separate meta tags for each language
   - Hreflang tags automatically generated
   - Language-specific sitemaps
7. Language-specific URLs:
   - /ar/ prefix for Arabic pages
   - /en/ prefix for English pages
   - Default language: English (no prefix) or configurable
8. RTL layout support:
   - Arabic content: right-to-left layout
   - English content: left-to-right layout
   - Layout mirrors correctly (navigation, forms, buttons)
9. Language switcher on public website:
   - Toggle between Arabic and English
   - Preserves current page (e.g., /en/about → /ar/about)
   - Language preference saved in cookie
10. Translation completeness check:
    - Warning if publishing with incomplete translations
    - Translation report: list missing translations per page
11. Language-specific content:
    - Some content may be language-specific (e.g., Arabic-only blog post)
    - Language availability toggle per content item
12. Bilingual search: search works in both languages
13. Language analytics: track language preference, page views per language
14. Language export/import: export content for translation, import translated content

**Edge Cases:**
- EC-1: Publish with missing Arabic translation → warning: "Arabic translation incomplete"
- EC-2: Publish with missing English translation → warning: "English translation incomplete"
- EC-3: URL slug conflict between languages → validation error with suggestion
- EC-4: Mixed RTL/LTR content in rich text → handled correctly with direction markers
- EC-5: Language switcher on page without translation → redirects to home page in selected language
- EC-6: Search query in Arabic → searches Arabic content, shows Arabic results
- EC-7: Media alt text missing in one language → uses other language as fallback
- EC-8: Language preference cookie expired → defaults to browser language or English
- EC-9: Copy from English with formatting → formatting preserved in Arabic field
- EC-10: Translation import with invalid format → validation error with format guide
- EC-11: Language-specific content not available in other language → 404 or redirect
- EC-12: Bilingual email templates → language selected based on user preference

**Validation Rules:**
- Arabic content: RTL text direction, Arabic characters recommended
- English content: LTR text direction, Latin characters recommended
- URL slugs: unique per language, alphanumeric and hyphens only
- Translation status: Complete (100%), Partial (1-99%), Missing (0%)

**Security Considerations:**
- Language-specific content access controlled
- Translation import validated and sanitized
- URL slugs validated to prevent injection
- Language preference cookie secured (HttpOnly, Secure)

**Responsive Design:**
- Mobile (375px): Language switcher in mobile menu
- Tablet (768px): Language switcher in header
- Desktop (1024px+): Language switcher in header, prominent position

**Performance:**
- Language switch: < 200ms
- Translation load: < 500ms
- Bilingual search: < 500ms
- Translation export: < 2 seconds
- Translation import: < 5 seconds

**UX Considerations:**
- Side-by-side editing: view Arabic and English fields together
- Translation progress bar per content item
- "Translate All" button for bulk translation (using translation service)
- Translation memory: suggest previous translations
- Language-specific preview
- Translation completeness dashboard
- "Copy from English" with auto-translation option (Google Translate API)
- RTL preview for Arabic content
- Language-specific character counters
- Bilingual spell checker
- Translation export to XLIFF format for professional translation
- Translation import with validation and preview

---

## US-CMS-5.16 🟠 Navigation Menu Management
**As a** Super Admin, Admin, or Founder (CTO), **I want to** manage the website navigation menu structure, **so that** I can control the site hierarchy and user navigation experience.

**Acceptance Criteria:**
1. Navigation menu editor accessible from CMS dashboard → Site Settings → Navigation
2. Menu types:
   - Header menu (main navigation)
   - Footer menu (footer links)
   - Mobile menu (mobile-specific navigation)
3. Menu item types:
   - Internal page link (select from pages)
   - External link (custom URL)
   - Dropdown/Submenu (nested items, max 2 levels)
   - Mega menu (multi-column dropdown, optional)
   - Custom HTML (for special items)
4. Menu item fields (bilingual):
   - Label (max 50 characters)
   - URL (internal or external)
   - Icon (optional, select from library)
   - Description (for mega menu, max 100 characters)
   - Open in new tab (toggle)
   - CSS class (for custom styling)
5. Menu structure:
   - Drag-and-drop reordering
   - Nested items (indent/outdent)
   - Visual hierarchy indicator
   - Expand/collapse nested items
6. Menu item actions:
   - Add item
   - Edit item
   - Delete item
   - Duplicate item
   - Move item (up, down, indent, outdent)
7. Menu visibility:
   - Show/hide toggle per item
   - Conditional visibility (logged in, logged out, role-based)
   - Schedule visibility (show between dates)
8. Menu preview:
   - Live preview of menu structure
   - Preview on different devices (mobile, tablet, desktop)
   - Preview with different user roles
9. Menu templates:
   - Save menu structure as template
   - Load template for quick setup
   - Default templates: Standard, Minimal, Full
10. Menu validation:
    - Warn if menu item links to non-existent page
    - Warn if menu too deep (>2 levels)
    - Warn if menu too wide (>7 items)
11. Save triggers instant revalidation of all pages
12. Success message: "Navigation menu updated and all pages revalidated"
13. Changes logged in audit log
14. Menu export/import (JSON format)

**Edge Cases:**
- EC-1: Menu item label exceeds 50 characters → validation error
- EC-2: Menu item links to deleted page → warning: "Page not found"
- EC-3: Menu item external URL invalid → validation error
- EC-4: Menu nesting exceeds 2 levels → validation error: "Maximum 2 levels allowed"
- EC-5: Menu item with no label → validation error: "Label is required"
- EC-6: Duplicate menu item labels → warning: "Duplicate label detected"
- EC-7: Menu item visibility conflict → warning: "Item hidden for all users"
- EC-8: Mega menu with no items → validation error: "Add at least one item"
- EC-9: Menu item custom HTML with scripts → sanitized automatically
- EC-10: Menu import with invalid format → validation error with format guide
- EC-11: Menu too wide (>10 items) → warning: "Consider using dropdown menus"
- EC-12: Arabic menu with RTL → menu layout mirrors correctly

**Validation Rules:**
- Menu item label: required, max 50 characters per language
- Menu item URL: required, valid URL format
- Menu nesting: max 2 levels
- Menu width: recommended max 7 items, warning at 10+
- Custom HTML: sanitized, no scripts allowed

**Security Considerations:**
- Only authorized roles can edit navigation
- All input sanitized to prevent XSS
- External URLs validated
- Custom HTML sanitized (whitelist: a, span, strong, em)
- Menu changes logged in audit log

**Responsive Design:**
- Mobile (375px): Hamburger menu, vertical layout, collapsible submenus
- Tablet (768px): Horizontal menu, dropdown submenus
- Desktop (1024px+): Full horizontal menu, mega menu support

**Performance:**
- Menu editor load: < 500ms
- Save and revalidate: < 5 seconds
- Drag-and-drop reorder: < 100ms response
- Menu preview: < 200ms
- Menu render on website: < 50ms

**UX Considerations:**
- Drag-and-drop with visual feedback
- Indent/outdent buttons for nesting
- Expand/collapse all button
- Menu item preview on hover
- "Add Page" quick action (select from pages)
- Menu structure visualization (tree view)
- Keyboard shortcuts: N (new item), E (edit), D (delete)
- Undo/Redo for menu changes
- Menu item search and filter
- Bulk actions: show, hide, delete
- Menu analytics: most clicked items, navigation paths
- A/B testing for menu structures (optional)

---

## US-CMS-5.17 🟠 Blog Post Management (CRUD)
**As a** Super Admin, Admin, or Founder (CTO), **I want to** create, read, update, and delete blog posts, **so that** I can publish content marketing articles and company updates.

**Acceptance Criteria:**
1. Blog management accessible from CMS dashboard → Blog
2. Blog post list displays: Featured Image, Title, Author, Category, Status, Published Date, Views, Actions
3. **Create Blog Post:**
   - Required fields: Title (bilingual), Content (rich text, bilingual), Featured Image, Category, Author
   - Optional fields: Excerpt (bilingual), Tags, SEO fields, Related Posts, Call-to-Action
   - Rich text editor: full formatting, images, videos, code blocks, embeds
   - Featured image: max 5MB, JPG/PNG/WebP, recommended 1200x630px
   - Category: select from list or create new
   - Tags: multi-select or create new, max 10 tags
   - Author: auto-assigned to current user, editable
   - Status: Draft, Published, Scheduled
   - Publish date: auto (now) or scheduled
   - URL slug: auto-generated from title, editable
4. **View Blog Posts:**
   - List view with pagination (20 per page)
   - Grid view with post cards
   - Search by title, content, author, tags
   - Filter by: Status, Category, Author, Date Range, Tags
   - Sort by: Published Date, Title, Views, Comments
5. **Update Blog Post:**
   - Edit all fields
   - Change status (Draft ↔ Published)
   - Update publish date
   - Version history tracked
6. **Delete Blog Post:**
   - Confirmation dialog with warning
   - Soft delete (recoverable for 30 days)
   - Deletion logged in audit log
7. Blog post preview: preview before publish
8. Blog post analytics: views, time on page, social shares, comments
9. Related posts: auto-suggest based on category and tags, manual selection
10. Social sharing: auto-generate social media cards (OG, Twitter)
11. Comments: enable/disable per post, moderation required
12. Save triggers instant revalidation of blog page and post page
13. Success messages: "Blog post created", "Blog post updated", "Blog post deleted"
14. Changes logged in audit log
15. Duplicate post feature (copies all fields, adds "Copy" suffix)

**Edge Cases:**
- EC-1: Title exceeds character limit → validation error
- EC-2: No featured image → validation error: "Featured image is required"
- EC-3: Featured image exceeds 5MB → validation error, suggest compression
- EC-4: URL slug conflict → validation error: "Slug already exists"
- EC-5: Publish with empty content → validation error: "Content is required"
- EC-6: More than 10 tags → validation error: "Maximum 10 tags allowed"
- EC-7: Delete post with comments → warning: "Post has X comments. Delete anyway?"
- EC-8: Rich text with malicious HTML → sanitized automatically
- EC-9: Embed code with scripts → sanitized, warning shown
- EC-10: Related posts deleted → automatically removed from related list
- EC-11: Author deleted → shows "Deleted User" with original name
- EC-12: Arabic content with RTL → rich text editor supports RTL

**Validation Rules:**
- Title: required, max 100 characters per language
- Content: required, max 50,000 characters per language
- Excerpt: optional, max 300 characters per language
- Featured Image: required, JPG/PNG/WebP, max 5MB
- Category: required, select from list
- Tags: optional, max 10 tags
- URL slug: required, unique, alphanumeric and hyphens only
- Author: required, select from users

**Security Considerations:**
- Only authorized roles can manage blog posts
- Rich text content sanitized to prevent XSS
- HTML tags whitelist: p, strong, em, u, ul, ol, li, a, h2, h3, h4, blockquote, code, pre, img
- Embed codes sanitized (whitelist: YouTube, Vimeo, Twitter)
- Image files scanned for malicious content
- Comments moderated before publishing
- All CRUD operations logged

**Responsive Design:**
- Mobile (375px): List view, stacked cards, swipe actions
- Tablet (768px): Grid view (2 columns), rich text editor optimized
- Desktop (1024px+): Grid view (3 columns), full-featured editor

**Performance:**
- Blog post list load: < 500ms
- Create/Update/Delete: < 1 second
- Rich text editor load: < 300ms
- Featured image upload: < 3 seconds
- Search and filter: < 200ms
- Blog post page load: < 1 second

**UX Considerations:**
- Rich text editor with formatting toolbar
- Distraction-free writing mode
- Auto-save draft every 30 seconds
- Word count and reading time estimate
- SEO score indicator
- "Copy from English" button for Arabic fields
- Tag autocomplete with suggestions
- Category management inline
- Related posts auto-suggest
- Social media preview cards
- Keyboard shortcuts: Ctrl+S (save), Ctrl+P (preview)
- Version history with restore
- Duplicate post for quick creation
- Bulk actions: publish, unpublish, delete, change category

---

## US-CMS-5.18 🟡 Analytics Dashboard for Content Performance
**As a** Super Admin, Admin, or Founder (CTO), **I want to** view analytics for content performance, **so that** I can understand what content resonates with visitors and make data-driven decisions.

**Acceptance Criteria:**
1. Analytics dashboard accessible from CMS dashboard → Analytics
2. Dashboard overview:
   - Total page views (last 7 days, 30 days, 90 days, custom range)
   - Unique visitors
   - Bounce rate
   - Average time on page
   - Top performing pages (by views)
   - Top performing blog posts (by views)
   - Top traffic sources (direct, search, social, referral)
   - Device breakdown (mobile, tablet, desktop)
   - Language preference (Arabic vs. English)
3. Page-level analytics:
   - Views per page
   - Unique visitors per page
   - Average time on page
   - Bounce rate per page
   - Exit rate per page
   - Conversion rate (if CTA clicked)
   - Scroll depth (how far users scroll)
4. Content-level analytics:
   - Service card views and CTA clicks
   - Project views and external link clicks
   - Blog post views, time on page, social shares
   - Contact form submissions
   - Newsletter signups
5. User behavior analytics:
   - Navigation paths (most common user journeys)
   - Heatmaps (click heatmaps, scroll heatmaps)
   - Session recordings (optional, privacy-compliant)
6. SEO analytics:
   - Search engine rankings for target keywords
   - Organic search traffic
   - Click-through rate from search results
   - Top search queries leading to site
7. Social media analytics:
   - Social shares per page/post
   - Social traffic sources (Facebook, Twitter, LinkedIn)
   - Social engagement (likes, comments, shares)
8. Real-time analytics:
   - Current active users
   - Pages being viewed right now
   - Real-time traffic sources
9. Analytics export:
   - Export to CSV, PDF, Excel
   - Scheduled reports (daily, weekly, monthly)
   - Email reports to stakeholders
10. Analytics integration:
    - Google Analytics integration
    - Google Search Console integration
    - Facebook Pixel integration
    - Custom analytics events
11. Analytics filters:
    - Date range selector
    - Device filter (mobile, tablet, desktop)
    - Language filter (Arabic, English)
    - Traffic source filter
    - User role filter (public, logged-in)
12. Analytics visualization:
    - Line charts for trends
    - Bar charts for comparisons
    - Pie charts for distributions
    - Tables for detailed data
13. Analytics goals:
    - Define conversion goals (e.g., contact form submission)
    - Track goal completions
    - Funnel visualization
14. Analytics alerts:
    - Alert when traffic drops >20%
    - Alert when page load time >3 seconds
    - Alert when bounce rate >70%

**Edge Cases:**
- EC-1: No analytics data available → empty state with setup guide
- EC-2: Analytics integration not configured → warning with setup link
- EC-3: Date range with no data → message: "No data for selected period"
- EC-4: Export with large dataset → progress bar, async export
- EC-5: Real-time analytics with no active users → message: "No active users"
- EC-6: Heatmap for page with low traffic → warning: "Insufficient data for heatmap"
- EC-7: Session recordings disabled → feature unavailable, privacy notice
- EC-8: Analytics API rate limit exceeded → cached data shown, warning displayed
- EC-9: Custom date range exceeds 1 year → validation error
- EC-10: Analytics alert triggered → email notification sent
- EC-11: Analytics data delayed → message: "Data may be delayed up to 24 hours"
- EC-12: Analytics for deleted content → data retained, marked as deleted

**Validation Rules:**
- Date range: max 1 year
- Custom date range: start date before end date
- Export format: CSV, PDF, Excel
- Scheduled reports: daily, weekly, monthly
- Analytics goals: max 10 goals

**Security Considerations:**
- Only authorized roles can view analytics
- Analytics data access logged
- User privacy: IP addresses anonymized
- GDPR compliance: cookie consent required
- Session recordings: opt-in only, exclude sensitive data
- Analytics export: access controlled

**Responsive Design:**
- Mobile (375px): Stacked charts, simplified view, swipe between metrics
- Tablet (768px): Two-column layout, interactive charts
- Desktop (1024px+): Multi-column dashboard, detailed charts, side-by-side comparisons

**Performance:**
- Dashboard load: < 2 seconds
- Chart rendering: < 500ms
- Real-time data update: every 30 seconds
- Export generation: < 10 seconds
- Heatmap generation: < 5 seconds

**UX Considerations:**
- Interactive charts with tooltips
- Date range quick selectors (Today, Last 7 days, Last 30 days, Last 90 days)
- Comparison mode (compare two date ranges)
- Metric cards with trend indicators (↑ increase, ↓ decrease)
- Drill-down capability (click chart to see details)
- Dashboard customization (add/remove widgets)
- Saved views (save custom dashboard configurations)
- Analytics insights (AI-generated insights and recommendations)
- Goal funnel visualization
- Keyboard shortcuts: R (refresh), E (export)
- Analytics glossary (explain metrics)
- Benchmark comparison (compare to industry averages)

---

---

## Summary and Implementation Notes

### Overview
This document contains **18 comprehensive user stories** for the Landing Page CMS module of the Tarqumi CRM system. The CMS enables authorized users (Super Admin, Admin, and Founder/CTO) to manage all public-facing website content including pages, services, projects, blog posts, and site settings.

### Key Features Covered

#### Content Management (Stories 5.1-5.7, 5.17)
- **SEO Settings**: Comprehensive SEO management for all pages with bilingual support
- **Page Editing**: Hero sections, services preview, projects preview, about page
- **Service Cards**: Full CRUD operations with drag-and-drop reordering
- **Showcase Projects**: Portfolio management with gallery support
- **Blog Posts**: Complete blogging platform with rich text editing

#### Site Configuration (Stories 5.8-5.9, 5.16)
- **Footer Management**: Logo, contact info, social links, newsletter signup
- **Contact Form**: Email routing, auto-replies, spam protection
- **Navigation Menus**: Header, footer, and mobile menu management with nested items

#### Advanced Features (Stories 5.10-5.15, 5.18)
- **Version Control**: Complete version history with restore capability
- **Media Library**: Centralized media management with optimization
- **Preview System**: Multi-device preview before publishing
- **Revalidation**: Instant content updates with cache management
- **Scheduling**: Content scheduling with timezone support
- **Multi-Language**: Full Arabic/English support with RTL layout
- **Analytics**: Comprehensive performance tracking and insights

### Technical Requirements

#### Performance Targets
- Page load: < 1 second
- Content save: < 2 seconds
- Revalidation: < 5 seconds (single page), < 30 seconds (multiple pages)
- Media upload: < 5 seconds per file
- Search/filter: < 300ms

#### Security Requirements
- Role-based access control (Super Admin, Admin, CTO only)
- Input sanitization to prevent XSS attacks
- File upload validation and virus scanning
- Audit logging for all CRUD operations
- Soft delete for data recovery (30-day retention)
- HTTPS required for all CMS operations

#### Responsive Design
- Mobile-first approach (375px minimum)
- Tablet optimization (768px)
- Desktop full features (1024px+)
- Touch-friendly controls for mobile/tablet
- Responsive preview for content editing

#### Bilingual Support
- All content fields support Arabic and English
- RTL layout for Arabic content
- Language-specific SEO and URLs
- Translation workflow with progress tracking
- Language fallback mechanism

### Integration Points

#### External Services
- **Email**: SMTP, SendGrid, AWS SES, Mailgun
- **Analytics**: Google Analytics, Google Search Console
- **CDN**: CloudFront, Cloudflare, or similar
- **Media**: S3 or similar object storage
- **Translation**: Google Translate API (optional)

#### Internal Systems
- **Authentication**: Integrates with CRM user management
- **Audit Log**: All changes logged for compliance
- **Notifications**: Email notifications for scheduled content, alerts
- **Search**: Full-text search across all content

### Implementation Priority

#### Phase 1 - Critical (🔴)
1. SEO Settings (5.1)
2. Hero Section (5.2)
3. Services Preview (5.3)
4. Projects Preview (5.4)
5. About Page (5.5)
6. Service Cards CRUD (5.6)
7. Showcase Projects CRUD (5.7)
8. Footer Content (5.8)
9. Version Control (5.10)
10. Revalidation (5.13)
11. Multi-Language (5.15)

#### Phase 2 - High Priority (🟠)
1. Contact Form Config (5.9)
2. Media Library (5.11)
3. Preview System (5.12)
4. Navigation Menus (5.16)
5. Blog Posts (5.17)

#### Phase 3 - Nice-to-Have (🟡)
1. Content Scheduling (5.14)
2. Analytics Dashboard (5.18)

### User Roles and Permissions

| Feature | Super Admin | Admin | CTO | Other Roles |
|---------|-------------|-------|-----|-------------|
| Edit SEO Settings | ✅ | ✅ | ✅ | ❌ |
| Edit Page Content | ✅ | ✅ | ✅ | ❌ |
| Manage Services | ✅ | ✅ | ✅ | ❌ |
| Manage Projects | ✅ | ✅ | ✅ | ❌ |
| Manage Blog | ✅ | ✅ | ✅ | ❌ |
| Edit Footer | ✅ | ✅ | ✅ | ❌ |
| Configure Contact Form | ✅ | ✅ | ✅ | ❌ |
| View Version History | ✅ | ✅ | ✅ | ❌ |
| Restore Versions | ✅ | ✅ | ❌ | ❌ |
| Manage Media Library | ✅ | ✅ | ✅ | ❌ |
| Preview Content | ✅ | ✅ | ✅ | ❌ |
| Trigger Revalidation | ✅ | ✅ | ✅ | ❌ |
| Bulk Revalidate All | ✅ | ❌ | ❌ | ❌ |
| Schedule Content | ✅ | ✅ | ✅ | ❌ |
| Manage Navigation | ✅ | ✅ | ✅ | ❌ |
| View Analytics | ✅ | ✅ | ✅ | ❌ |

### Testing Considerations

#### Unit Tests
- Content validation logic
- URL slug generation
- SEO meta tag generation
- Language detection and fallback
- Media file validation

#### Integration Tests
- Content save and revalidation flow
- Media upload and optimization
- Email sending (contact form, notifications)
- Version control and restore
- Multi-language content switching

#### E2E Tests
- Complete content creation workflow
- Publish and preview flow
- Media library upload and usage
- Navigation menu editing
- Blog post creation and publishing

#### Performance Tests
- Load testing for revalidation
- Media upload with large files
- Bulk operations (revalidate all, bulk delete)
- Analytics dashboard with large datasets

### Accessibility Requirements
- WCAG 2.1 Level AA compliance
- Keyboard navigation for all CMS features
- Screen reader support
- Color contrast ratios (4.5:1 minimum)
- Focus indicators for all interactive elements
- Alt text required for all images
- Semantic HTML structure

### Data Retention and Backup
- Soft delete: 30 days retention
- Version history: 90 days full, then monthly snapshots
- Media files: permanent storage with CDN backup
- Audit logs: 1 year retention
- Analytics data: 2 years retention
- Automated daily backups of all content

### Monitoring and Alerts
- Revalidation failures
- Email delivery failures
- Media upload errors
- High bounce rate (>70%)
- Traffic drops (>20%)
- Page load time (>3 seconds)
- Storage quota (>80%)

### Future Enhancements (Not in Current Scope)
- A/B testing for content variations
- Personalization based on user behavior
- Advanced workflow with approval process
- Content collaboration (multiple editors)
- AI-powered content suggestions
- Advanced SEO recommendations
- Automated image optimization
- Video transcoding and streaming
- Progressive Web App (PWA) support
- Headless CMS API for mobile apps

---

**Document Version**: 1.0  
**Last Updated**: 2024  
**Total User Stories**: 18  
**Total Acceptance Criteria**: 237  
**Total Edge Cases**: 216  

**Status**: ✅ Complete and Ready for Implementation
