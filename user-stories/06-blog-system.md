# Blog System User Stories

> **Format**: Each story follows the pattern:
> `As a [role], I want to [action], so that [benefit].`
>
> **Priority Levels**: 🔴 Critical | 🟠 High | 🟡 Medium | 🟢 Nice-to-have
>
> **Acceptance Criteria (AC)**: Numbered conditions that MUST be true for the story to be complete.
>
> **Edge Cases (EC)**: Scenarios that must be handled gracefully.

---

## US-BLOG-6.1 🔴 Create Blog Post with Bilingual Content and SEO
**As a** Super Admin, Admin, or Founder (CTO), **I want to** create a new blog post with bilingual content and comprehensive SEO settings, **so that** I can publish engaging content that ranks well in search engines.

**Acceptance Criteria:**
1. Blog post creation accessible from CMS dashboard → Blog → New Post
2. Required fields (bilingual - Arabic and English):
   - Title (max 100 characters with counter)
   - Slug (auto-generated from title, editable, URL-safe)
   - Excerpt/Summary (max 300 characters with counter)
   - Content (rich text editor with full formatting)
   - Featured Image (upload or select from media library)
   - Author (select from team members with Author/Admin/Super Admin roles)
   - Category (select from predefined categoers, optional)
3. **SEO Fields (bilingual):**
   - Meta Title (max 60 characters, defaults to post title)
   - Meta Description (max 160 characters, defaults to excerpt)
   - Meta Keywords (comma-separated, max 10 keywords)
   - OG Title (defaults to meta title)
   - OG Description (defaults to meta description)
   - OG Image (defaults to featured image, can override)
   - Twitter Card Type (Summary, Summary Large Image)
   - Canonical URL (auto-generated, editable)
   - Focus Keyword (for SEO analysis)
4. **Categorization:**
   - Categories (multi-select, at least 1 required)
   - Tags (multi-select, optional, max 10 tags)
   - Author (auto-assigned to current user, changeable by admin)
   - Publication Date (defaults to now, can schedule future)
5. **Status Management:**
   - Status: Draft, Published, Scheduled
   - Visibility: Public, Private, Password Protected
   - Publish Date/Time (for scheduled posts)
6. Rich text editor features: headings (H2-H6), bold, italic, underline, strikethrough, lists (ordered/unordered), links, images, code blocks, blockquotes, tables, horizontal rules
7. Character counters for all text fields with color coding (green > 80%, yellow 50-80%, red < 50%)
8. SEO score indicator (0-100) based on: keyword usage, readability, meta tags, image alt text, internal links
9. Readability score (Flesch Reading Ease) with suggestions
10. Live preview panel shows post as it will appear on website
11. Preview responsive: toggle between mobile, tablet, desktop views
12. Auto-save draft every 30 seconds
13. "Save Draft", "Preview", and "Publish" buttons
14. Success message: "Blog post created and published" or "Blog post saved as draft"
15. Changes logged in audit log with author, timestamp, action

**Edge Cases:**
- EC-1: Title exceeds 200 characters → validation error with character count
- EC-2: Slug already exists → validation error: "Slug must be unique. Suggestion: [slug-2]"
- EC-3: Slug contains special characters → auto-sanitized to URL-friendly format
- EC-4: Featured image exceeds 10MB → validation error, suggest compression
- EC-5: Featured image missing → warning: "Featured image recommended for social sharing"
- EC-6: Featured image alt text missing → validation error (accessibility requirement)
- EC-7: No category selected → validation error: "At least 1 category required"
- EC-8: More than 10 tags selected → validation error: "Maximum 10 tags allowed"
- EC-9: Scheduled date in the past → validation error: "Publish date must be in the future"
- EC-10: Content empty → validation error: "Content is required"
- EC-11: Meta title exceeds 60 characters → warning with truncation preview
- EC-12: Meta description exceeds 160 characters → warning with truncation preview
- EC-13: Focus keyword not found in content → SEO warning with suggestions
- EC-14: No internal links → SEO suggestion: "Add internal links to improve SEO"
- EC-15: Images without alt text → accessibility warning with list of images

**Validation Rules:**
- Title: required, max 200 characters
- Slug: required, unique, URL-friendly (lowercase, hyphens, no special chars)
- Excerpt: required, max 300 characters
- Content: required, min 100 characters
- Featured Image: JPG/PNG/WebP, max 10MB, recommended 1200x630px
- Featured Image Alt Text: required, max 125 characters
- Categories: at least 1 required
- Tags: max 10
- Meta Title: max 60 characters
- Meta Description: max 160 characters
- Publish Date: valid date/time format

**Security Considerations:**
- Only Super Admin, Admin, and CTO can create blog posts
- Rich text content sanitized to prevent XSS
- HTML tags whitelist: p, strong, em, u, ul, ol, li, a, h2, h3, h4, h5, h6, blockquote, code, pre, table, tr, td, th, img, hr
- External links validated and set to rel="noopener noreferrer"
- Image files scanned for malicious content
- Slug validated to prevent path traversal attacks

**SEO Best Practices:**
- Title: Include focus keyword, compelling, under 60 chars
- Excerpt: Engaging summary, call-to-action, under 300 chars
- Content: Min 300 words, focus keyword density 1-2%, proper heading structure
- Featured Image: High quality, optimized, descriptive alt text
- Meta Description: Compelling, includes focus keyword, under 160 chars
- Internal Links: 2-5 links to related posts or pages
- External Links: 1-3 authoritative sources
- Readability: Flesch score 60+ (easy to read)

**Responsive Design:**
- Mobile (375px): Single column form, full-width inputs, collapsible sections
- Tablet (768px): Two-column layout (form left, preview right)
- Desktop (1024px+): Side-by-side layout with live preview panel

**Performance:**
- Form load: < 500ms
- Auto-save: < 200ms
- Save and publish: < 2 seconds
- Featured image upload and optimization: < 3 seconds
- SEO analysis: < 500ms
- Preview generation: < 300ms

**UX Considerations:**
- WYSIWYG rich text editor with formatting toolbar
- Character counters with color coding
- SEO score with actionable suggestions
- Readability score with improvement tips
- Live preview with responsive toggle
- Auto-save with visual indicator
- Keyboard shortcuts: Ctrl+S (save), Ctrl+P (preview)
- "Copy from English" button for Arabic fields
- Media library integration for quick image selection
- Category and tag creation inline (if doesn't exist)
- Distraction-free writing mode (full-screen editor)
- Word count and estimated reading time

---

## US-BLOG-6.2 🔴 Edit Existing Blog Post
**As a** Super Admin, Admin, or Founder (CTO), **I want to** edit an existing blog post, **so that** I can update content, fix errors, or improve SEO.

**Acceptance Criteria:**
1. Blog post editing accessible from Blog List → select post → Edit button
2. All fields from creation are editable (title, slug, excerpt, content, images, SEO fields, categories, tags, author, status)
3. Edit form pre-populated with existing data
4. Version history displayed: list of previous versions with timestamp, author, changes summary
5. "Restore Version" button to revert to previous version
6. Visual diff shows changes between current and selected version
7. Live preview updates as user edits
8. Auto-save draft every 30 seconds (doesn't affect published version)
9. "Update" button saves changes and updates published post
10. "Save as Draft" button saves changes without publishing
11. "Preview Changes" button shows unpublished changes
12. Slug change warning: "Changing slug will break existing links. Create 301 redirect?"
13. If post is published, "Last Modified" timestamp updated
14. If post is scheduled, can change publish date/time
15. Success message: "Blog post updated" with link to view live post
16. Changes logged in audit log with editor, timestamp, changed fields

**Edge Cases:**
- EC-1: User edits published post → changes immediately visible after update
- EC-2: User edits scheduled post → changes saved, post still publishes at scheduled time
- EC-3: User changes slug of published post → 301 redirect automatically created from old slug
- EC-4: Multiple users edit same post simultaneously → last save wins, warning shown
- EC-5: User navigates away with unsaved changes → confirmation prompt
- EC-6: Auto-save fails (network error) → error indicator, manual save option
- EC-7: User restores old version → current version saved in history before restore
- EC-8: User changes author → original author notified via email
- EC-9: User changes status from Published to Draft → post hidden from public site
- EC-10: User removes all categories → validation error: "At least 1 category required"
- EC-11: User changes featured image → old image remains in media library
- EC-12: User edits post without permission (role changed) → 403 error, changes discarded

**Validation Rules:**
- Same validation rules as creation
- Slug uniqueness checked (excluding current post)
- If slug changed, old slug added to redirect table

**Security Considerations:**
- Only authorized roles can edit blog posts
- User can only change author if they have admin privileges
- All edits logged in audit log
- Version history cannot be deleted (audit trail)
- Restored versions create new version entry

**Responsive Design:**
- Mobile (375px): Single column, collapsible version history
- Tablet (768px): Two-column layout
- Desktop (1024px+): Side-by-side with preview panel

**Performance:**
- Edit form load: < 500ms
- Auto-save: < 200ms
- Update and publish: < 2 seconds
- Version history load: < 500ms
- Visual diff generation: < 300ms

**UX Considerations:**
- Version history with visual diff
- "Restore Version" with confirmation
- Unsaved changes indicator
- Auto-save status indicator
- "Preview Changes" before publishing
- Slug change warning with redirect option
- "Duplicate Post" button for creating similar posts
- "View Live Post" button (if published)
- Keyboard shortcuts: Ctrl+S (save), Ctrl+P (preview)
- Undo/Redo functionality (Ctrl+Z, Ctrl+Y)

---

## US-BLOG-6.3 🔴 Delete Blog Post with Soft Delete
**As a** Super Admin or Admin, **I want to** delete blog posts, **so that** I can remove outdated or inappropriate content.

**Acceptance Criteria:**
1. Delete button accessible from Blog List (bulk action) or Edit Post page
2. Confirmation dialog with warning: "Are you sure you want to delete '[Post Title]'? This action can be undone within 30 days."
3. Soft delete: post marked as deleted, not removed from database
4. Deleted post hidden from public site immediately
5. Deleted post moved to "Trash" section in admin panel
6. Trash shows: Post Title, Author, Deleted Date, Deleted By, Actions (Restore, Permanent Delete)
7. Deleted posts retained for 30 days, then permanently deleted automatically
8. "Restore" button moves post back to draft status
9. "Permanent Delete" button removes post from database (requires confirmation)
10. Deletion logged in audit log with deleter, timestamp, post details
11. If post has comments, warning shown: "This post has [N] comments. Delete anyway?"
12. If post is linked from other posts, warning shown: "This post is linked from [N] other posts. Links will break."
13. Deleted post's slug becomes available for new posts after 30 days
14. SEO: 410 Gone status returned for deleted post URLs

**Edge Cases:**
- EC-1: User deletes published post → immediately hidden from public site, 410 status
- EC-2: User deletes scheduled post → post never publishes
- EC-3: User deletes draft post → moved to trash, can be restored
- EC-4: User restores deleted post → post status reverts to draft, not published
- EC-5: User permanently deletes post → all associated data removed (comments, analytics, images)
- EC-6: User tries to delete post without permission → 403 error
- EC-7: Post auto-deleted after 30 days → email notification sent to original author
- EC-8: User deletes post with featured image → image remains in media library (not deleted)
- EC-9: User deletes post linked in navigation menu → menu link becomes broken, warning shown
- EC-10: Bulk delete multiple posts → confirmation shows count, all moved to trash
- EC-11: User tries to restore post after 30 days → error: "Post permanently deleted"
- EC-12: User deletes post with scheduled publish date → scheduled job cancelled

**Validation Rules:**
- Only Super Admin and Admin can delete posts
- Founder (CTO) can delete own posts only
- Permanent delete requires additional confirmation
- Cannot delete post if user doesn't have permission

**Security Considerations:**
- Deletion logged in audit log (cannot be deleted)
- Soft delete prevents accidental data loss
- Permanent delete requires Super Admin or Admin role
- Deleted post content still accessible in audit log
- 410 Gone status prevents SEO issues

**Responsive Design:**
- Mobile (375px): Confirmation dialog full-screen
- Tablet/Desktop: Modal dialog with clear actions

**Performance:**
- Soft delete: < 200ms
- Permanent delete: < 500ms
- Trash list load: < 500ms
- Restore: < 300ms

**UX Considerations:**
- Clear confirmation dialog with post title
- Warning if post has comments or links
- Trash section with restore option
- 30-day retention period clearly communicated
- Bulk delete with count confirmation
- "Undo" notification after delete (5-second window)
- Email notification before auto-delete (7 days warning)
- "View Deleted Posts" filter in blog list

---

## US-BLOG-6.4 🔴 View and Manage Blog List (Admin)
**As a** Super Admin, Admin, or Founder (CTO), **I want to** view and manage all blog posts in a comprehensive list, **so that** I can efficiently organize and maintain blog content.

**Acceptance Criteria:**
1. Blog list accessible from CMS dashboard → Blog → All Posts
2. **List View Columns:**
   - Checkbox (for bulk actions)
   - Featured Image (thumbnail)
   - Title (clickable to edit)
   - Author (with avatar)
   - Categories (badges)
   - Status (Draft/Published/Scheduled/Trash)
   - Views Count
   - Comments Count
   - Last Modified Date
   - Actions (Edit, View, Duplicate, Delete)
3. **Pagination:**
   - 20 posts per page (configurable: 10, 20, 50, 100)
   - Page numbers with prev/next buttons
   - "Jump to page" input
   - Total count displayed: "Showing 1-20 of 150 posts"
4. **Search:**
   - Search by title, excerpt, content, author
   - Real-time search with debounce (300ms)
   - Search highlights matching terms
   - "Clear search" button
5. **Filters:**
   - Status: All, Published, Draft, Scheduled, Trash
   - Category: All, [Category 1], [Category 2], etc.
   - Author: All, [Author 1], [Author 2], etc.
   - Date Range: All Time, Today, Last 7 Days, Last 30 Days, Custom Range
   - Language: All, Arabic, English
6. **Sorting:**
   - Sort by: Title, Author, Date Created, Last Modified, Views, Comments
   - Sort order: Ascending, Descending
   - Default: Last Modified (descending)
7. **Bulk Actions:**
   - Select All / Deselect All
   - Bulk Edit: Change status, category, author, tags
   - Bulk Delete: Move to trash
   - Bulk Publish: Publish multiple drafts
   - Bulk actions confirmation dialog
8. **View Modes:**
   - List view (default): table with all columns
   - Grid view: cards with featured image, title, excerpt
   - Compact view: minimal columns for quick scanning
9. Quick actions on hover: Edit, View, Duplicate, Delete
10. "Add New Post" button prominently displayed
11. Export options: CSV, JSON (filtered results)
12. Import posts: CSV, JSON, WordPress XML

**Edge Cases:**
- EC-1: No posts found → empty state with "Create your first post" CTA
- EC-2: Search returns no results → "No posts found. Try different keywords."
- EC-3: Filter combination returns no results → "No posts match your filters."
- EC-4: User selects all posts across pages → confirmation: "Select all [N] posts?"
- EC-5: Bulk action on mixed statuses → action applies to all selected
- EC-6: User tries to bulk publish scheduled posts → warning: "Scheduled posts will publish immediately"
- EC-7: User exports large dataset (1000+ posts) → background job, email when ready
- EC-8: User imports posts with duplicate slugs → slugs auto-incremented
- EC-9: Grid view with missing featured images → placeholder image shown
- EC-10: User filters by deleted author → posts show "[Deleted User]"
- EC-11: User sorts by views with same count → secondary sort by date
- EC-12: User changes pagination size → current page adjusted to show same posts

**Validation Rules:**
- Bulk actions require at least 1 post selected
- Export limited to 10,000 posts per request
- Import file max size: 50MB
- Import validation: required fields, valid formats

**Security Considerations:**
- Only authorized roles can access blog list
- User can only see posts they have permission to view
- Bulk actions validated per post (skip posts without permission)
- Export data sanitized (no sensitive info)
- Import files scanned for malicious content

**Responsive Design:**
- Mobile (375px): Compact view, swipe actions, bottom sheet filters
- Tablet (768px): List view with fewer columns, collapsible filters
- Desktop (1024px+): Full list view with all columns, sidebar filters

**Performance:**
- List load: < 500ms for 20 posts
- Search: < 300ms with debounce
- Filter/Sort: < 200ms
- Bulk action: < 1 second for 100 posts
- Export: < 5 seconds for 1000 posts
- Import: < 10 seconds for 1000 posts

**UX Considerations:**
- Sticky header with filters and search
- Infinite scroll option (alternative to pagination)
- Keyboard navigation: arrow keys, Enter to edit
- Drag-and-drop reordering (for featured posts)
- Quick edit inline (title, status, categories)
- "Recently Viewed" section for quick access
- Saved filter presets (e.g., "My Drafts", "Published This Month")
- Column visibility toggle (show/hide columns)
- Bulk action progress indicator
- Undo bulk actions (5-second window)


---

## US-BLOG-6.5 🔴 Blog Post Public View with Maximum SEO Optimization
**As a** website visitor, **I want to** read blog posts with excellent SEO and user experience, **so that** I can find valuable content through search engines and enjoy reading it.

**Acceptance Criteria:**
1. Blog post accessible at URL: `/en/blog/[slug]` and `/ar/blog/[slug]`
2. **SEO Meta Tags (all present in HTML head):**
   - `<title>` - from Meta Title or post title
   - `<meta name="description">` - from Meta Description or excerpt
   - `<meta name="keywords">` - from Meta Keywords
   - `<meta name="author">` - author name
   - `<link rel="canonical">` - canonical URL
   - `<meta name="robots">` - index, follow
3. **Open Graph Tags:**
   - `og:title`, `og:description`, `og:image`, `og:url`, `og:type="article"`
   - `og:site_name="Tarqumi"`
   - `og:locale` (ar_AR or en_US)
   - `article:published_time`, `article:modified_time`, `article:author`
4. **Twitter Card Tags:**
   - `twitter:card` (summary or summary_large_image)
   - `twitter:title`, `twitter:description`, `twitter:image`
5. **JSON-LD Structured Data (Article schema):**
   ```json
   {
     "@context": "https://schema.org",
     "@type": "Article",
     "headline": "Post Title",
     "image": "Featured Image URL",
     "datePublished": "ISO 8601 date",
     "dateModified": "ISO 8601 date",
     "author": {
       "@type": "Person",
       "name": "Author Name"
     },
     "publisher": {
       "@type": "Organization",
       "name": "Tarqumi",
       "logo": {
         "@type": "ImageObject",
         "url": "Logo URL"
       }
     },
     "description": "Post excerpt"
   }
   ```
6. **Hreflang Tags:**
   - `<link rel="alternate" hreflang="ar" href="/ar/blog/[slug]">`
   - `<link rel="alternate" hreflang="en" href="/en/blog/[slug]">`
   - `<link rel="alternate" hreflang="x-default" href="/en/blog/[slug]">`
7. **Semantic HTML Structure:**
   - `<article>` wrapper for post content
   - `<h1>` for post title (only one H1 per page)
   - Proper heading hierarchy (H2 → H3 → H4, no skipping)
   - `<time datetime="ISO 8601">` for publish/update dates
   - `<figure>` and `<figcaption>` for images
   - `<nav>` for breadcrumbs and related posts
8. **Content Display:**
   - Featured image at top with alt text
   - Post title (H1)
   - Author name with avatar (if available)
   - Publish date and last modified date
   - Reading time estimate (e.g., "5 min read")
   - Category badges (clickable, filter by category)
   - Tags (clickable, filter by tag)
   - Full post content with proper formatting
   - All images lazy-loaded with alt text
   - External links open in new tab with rel="noopener noreferrer"
9. **Breadcrumb Navigation:**
   - Home > Blog > [Category] > [Post Title]
   - Breadcrumbs with JSON-LD BreadcrumbList schema
10. **Related Posts Section:**
    - "You May Also Like" section at bottom
    - 3-4 related posts based on category/tags
    - Each shows: thumbnail, title, excerpt, publish date
11. **Social Sharing Buttons (optional):**
    - Share on: Facebook, Twitter, LinkedIn, WhatsApp, Copy Link
    - Share count display (optional)
12. **Comments Section (Phase 2 - placeholder for now):**
    - Placeholder: "Comments coming soon"
13. **Performance:**
    - Server-Side Rendered (SSR) for SEO
    - Images optimized and lazy-loaded
    - Page load time < 2 seconds
    - Core Web Vitals: LCP < 2.5s, FID < 100ms, CLS < 0.1
14. **Accessibility:**
    - All images have alt text
    - Proper heading hierarchy
    - Keyboard navigation support
    - ARIA labels where needed
    - Color contrast ratio ≥ 4.5:1
15. **Responsive Design:**
    - Mobile (375px): Single column, stacked layout
    - Tablet (768px): Optimized reading width
    - Desktop (1024px+): Max content width 800px for readability

**Edge Cases:**
- EC-1: Post with no featured image → default OG image used (site logo)
- EC-2: Post with very long title → truncated in meta tags, full title in H1
- EC-3: Post with no author → displays "Tarqumi Team"
- EC-4: Post with no category → breadcrumb shows "Uncategorized"
- EC-5: Post with no tags → tags section hidden
- EC-6: Post with no related posts → section hidden
- EC-7: Post content with embedded videos → responsive iframe wrapper
- EC-8: Post content with code blocks → syntax highlighting applied
- EC-9: Post content with tables → horizontal scroll on mobile
- EC-10: Post scheduled but accessed before publish date → 404 error
- EC-11: Post set to Private → 404 error for non-logged-in users
- EC-12: Post deleted → 410 Gone status returned

**Validation Rules:**
- All meta tags must be present and non-empty
- JSON-LD must be valid JSON
- Hreflang URLs must be absolute URLs
- Canonical URL must match current page URL
- All images must have alt text

**Security Considerations:**
- Content sanitized to prevent XSS
- External links have rel="noopener noreferrer"
- No inline scripts in post content
- CSP headers configured

**SEO Best Practices:**
- Unique title and description per post
- Focus keyword in title, first paragraph, and H2 headings
- Internal links to related posts and pages
- Image alt text descriptive and includes keywords
- URL structure clean and descriptive
- Mobile-friendly and fast loading
- Proper heading hierarchy
- Schema markup for rich snippets

**Responsive Design:**
- Mobile (375px): Full-width images, readable font size (16px+), touch-friendly buttons
- Tablet (768px): Optimized reading width (600-700px)
- Desktop (1024px+): Max content width 800px, sidebar for related posts (optional)

**Performance:**
- SSR page generation: < 1 second
- Time to First Byte (TTFB): < 600ms
- First Contentful Paint (FCP): < 1.5 seconds
- Largest Contentful Paint (LCP): < 2.5 seconds
- Images lazy-loaded below the fold
- Critical CSS inlined

**UX Considerations:**
- Clear typography with good line height (1.6-1.8)
- Ample white space for readability
- Sticky header for easy navigation
- "Back to Blog" button
- Progress indicator showing reading progress (optional)
- Print-friendly styles
- Dark mode support (optional, Phase 2)

---

## US-BLOG-6.6 🟠 Blog List Page with Pagination and Filtering
**As a** website visitor, **I want to** browse all blog posts with filtering and pagination, **so that** I can find articles that interest me.

**Acceptance Criteria:**
1. Blog list accessible at `/en/blog` and `/ar/blog`
2. **Page Header:**
   - Page title: "Blog" or "المدونة"
   - Page description/tagline
   - Search bar (search by title, excerpt, content)
3. **Post Grid/List:**
   - Default view: Grid layout (2-3 columns)
   - Each post card shows:
     - Featured image (thumbnail)
     - Category badge
     - Post title (clickable)
     - Excerpt (truncated to 150 characters)
     - Author name with avatar
     - Publish date
     - Reading time estimate
     - "Read More" link
4. **Filtering Options:**
   - Filter by Category: All, [Category 1], [Category 2], etc.
   - Filter by Tag: All, [Tag 1], [Tag 2], etc.
   - Filter by Author: All, [Author 1], [Author 2], etc.
   - Filter by Date: All, This Month, Last 3 Months, This Year, Custom Range
5. **Sorting Options:**
   - Sort by: Newest First (default), Oldest First, Most Popular (by views), A-Z, Z-A
6. **Search Functionality:**
   - Real-time search with debounce (300ms)
   - Search by title, excerpt, content, tags
   - Search highlights matching terms in results
   - "Clear search" button
7. **Pagination:**
   - 12 posts per page (configurable)
   - Page numbers with prev/next buttons
   - "Jump to page" input
   - Total count displayed: "Showing 1-12 of 48 posts"
   - SEO-friendly pagination with rel="prev" and rel="next" links
8. **Empty States:**
   - No posts found: "No blog posts found. Try different filters."
   - No search results: "No posts match your search for '[query]'"
9. **SEO Meta Tags:**
   - Unique title: "Blog - Tarqumi" or "المدونة - ترقمي"
   - Meta description: "Read our latest articles about [topics]"
   - Canonical URL
   - OG tags for social sharing
10. **Breadcrumb Navigation:**
    - Home > Blog
11. **Featured Post (optional):**
    - First post can be featured (larger card, full width)
12. **Categories Sidebar (desktop):**
    - List of all categories with post count
    - Clickable to filter
13. **Tags Cloud (optional):**
    - Visual tag cloud with size based on usage
14. **Newsletter Signup (optional):**
    - Email subscription form at bottom
15. **Performance:**
    - SSR for SEO
    - Lazy load images
    - Page load < 2 seconds

**Edge Cases:**
- EC-1: No posts published → empty state: "No blog posts yet. Check back soon!"
- EC-2: Search returns no results → empty state with suggestions
- EC-3: Filter combination returns no results → empty state with active filters shown
- EC-4: User on page 5, applies filter that returns only 2 pages → redirected to page 1
- EC-5: Post with no featured image → placeholder image used
- EC-6: Post with very long title → truncated with ellipsis
- EC-7: Post with no excerpt → auto-generated from first 150 characters of content
- EC-8: Category with no posts → category hidden from filter
- EC-9: Pagination beyond available pages → 404 error
- EC-10: Search with special characters → properly escaped
- EC-11: Filter and search state preserved in URL → shareable links
- EC-12: Mobile view → filters in collapsible drawer

**Validation Rules:**
- Search query: max 255 characters
- Page number: positive integer, within valid range
- Per page: 6, 12, 24, or 48
- Date range: valid date format

**Security Considerations:**
- Search queries sanitized to prevent XSS
- Filter parameters validated
- Pagination parameters validated to prevent injection

**SEO Best Practices:**
- Unique meta title and description for blog list page
- Canonical URL for paginated pages
- Rel="prev" and rel="next" for pagination
- Noindex for filtered/search result pages (optional)
- Sitemap includes all blog posts

**Responsive Design:**
- Mobile (375px): Single column, filters in drawer, stacked cards
- Tablet (768px): 2 columns, filters in sidebar
- Desktop (1024px+): 3 columns, filters in left sidebar

**Performance:**
- Page load: < 2 seconds
- Search/filter response: < 500ms
- Pagination navigation: < 300ms
- Images lazy-loaded

**UX Considerations:**
- Active filters displayed as removable chips
- Filter count badges (e.g., "Technology (12)")
- Hover effects on post cards
- Smooth transitions between pages
- Loading skeleton while fetching
- "Back to top" button on long pages
- Keyboard navigation support

---

## US-BLOG-6.7 🟠 Blog Categories Management (CRUD)
**As a** Super Admin, Admin, or Founder (CTO), **I want to** manage blog categories, **so that** I can organize blog posts into topics.

**Acceptance Criteria:**
1. Categories management accessible from CMS dashboard → Blog → Categories
2. Category list displays: Name, Slug, Description, Post Count, Status, Actions
3. **Create Category:**
   - Required fields: Name (bilingual), Slug (auto-generated, editable)
   - Optional fields: Description (bilingual, max 500 characters), Parent Category, Icon/Image, SEO fields
   - Slug validation: unique, URL-safe
   - Parent category: select from existing categories (for hierarchical structure)
   - Icon/Image upload: max 2MB, JPG/PNG/SVG
   - SEO fields: Meta Title, Meta Description (for category archive page)
   - Status: Active or Inactive
4. **View Categories:**
   - List view with pagination (20 per page)
   - Hierarchical view showing parent-child relationships
   - Search by name or slug
   - Filter by status (All, Active, Inactive)
   - Sort by: Name, Post Count, Created Date
5. **Update Category:**
   - Edit all fields
   - Change parent category (move in hierarchy)
   - Update slug (with 301 redirect from old slug)
   - Change status
6. **Delete Category:**
   - Confirmation dialog with warning
   - If category has posts → reassignment required or posts moved to "Uncategorized"
   - Soft delete (recoverable for 30 days)
   - Deletion logged in audit log
7. **Reorder Categories:**
   - Drag-and-drop reordering for display order
   - Order saved and reflected on public site
8. **Category Archive Page:**
   - Each category has dedicated page: `/en/blog/category/[slug]`
   - Shows all posts in that category
   - Category name, description, and icon displayed
   - SEO optimized with unique meta tags
9. Save triggers revalidation of affected pages
10. Success messages for all CRUD operations
11. Changes logged in audit log

**Edge Cases:**
- EC-1: Category name already exists → validation error
- EC-2: Slug already exists → validation error with suggestion
- EC-3: Delete category with posts → reassignment dialog shown
- EC-4: Delete parent category → child categories become top-level
- EC-5: Circular parent-child relationship → validation error
- EC-6: Category with no posts → can be deleted without reassignment
- EC-7: Change slug of category with 100+ posts → confirmation required
- EC-8: Inactive category → hidden from public site, posts still accessible
- EC-9: Category icon missing → default icon used
- EC-10: Very long category name → truncated in UI with tooltip
- EC-11: Hierarchical depth > 3 levels → warning shown
- EC-12: Default "Uncategorized" category → cannot be deleted or renamed

**Validation Rules:**
- Name: required, max 100 characters per language
- Slug: required, unique, URL-safe
- Description: optional, max 500 characters per language
- Icon/Image: JPG/PNG/SVG, max 2MB
- Parent Category: must be existing category, no circular references

**Security Considerations:**
- Only authorized roles can manage categories
- All input sanitized to prevent XSS
- Slug validated to prevent path traversal
- Category deletion requires confirmation
- All CRUD operations logged

**Responsive Design:**
- Mobile (375px): List view, swipe actions
- Tablet (768px): Grid view with drag-and-drop
- Desktop (1024px+): Hierarchical tree view with inline editing

**Performance:**
- Category list load: < 500ms
- Create/Update/Delete: < 1 second
- Reorder: < 200ms
- Revalidation: < 2 seconds

**UX Considerations:**
- Inline editing for quick updates
- Hierarchical tree view with expand/collapse
- Post count badge per category
- "View Posts" link to see category posts
- Drag-and-drop reordering with visual feedback
- Bulk actions: Delete, Activate, Deactivate
- "Create Subcategory" quick action
- Category icon preview

---

## US-BLOG-6.8 🟠 Blog Tags Management (CRUD)
**As a** Super Admin, Admin, or Founder (CTO), **I want to** manage blog tags, **so that** I can add flexible metadata to blog posts for better organization and discoverability.

**Acceptance Criteria:**
1. Tags management accessible from CMS dashboard → Blog → Tags
2. Tag list displays: Name, Slug, Post Count, Status, Actions
3. **Create Tag:**
   - Required fields: Name (bilingual), Slug (auto-generated, editable)
   - Optional fields: Description (bilingual, max 200 characters), Color (hex code for badge)
   - Slug validation: unique, URL-safe
   - Color picker for tag badge color
   - Status: Active or Inactive
4. **View Tags:**
   - List view with pagination (50 per page)
   - Tag cloud view (size based on usage)
   - Search by name or slug
   - Filter by status (All, Active, Inactive)
   - Sort by: Name, Post Count, Created Date
5. **Update Tag:**
   - Edit all fields
   - Update slug (with 301 redirect from old slug)
   - Change color
   - Change status
6. **Delete Tag:**
   - Confirmation dialog
   - Tag removed from all posts
   - Soft delete (recoverable for 30 days)
   - Deletion logged in audit log
7. **Merge Tags:**
   - Select multiple tags to merge into one
   - All posts updated to use merged tag
   - Old tags deleted
8. **Tag Archive Page:**
   - Each tag has dedicated page: `/en/blog/tag/[slug]`
   - Shows all posts with that tag
   - Tag name and description displayed
   - SEO optimized
9. **Auto-suggest Tags:**
   - When creating/editing post, suggest tags based on content
   - AI-powered tag suggestions (optional, Phase 2)
10. Save triggers revalidation of affected pages
11. Success messages for all CRUD operations
12. Changes logged in audit log

**Edge Cases:**
- EC-1: Tag name already exists → validation error
- EC-2: Slug already exists → validation error with suggestion
- EC-3: Delete tag used in 100+ posts → confirmation with post count
- EC-4: Merge tags with different colors → user selects final color
- EC-5: Tag with no posts → can be deleted without confirmation
- EC-6: Inactive tag → hidden from public site, posts still accessible
- EC-7: Very long tag name → truncated in UI with tooltip
- EC-8: Tag color invalid hex code → validation error
- EC-9: Tag color too light/dark → warning about readability
- EC-10: Bulk delete tags → confirmation with total post count affected
- EC-11: Tag created during post creation → auto-saved and available immediately
- EC-12: Tag with special characters in name → slug sanitized automatically

**Validation Rules:**
- Name: required, max 50 characters per language
- Slug: required, unique, URL-safe
- Description: optional, max 200 characters per language
- Color: valid hex code (e.g., #FF5733)

**Security Considerations:**
- Only authorized roles can manage tags
- All input sanitized to prevent XSS
- Slug validated to prevent path traversal
- Tag deletion requires confirmation
- All CRUD operations logged

**Responsive Design:**
- Mobile (375px): List view, swipe to delete
- Tablet (768px): Grid view with color badges
- Desktop (1024px+): List view with inline editing, tag cloud view

**Performance:**
- Tag list load: < 500ms
- Create/Update/Delete: < 1 second
- Merge tags: < 2 seconds for 100 posts
- Revalidation: < 2 seconds

**UX Considerations:**
- Inline editing for quick updates
- Color picker with preset colors
- Post count badge per tag
- "View Posts" link to see tag posts
- Bulk actions: Delete, Activate, Deactivate, Merge
- Tag cloud visualization
- Auto-suggest tags when typing
- "Create Tag" quick action from post editor

---

## US-BLOG-6.9 🟡 Blog Analytics and Insights
**As a** Super Admin, Admin, or Founder, **I want to** view blog analytics and insights, **so that** I can understand content performance and make data-driven decisions.

**Acceptance Criteria:**
1. Analytics dashboard accessible from CMS dashboard → Blog → Analytics
2. **Overview Metrics:**
   - Total posts published
   - Total views (all-time)
   - Total views (last 30 days)
   - Average views per post
   - Total comments (Phase 2)
   - Total shares (if tracking enabled)
3. **Top Performing Posts:**
   - Top 10 posts by views (last 30 days)
   - Each shows: Title, Views, Publish Date, Author
   - Clickable to view post or edit
4. **Post Performance Chart:**
   - Line chart showing views over time (last 30/90/365 days)
   - Filter by date range
   - Compare multiple posts
5. **Category Performance:**
   - Bar chart showing views per category
   - Post count per category
   - Average views per post in each category
6. **Author Performance:**
   - Table showing each author's stats
   - Posts published, total views, average views per post
   - Most popular post per author
7. **Traffic Sources (if integrated with Google Analytics):**
   - Organic search, social media, direct, referral
   - Top referring domains
8. **Search Keywords (if integrated with Google Search Console):**
   - Top keywords driving traffic to blog
   - Impressions, clicks, CTR, average position
9. **Engagement Metrics:**
   - Average time on page
   - Bounce rate
   - Scroll depth (how far users read)
10. **Export Options:**
    - Export analytics data to CSV/Excel
    - Date range selection
    - Filter by category, author, or post
11. **Real-time Stats (optional):**
    - Current active readers
    - Posts being read right now
12. **Performance Alerts:**
    - Notification when post reaches milestone (1K, 10K, 100K views)
    - Alert when post performance drops significantly
13. **SEO Insights:**
    - Posts with missing meta descriptions
    - Posts with low SEO scores
    - Posts with broken links
14. **Content Recommendations:**
    - Suggest topics based on popular posts
    - Identify content gaps
15. Analytics data refreshed daily (or hourly for real-time)

**Edge Cases:**
- EC-1: No posts published → empty state: "Publish posts to see analytics"
- EC-2: No views yet → zero state with encouragement message
- EC-3: Date range with no data → empty chart with message
- EC-4: Very high view count (millions) → formatted with K/M suffix
- EC-5: Analytics integration not configured → warning with setup instructions
- EC-6: Export with large dataset (10K+ posts) → background job, email download link
- EC-7: Real-time stats unavailable → fallback to daily stats
- EC-8: Chart fails to render → fallback to table view
- EC-9: Author deleted → shows "[Deleted User]" in analytics
- EC-10: Post deleted → removed from analytics or marked as deleted
- EC-11: Category deleted → posts show "Uncategorized" in analytics
- EC-12: Analytics data older than 2 years → archived, available on request

**Validation Rules:**
- Date range: valid start and end dates
- Export limit: max 10,000 records per export
- Chart data points: max 365 days for line charts

**Security Considerations:**
- Analytics visible only to Admin, Super Admin, Founders
- Employee and HR roles cannot access analytics
- Export action logged in audit log
- No PII (Personally Identifiable Information) in analytics

**Responsive Design:**
- Mobile (375px): Stacked metrics, simplified charts, scrollable tables
- Tablet (768px): 2-column layout, responsive charts
- Desktop (1024px+): Full dashboard with multiple charts and tables

**Performance:**
- Dashboard load: < 2 seconds
- Chart render: < 1 second
- Export: < 5 seconds for 1000 records
- Real-time updates: < 500ms

**UX Considerations:**
- Visual charts with tooltips
- Color-coded metrics (green for growth, red for decline)
- Comparison mode (compare two time periods)
- Drill-down capability (click chart to see details)
- Customizable dashboard (drag-and-drop widgets)
- Saved reports (save filter combinations)
- Scheduled email reports (daily/weekly/monthly)
- Dark mode support for dashboard (optional)

---

## US-BLOG-6.10 🟢 Blog Comments System (Phase 2 - Future)
**As a** website visitor, **I want to** comment on blog posts, **so that** I can engage with the content and community.

**Acceptance Criteria:**
1. Comments section displayed at bottom of each blog post
2. **Comment Form:**
   - Name (required)
   - Email (required, not displayed publicly)
   - Website (optional)
   - Comment text (required, max 1000 characters)
   - "Post Comment" button
3. **Comment Display:**
   - Commenter name
   - Comment text
   - Timestamp (relative: "2 hours ago")
   - Reply button (nested comments, max 3 levels)
   - Like button (optional)
   - Report button (flag inappropriate comments)
4. **Comment Moderation:**
   - Admin approval required before comment appears (optional setting)
   - Admin can approve, reject, or delete comments
   - Spam detection (Akismet integration or similar)
   - Profanity filter
5. **Notifications:**
   - Admin notified of new comments (email)
   - Commenter notified of replies (email, opt-in)
6. **Comment Count:**
   - Display comment count on post card and post page
   - "X Comments" link scrolls to comments section
7. **Pagination:**
   - Load more comments (infinite scroll or pagination)
   - Default: 20 comments per page
8. **Sorting:**
   - Sort by: Newest First, Oldest First, Most Liked
9. **User Authentication (optional):**
   - Allow logged-in users to comment without entering name/email
   - Display user avatar for logged-in commenters
10. **Social Login (optional):**
    - Comment with Facebook, Google, Twitter account
11. **Rich Text Comments (optional):**
    - Allow basic formatting (bold, italic, links)
    - Markdown support
12. **Comment Editing:**
    - Commenter can edit own comment within 5 minutes
    - "Edited" label shown if comment was edited
13. **Comment Deletion:**
    - Commenter can delete own comment
    - Admin can delete any comment
14. **Threaded Replies:**
    - Reply to specific comments
    - Nested display (max 3 levels)
    - "View all replies" for collapsed threads
15. **Anti-spam Measures:**
    - CAPTCHA for anonymous commenters (optional)
    - Rate limiting: max 5 comments per hour per IP
    - Link limit: max 2 links per comment

**Edge Cases:**
- EC-1: Comment with no name → validation error
- EC-2: Comment with invalid email → validation error
- EC-3: Comment exceeds 1000 characters → validation error with character count
- EC-4: Comment with malicious HTML → sanitized automatically
- EC-5: Comment with spam keywords → flagged for moderation
- EC-6: Comment on deleted post → 404 error
- EC-7: Reply to deleted comment → reply becomes top-level comment
- EC-8: Very long comment thread (100+ comments) → paginated
- EC-9: Comment with multiple links → flagged for moderation
- EC-10: Duplicate comment submission → prevented with message
- EC-11: Comment from banned IP → rejected silently
- EC-12: Comment notification email fails → logged, retry attempted

**Validation Rules:**
- Name: required, max 100 characters
- Email: required, valid format, max 255 characters
- Website: optional, valid URL format
- Comment: required, min 10 characters, max 1000 characters

**Security Considerations:**
- All comments sanitized to prevent XSS
- Email addresses not displayed publicly
- IP addresses logged for spam prevention
- Rate limiting to prevent spam
- CAPTCHA for anonymous users (optional)
- Profanity filter
- Admin moderation queue

**Responsive Design:**
- Mobile (375px): Stacked comments, simplified form
- Tablet (768px): Nested comments with indentation
- Desktop (1024px+): Full-width comments with avatars

**Performance:**
- Comment submission: < 1 second
- Comment load: < 500ms for 20 comments
- Spam check: < 200ms

**UX Considerations:**
- Real-time comment count updates
- Smooth scroll to comments section
- Inline reply form
- Character counter for comment text
- Success message after posting
- Email notification opt-in checkbox
- "Preview" button before posting
- Markdown cheat sheet (if Markdown enabled)

---

## Summary

The Blog System module includes 10 comprehensive user stories covering all aspects of blog management and public display:

| Story | Priority | Description |
|-------|----------|-------------|
| US-BLOG-6.1 | 🔴 Critical | Create blog post with bilingual content and SEO |
| US-BLOG-6.2 | 🔴 Critical | Edit existing blog post |
| US-BLOG-6.3 | 🔴 Critical | Delete blog post with soft delete |
| US-BLOG-6.4 | 🔴 Critical | View and manage blog list (admin) |
| US-BLOG-6.5 | 🔴 Critical | Blog post public view with maximum SEO |
| US-BLOG-6.6 | 🟠 High | Blog list page with pagination and filtering |
| US-BLOG-6.7 | 🟠 High | Blog categories management (CRUD) |
| US-BLOG-6.8 | 🟠 High | Blog tags management (CRUD) |
| US-BLOG-6.9 | 🟡 Medium | Blog analytics and insights |
| US-BLOG-6.10 | 🟢 Nice-to-have | Blog comments system (Phase 2) |

**Key Features:**
- Bilingual content support (Arabic and English)
- Comprehensive SEO optimization (meta tags, JSON-LD, Open Graph, Twitter Cards)
- Rich text editor with full formatting capabilities
- Category and tag organization
- Draft, scheduled, and published status management
- Image optimization and media library integration
- Analytics and performance tracking
- Responsive design for all devices
- Accessibility compliance
- Security best practices (XSS prevention, input sanitization)

**SEO Priorities:**
- Server-Side Rendering (SSR) for all blog pages
- Unique meta titles and descriptions
- JSON-LD structured data (Article schema)
- Proper heading hierarchy (H1 → H2 → H3)
- Image alt text requirements
- Internal linking suggestions
- Sitemap inclusion
- Hreflang tags for bilingual content
- Canonical URLs
- Mobile-friendly and fast loading

**Phase 1 Scope:**
- Stories 6.1 through 6.8 (blog creation, management, categories, tags)
- Basic analytics (6.9) if time permits

**Phase 2 Scope:**
- Advanced analytics with third-party integrations
- Comments system (6.10)
- AI-powered content suggestions
- Advanced SEO tools (keyword research, competitor analysis)
