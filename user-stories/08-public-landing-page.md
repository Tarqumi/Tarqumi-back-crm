# Public Landing Page User Stories

> **Format**: Each story follows the pattern:
> `As a [role], I want to [action], so that [benefit].`
>
> **Priority Levels**: 🔴 Critical | 🟠 High | 🟡 Medium | 🟢 Nice-to-have
>
> **Acceptance Criteria (AC)**: Numbered conditions that MUST be true for the story to be complete.
>
> **Edge Cases (EC)**: Scenarios that must be handled gracefully.

---

## US-PUBLIC-8.1 🔴 Home Page Visitor Experience
**As a** website visitor, **I want to** land on an engaging and informative home page, **so that** I understand what Tarqumi does and am motivated to explore further or contact them.

**Acceptance Criteria:**
1. **Hero Section:**
   - Large, eye-catching hero section with headline and subheadline
   - Clear value proposition visible immediately
   - Primary CTA button (e.g., "Get Started", "Contact Us")
   - Secondary CTA button (optional, e.g., "View Projects")
   - Background image or video (optimized for performance)
   - Smooth entrance animation (fade in, slide up)
2. **Services Preview Section:**
   - Section title: "Our Services" or "What We Do"
   - 6 service cards displayed in grid layout
   - Each card shows: Icon, Title, Short Description
   - Hover effects on cards (scale, shadow, color change)
   - "View All Services" button linking to Services page
   - Responsive grid: 3 columns desktop, 2 tablet, 1 mobile
3. **Projects Preview Section:**
   - Section title: "Our Work" or "Featured Projects"
   - 3 featured project cards displayed
   - Each card shows: Image, Title, Client, Category, Short Description
   - Hover effects revealing more details
   - "View All Projects" button linking to Projects page
   - Responsive layout: 3 columns desktop, 2 tablet, 1 mobile
4. **Call-to-Action Section:**
   - Compelling CTA message
   - Contact button prominently displayed
   - Background color or image for visual separation
   - Smooth scroll to contact form or link to Contact page
5. **Statistics Section (Optional):**
   - Key metrics: Projects Completed, Happy Clients, Years Experience, Team Members
   - Animated counters (count up on scroll into view)
   - Icons for each statistic
   - Responsive grid layout
6. **Testimonials Section (Optional):**
   - Client testimonials with quotes
   - Client name, company, and photo
   - Carousel or grid layout
   - Star ratings (optional)
7. **Blog Preview Section (Optional):**
   - "Latest from Our Blog" section
   - 3 recent blog posts
   - Each shows: Featured image, title, excerpt, publish date
   - "Read More" links to individual posts
   - "View All Posts" button
8. **Performance:**
   - Page loads in < 3 seconds
   - Largest Contentful Paint (LCP) < 2.5 seconds
   - First Input Delay (FID) < 100ms
   - Cumulative Layout Shift (CLS) < 0.1
   - Images lazy-loaded below the fold
   - Critical CSS inlined
9. **Animations:**
   - Smooth, 60fps animations
   - Fade in on scroll for sections
   - Parallax effects (optional, subtle)
   - Hover animations on interactive elements
   - No janky or laggy animations
10. **SEO:**
    - Unique page title: "Tarqumi - Software Development Company"
    - Meta description highlighting services
    - Open Graph tags for social sharing
    - JSON-LD structured data (Organization, WebSite)
    - Proper heading hierarchy (H1 for main title)
11. **Accessibility:**
    - All images have alt text
    - Proper ARIA labels
    - Keyboard navigation works
    - Focus indicators visible
    - Color contrast ratio ≥ 4.5:1
    - Screen reader friendly
12. **Bilingual Support:**
    - All content in both Arabic and English
    - Language switcher in header
    - RTL layout for Arabic
    - LTR layout for English
    - Smooth transition between languages
13. **Mobile Experience:**
    - Touch-friendly buttons (min 44px)
    - Swipe gestures for carousels
    - Hamburger menu for navigation
    - Optimized images for mobile
    - No horizontal scroll
14. **Footer:**
    - Company logo
    - Contact information
    - Social media links
    - Quick links to main pages
    - Copyright notice with auto-updating year
15. **Analytics:**
    - Page view tracking
    - CTA click tracking
    - Scroll depth tracking
    - Time on page tracking


**Edge Cases:**
- EC-1: Hero image fails to load → fallback to solid color background
- EC-2: Hero video fails to load → fallback to static image
- EC-3: Service cards exceed 6 → only first 6 shown, rest on Services page
- EC-4: No services configured → section hidden, warning in admin
- EC-5: No projects configured → section hidden, warning in admin
- EC-6: Very long service title → truncated with ellipsis
- EC-7: Service card without icon → default icon used
- EC-8: Project without image → placeholder image used
- EC-9: Slow network connection → progressive loading with skeleton screens
- EC-10: JavaScript disabled → page still functional, no animations
- EC-11: Very small screen (< 375px) → content scales appropriately
- EC-12: Very large screen (> 1920px) → content max-width prevents stretching
- EC-13: User scrolls very fast → animations don't lag
- EC-14: User on low-end device → animations disabled or simplified
- EC-15: Content updated while user viewing → no layout shift

**Validation Rules:**
- Hero headline: max 100 characters
- Hero subheadline: max 200 characters
- Service card title: max 50 characters
- Service card description: max 150 characters
- Project card title: max 100 characters
- All images: max 10MB, optimized to < 500KB

**Security Considerations:**
- All content sanitized (no XSS)
- External links have rel="noopener noreferrer"
- No sensitive data exposed
- Analytics tracking respects privacy

**Responsive Design:**
- Mobile (375px): Single column, stacked sections, hamburger menu
- Tablet (768px): 2-column grids, collapsible sections
- Desktop (1024px): 3-column grids, full navigation
- Large Desktop (1440px+): Max content width 1400px, centered

**Performance:**
- Page load: < 3 seconds
- LCP: < 2.5 seconds
- FID: < 100ms
- CLS: < 0.1
- Total page size: < 2MB
- Images optimized: WebP format, lazy loading

**UX Considerations:**
- Clear visual hierarchy
- Smooth scrolling
- Intuitive navigation
- Engaging animations
- Fast interactions
- No dead ends
- Clear CTAs
- Consistent branding

---

## US-PUBLIC-8.2 🔴 Language Switching with RTL/LTR Support
**As a** website visitor, **I want to** switch between Arabic and English languages, **so that** I can read the website in my preferred language with proper text direction.

**Acceptance Criteria:**
1. **Language Switcher:**
   - Visible in header on all pages
   - Shows current language (AR or EN)
   - Click to toggle between languages
   - Flag icons or text labels
   - Dropdown or toggle button
   - Accessible on mobile and desktop
2. **URL Structure:**
   - Arabic pages: `/ar/page-name`
   - English pages: `/en/page-name`
   - Language prefix in all URLs
   - Clean, SEO-friendly URLs
3. **Text Direction:**
   - Arabic: Right-to-Left (RTL)
   - English: Left-to-Right (LTR)
   - `<html dir="rtl">` for Arabic
   - `<html dir="ltr">` for English
   - All UI elements mirror correctly in RTL
4. **Layout Mirroring:**
   - Navigation menu: right-aligned for Arabic, left-aligned for English
   - Text alignment: right for Arabic, left for English
   - Icons and images: mirrored where appropriate
   - Margins and padding: mirrored
   - Scroll direction: natural for each language
5. **Content Translation:**
   - All text translated (no hardcoded strings)
   - Navigation labels translated
   - Button text translated
   - Form labels and placeholders translated
   - Error messages translated
   - Success messages translated
6. **Language Persistence:**
   - User's language choice saved in cookie (30 days)
   - Language persists across pages
   - Language persists across sessions
   - Language preference respected on return visits
7. **Language Detection:**
   - Browser language detected on first visit
   - User redirected to appropriate language
   - Manual override always available
8. **Hreflang Tags:**
   - Each page links to alternate language version
   - `<link rel="alternate" hreflang="ar" href="/ar/page">`
   - `<link rel="alternate" hreflang="en" href="/en/page">`
   - `<link rel="alternate" hreflang="x-default" href="/en/page">`
9. **Smooth Transition:**
   - No page reload when switching languages (SPA behavior)
   - Smooth fade transition
   - Content updates instantly
   - No layout shift during transition
10. **SEO Considerations:**
    - Separate URLs for each language
    - Proper hreflang tags
    - Language-specific sitemaps
    - No duplicate content issues
11. **Forms and Inputs:**
    - Form labels in selected language
    - Validation messages in selected language
    - Placeholder text in selected language
    - Date formats localized
    - Number formats localized
12. **Images and Media:**
    - Alt text in selected language
    - Image captions in selected language
    - Video subtitles in selected language (if applicable)
13. **Third-Party Content:**
    - Social media widgets in selected language
    - Maps in selected language
    - Analytics in selected language
14. **Accessibility:**
    - Language switcher keyboard accessible
    - Screen reader announces language change
    - Language attribute on HTML element
15. **Mobile Experience:**
    - Language switcher easily accessible on mobile
    - Touch-friendly toggle
    - No horizontal scroll in either language

**Edge Cases:**
- EC-1: User switches language mid-form → form data preserved
- EC-2: User switches language on blog post → redirects to translated version or shows "Not available in [language]"
- EC-3: Content missing in one language → fallback to other language with notice
- EC-4: User's browser language not supported → defaults to English
- EC-5: Language cookie deleted → browser language detected again
- EC-6: User manually changes URL language prefix → language switcher updates
- EC-7: Search engines crawl both languages → proper hreflang prevents duplicate content
- EC-8: RTL layout breaks on specific component → component fixed or excluded from mirroring
- EC-9: Mixed content (Arabic and English in same sentence) → handled gracefully
- EC-10: Numbers in Arabic → displayed in Arabic numerals (١٢٣) or Western (123) based on preference
- EC-11: Dates in Arabic → formatted in Arabic style
- EC-12: Currency in Arabic → displayed with Arabic formatting
- EC-13: User switches language during animation → animation completes smoothly
- EC-14: Language switcher on 404 page → works correctly
- EC-15: Language preference conflicts with URL → URL takes precedence

**Validation Rules:**
- Language code: ar or en only
- URL structure: /{lang}/page-name
- Cookie expiration: 30 days
- All text must have translations

**Security Considerations:**
- Language parameter validated (prevent injection)
- No sensitive data in language cookie
- XSS prevention in translated content

**Responsive Design:**
- Mobile: Language switcher in header or menu
- Tablet: Language switcher in header
- Desktop: Language switcher in header (top-right)

**Performance:**
- Language switch: < 500ms
- No page reload required
- Translations loaded on demand
- Minimal bundle size increase

**UX Considerations:**
- Clear language indicator
- Easy to find and use
- Instant feedback
- No confusion about current language
- Consistent experience across languages
- Natural reading direction

---

## US-PUBLIC-8.3 🔴 Navigation Experience (Desktop and Mobile)
**As a** website visitor, **I want to** easily navigate the website, **so that** I can find the information I need quickly.

**Acceptance Criteria:**
1. **Desktop Navigation:**
   - Horizontal navigation bar in header
   - Links: Home, About, Services, Projects, Blog, Contact
   - Hover effects on links (underline, color change)
   - Active page highlighted
   - Logo on left (or right for Arabic)
   - Language switcher on right (or left for Arabic)
   - Sticky header (stays visible on scroll)
   - Smooth scroll to top when logo clicked
2. **Mobile Navigation:**
   - Hamburger menu icon (☰) in header
   - Click to open slide-in menu
   - Full-screen or slide-in menu overlay
   - Close button (X) visible
   - Same links as desktop
   - Touch-friendly link sizes (min 44px height)
   - Swipe to close menu (optional)
3. **Mega Menu (Optional):**
   - Dropdown for Services showing all services
   - Dropdown for Projects showing categories
   - Hover to open on desktop
   - Click to open on mobile
4. **Breadcrumbs:**
   - Visible on all pages except home
   - Format: Home > Page > Subpage
   - Clickable links
   - Current page not clickable
   - Proper schema markup
5. **Footer Navigation:**
   - Quick links to main pages
   - Social media links
   - Contact information
   - Newsletter signup (optional)
   - Back to top button
6. **Search Functionality (Optional):**
   - Search icon in header
   - Click to open search overlay
   - Search bar with autocomplete
   - Search results page
   - Search across pages, blog, projects
7. **Keyboard Navigation:**
   - Tab through all links
   - Enter to activate link
   - Escape to close mobile menu
   - Arrow keys for dropdown menus
8. **Accessibility:**
   - Skip to main content link
   - ARIA labels on navigation
   - Focus indicators visible
   - Screen reader friendly
   - Semantic HTML (nav, ul, li)
9. **Active State:**
   - Current page highlighted in navigation
   - Visual indicator (underline, background color)
   - Consistent across all pages
10. **Smooth Scrolling:**
    - Smooth scroll to anchor links
    - Smooth scroll to top
    - Easing function for natural feel
11. **Mobile Menu Animation:**
    - Slide in from right (or left for Arabic)
    - Fade in overlay
    - Smooth 300ms transition
    - No janky animations
12. **Sticky Header:**
    - Header sticks to top on scroll
    - Shrinks slightly on scroll (optional)
    - Shadow appears on scroll
    - Smooth transition
13. **Logo:**
    - Clickable, links to home page
    - Responsive size (smaller on mobile)
    - High-quality image (SVG preferred)
    - Alt text for accessibility
14. **CTA Button in Header (Optional):**
    - "Get Started" or "Contact Us" button
    - Prominent, different color
    - Always visible
    - Links to contact page or form
15. **Performance:**
    - Navigation renders immediately
    - No layout shift
    - Smooth animations (60fps)
    - Fast interaction response (< 100ms)

**Edge Cases:**
- EC-1: Very long page title in breadcrumbs → truncated with ellipsis
- EC-2: Mobile menu open, user rotates device → menu adapts to new orientation
- EC-3: User clicks outside mobile menu → menu closes
- EC-4: User scrolls with mobile menu open → menu stays open
- EC-5: Sticky header on very small screen → header height optimized
- EC-6: User has many tabs open → navigation still works
- EC-7: User on slow connection → navigation loads first (critical CSS)
- EC-8: JavaScript disabled → navigation still works (CSS-only fallback)
- EC-9: User zooms in → navigation remains usable
- EC-10: User uses keyboard only → all navigation accessible
- EC-11: Screen reader user → navigation announced correctly
- EC-12: User on touch device → hover effects replaced with tap
- EC-13: User clicks link rapidly → no duplicate navigation
- EC-14: User on page with long URL → breadcrumbs don't overflow
- EC-15: User on 404 page → navigation still works

**Validation Rules:**
- All navigation links must be valid
- All navigation links must return 200 status
- Navigation must be accessible
- Navigation must work without JavaScript (fallback)

**Security Considerations:**
- Navigation links validated
- No XSS in navigation
- External links have rel="noopener noreferrer"

**Responsive Design:**
- Mobile (< 768px): Hamburger menu
- Tablet (768px-1023px): Horizontal menu, may wrap
- Desktop (1024px+): Full horizontal menu

**Performance:**
- Navigation render: < 100ms
- Menu open/close: < 300ms
- Smooth 60fps animations
- No layout shift

**UX Considerations:**
- Clear visual hierarchy
- Easy to find and use
- Consistent across pages
- Intuitive interactions
- Fast and responsive
- No confusion


---

## US-PUBLIC-8.4 🟠 Services Page Experience
**As a** website visitor, **I want to** view all services Tarqumi offers, **so that** I can understand their capabilities and decide if they match my needs.

**Acceptance Criteria:**
1. **Page Layout:**
   - Page title: "Our Services" or "What We Do"
   - Page description/tagline
   - Grid of all service cards
   - Responsive grid: 3 columns desktop, 2 tablet, 1 mobile
2. **Service Cards:**
   - Icon (SVG or image)
   - Service title
   - Service description (150-200 characters)
   - Hover effects (scale, shadow, color change)
   - No detail pages (cards only, per requirements)
3. **Card Interactions:**
   - Hover: card lifts, shadow increases
   - Click: no action (cards are informational only)
   - Touch: tap shows brief highlight
4. **Filtering (Optional):**
   - Filter by category
   - Filter by technology
   - "Show All" button
5. **SEO:**
   - Unique page title: "Services - Tarqumi"
   - Meta description listing services
   - Structured data (Service schema for each)
   - Proper heading hierarchy
6. **Performance:**
   - Page loads in < 2 seconds
   - Images lazy-loaded
   - Smooth animations
7. **Accessibility:**
   - All cards keyboard accessible
   - Screen reader friendly
   - Proper ARIA labels
8. **Bilingual:**
   - All content in both languages
   - RTL/LTR support
9. **Empty State:**
   - If no services → "Services coming soon"
10. **CTA:**
    - "Interested in our services? Contact us" section at bottom
    - Contact button

**Edge Cases:**
- EC-1: No services configured → empty state shown
- EC-2: Only 1-2 services → grid adjusts, no empty spaces
- EC-3: Service without icon → default icon used
- EC-4: Very long service title → truncated with ellipsis
- EC-5: Very long description → truncated with "Read more" (but no detail page)
- EC-6: Service card image fails to load → fallback icon
- EC-7: User on slow connection → skeleton cards shown while loading
- EC-8: User zooms in → cards remain readable
- EC-9: User on touch device → hover effects replaced with tap
- EC-10: JavaScript disabled → cards still display, no animations

**Validation Rules:**
- Service title: max 50 characters
- Service description: max 200 characters
- Icon: SVG or image, max 100KB

**Security Considerations:**
- Content sanitized
- No XSS in service descriptions

**Responsive Design:**
- Mobile (375px): 1 column, stacked cards
- Tablet (768px): 2 columns
- Desktop (1024px+): 3 columns

**Performance:**
- Page load: < 2 seconds
- Card animations: 60fps
- Images optimized

**UX Considerations:**
- Clear service descriptions
- Engaging visuals
- Easy to scan
- Consistent card sizes

---

## US-PUBLIC-8.5 🟠 Projects Page Experience
**As a** website visitor, **I want to** browse Tarqumi's project portfolio, **so that** I can see examples of their work and assess their quality.

**Acceptance Criteria:**
1. **Page Layout:**
   - Page title: "Our Work" or "Projects"
   - Page description
   - Grid or masonry layout of project cards
   - Responsive: 3 columns desktop, 2 tablet, 1 mobile
2. **Project Cards:**
   - Project image (screenshot or mockup)
   - Project title
   - Client name
   - Category/tags
   - Short description
   - "View Project" button (if detail page exists)
   - "Visit Website" button (if live URL exists)
3. **Filtering:**
   - Filter by category (Web, Mobile, Design, etc.)
   - Filter by technology (React, Laravel, etc.)
   - Filter by industry
   - "Show All" button
4. **Sorting:**
   - Sort by: Newest, Oldest, A-Z, Z-A
   - Default: Newest first
5. **Project Detail Page (Optional):**
   - Full project description
   - Challenge, Solution, Results sections
   - Technologies used
   - Project timeline
   - Image gallery
   - Client testimonial
   - Link to live project
6. **SEO:**
   - Unique page title: "Projects - Tarqumi"
   - Meta description
   - Structured data (CreativeWork schema)
   - OG tags for social sharing
7. **Performance:**
   - Page loads in < 2 seconds
   - Images lazy-loaded
   - Optimized images (WebP)
8. **Accessibility:**
   - All images have alt text
   - Keyboard navigation
   - Screen reader friendly
9. **Bilingual:**
   - All content in both languages
   - RTL/LTR support
10. **Empty State:**
    - If no projects → "Projects coming soon"

**Edge Cases:**
- EC-1: No projects configured → empty state
- EC-2: Project without image → placeholder image
- EC-3: Project without live URL → "Visit Website" button hidden
- EC-4: Very long project title → truncated
- EC-5: Filter returns no results → "No projects match your filters"
- EC-6: User on slow connection → skeleton cards
- EC-7: Image fails to load → fallback image
- EC-8: Project detail page doesn't exist → card not clickable
- EC-9: External project URL broken → warning icon
- EC-10: User clicks "Visit Website" → opens in new tab

**Validation Rules:**
- Project title: max 100 characters
- Project description: max 300 characters
- Project image: max 5MB, optimized

**Security Considerations:**
- External URLs validated
- Links have rel="noopener noreferrer"
- Content sanitized

**Responsive Design:**
- Mobile (375px): 1 column, stacked
- Tablet (768px): 2 columns
- Desktop (1024px+): 3 columns or masonry

**Performance:**
- Page load: < 2 seconds
- Images lazy-loaded
- Smooth filtering

**UX Considerations:**
- High-quality project images
- Clear project descriptions
- Easy filtering
- Engaging hover effects

---

## US-PUBLIC-8.6 🟠 Blog Listing Page Experience
**As a** website visitor, **I want to** browse blog articles, **so that** I can read content that interests me and learn from Tarqumi's expertise.

**Acceptance Criteria:**
1. **Page Layout:**
   - Page title: "Blog" or "Latest Articles"
   - Page description
   - Grid of blog post cards
   - Responsive: 3 columns desktop, 2 tablet, 1 mobile
2. **Blog Post Cards:**
   - Featured image
   - Category badge
   - Post title
   - Excerpt (150 characters)
   - Author name and avatar
   - Publish date
   - Reading time estimate
   - "Read More" link
3. **Pagination:**
   - 12 posts per page
   - Page numbers (1, 2, 3, ...)
   - Previous/Next buttons
   - "Jump to page" input
   - Total count: "Showing 1-12 of 48 posts"
4. **Filtering:**
   - Filter by category
   - Filter by tag
   - Filter by author
   - Filter by date range
5. **Sorting:**
   - Sort by: Newest, Oldest, Most Popular, A-Z
   - Default: Newest first
6. **Search:**
   - Search bar at top
   - Real-time search with debounce
   - Search by title, excerpt, content
   - Search results highlighted
7. **Featured Post:**
   - First post can be featured (larger card)
   - Full-width or prominent placement
8. **Categories Sidebar:**
   - List of all categories with post count
   - Clickable to filter
9. **SEO:**
   - Unique page title: "Blog - Tarqumi"
   - Meta description
   - Canonical URL
   - Pagination rel="prev" and rel="next"
10. **Performance:**
    - Page loads in < 2 seconds
    - Images lazy-loaded
    - Smooth pagination

**Edge Cases:**
- EC-1: No blog posts → "No posts yet. Check back soon!"
- EC-2: Search returns no results → "No posts match your search"
- EC-3: Filter returns no results → "No posts in this category"
- EC-4: Post without featured image → placeholder image
- EC-5: Very long post title → truncated
- EC-6: Post without excerpt → auto-generated from content
- EC-7: User on last page clicks Next → button disabled
- EC-8: User on first page clicks Previous → button disabled
- EC-9: Pagination beyond available pages → 404
- EC-10: User applies multiple filters → all filters applied

**Validation Rules:**
- Search query: max 255 characters
- Page number: positive integer
- Per page: 6, 12, or 24

**Security Considerations:**
- Search queries sanitized
- No XSS in search results

**Responsive Design:**
- Mobile (375px): 1 column, filters in drawer
- Tablet (768px): 2 columns
- Desktop (1024px+): 3 columns, sidebar

**Performance:**
- Page load: < 2 seconds
- Search: < 500ms
- Pagination: < 300ms

**UX Considerations:**
- Clear post previews
- Easy filtering
- Fast search
- Smooth pagination

---

## US-PUBLIC-8.7 🟠 Blog Post Page Experience
**As a** website visitor, **I want to** read a full blog post, **so that** I can learn from the content and engage with it.

**Acceptance Criteria:**
1. **Post Header:**
   - Featured image (full-width or large)
   - Post title (H1)
   - Author name and avatar
   - Publish date and last updated date
   - Reading time estimate
   - Category badges
   - Tags
2. **Post Content:**
   - Full article content with proper formatting
   - Headings (H2, H3, H4)
   - Paragraphs with good line height
   - Lists (ordered and unordered)
   - Blockquotes
   - Code blocks with syntax highlighting
   - Images with captions
   - Videos (embedded)
   - Tables (responsive)
3. **Table of Contents (Optional):**
   - Auto-generated from headings
   - Sticky sidebar or top of post
   - Clickable links to sections
   - Highlights current section on scroll
4. **Social Sharing:**
   - Share buttons: Facebook, Twitter, LinkedIn, WhatsApp, Copy Link
   - Share count (optional)
   - Floating share bar (optional)
5. **Author Bio:**
   - Author photo
   - Author name
   - Author bio (short)
   - Link to author's other posts
   - Social media links
6. **Related Posts:**
   - "You May Also Like" section
   - 3-4 related posts based on category/tags
   - Each shows: image, title, excerpt
7. **Comments Section (Phase 2):**
   - Placeholder: "Comments coming soon"
   - Or integrated commenting system
8. **Breadcrumbs:**
   - Home > Blog > [Category] > [Post Title]
   - Clickable links
9. **SEO:**
   - Unique page title
   - Meta description
   - Open Graph tags
   - Twitter Card tags
   - JSON-LD Article schema
   - Canonical URL
   - Hreflang tags
10. **Performance:**
    - Page loads in < 2 seconds
    - Images lazy-loaded
    - Code blocks syntax highlighted

**Edge Cases:**
- EC-1: Post without featured image → default image
- EC-2: Post without author → "Tarqumi Team"
- EC-3: Post without category → "Uncategorized"
- EC-4: Post without tags → tags section hidden
- EC-5: No related posts → section hidden
- EC-6: Very long post → table of contents helpful
- EC-7: Post with many images → lazy loading essential
- EC-8: Post with embedded videos → responsive iframe
- EC-9: Post with code blocks → syntax highlighting applied
- EC-10: User shares post → OG tags ensure proper preview

**Validation Rules:**
- Post title: max 200 characters
- Post content: min 100 characters
- Featured image: max 10MB

**Security Considerations:**
- Content sanitized
- External links have rel="noopener noreferrer"
- No XSS in post content

**Responsive Design:**
- Mobile (375px): Single column, full-width images
- Tablet (768px): Optimized reading width
- Desktop (1024px+): Max content width 800px, sidebar for TOC

**Performance:**
- Page load: < 2 seconds
- LCP: < 2.5 seconds
- Images lazy-loaded

**UX Considerations:**
- Readable typography
- Good line height (1.6-1.8)
- Ample white space
- Clear headings
- Easy sharing

---

## US-PUBLIC-8.8 🟠 About Page Experience
**As a** website visitor, **I want to** learn about Tarqumi, **so that** I can understand their mission, values, and team.

**Acceptance Criteria:**
1. **Hero Section:**
   - Page title: "About Us"
   - Tagline or mission statement
   - Hero image or video
2. **Mission/Vision Section:**
   - Mission statement
   - Vision statement
   - Core values (with icons)
3. **Company Story:**
   - Brief history
   - Milestones
   - Achievements
4. **Team Section:**
   - Team member photos
   - Names and roles
   - Brief bios (optional)
   - Social media links (optional)
5. **Statistics:**
   - Years in business
   - Projects completed
   - Happy clients
   - Team members
   - Animated counters
6. **CTA:**
   - "Want to work with us?" section
   - Contact button
7. **SEO:**
   - Unique page title: "About - Tarqumi"
   - Meta description
   - Structured data (Organization schema)
8. **Performance:**
   - Page loads in < 2 seconds
9. **Accessibility:**
   - All images have alt text
   - Proper heading hierarchy
10. **Bilingual:**
    - All content in both languages

**Edge Cases:**
- EC-1: No team members configured → section hidden
- EC-2: Team member without photo → default avatar
- EC-3: Very long mission statement → formatted for readability
- EC-4: Statistics not configured → section hidden
- EC-5: Hero image fails to load → fallback color

**Validation Rules:**
- Mission statement: max 500 characters
- Team member bio: max 200 characters

**Security Considerations:**
- Content sanitized
- No sensitive information exposed

**Responsive Design:**
- Mobile (375px): Single column
- Tablet (768px): Two columns for team
- Desktop (1024px+): Multi-column layout

**Performance:**
- Page load: < 2 seconds
- Images optimized

**UX Considerations:**
- Engaging storytelling
- Clear mission
- Humanize the brand
- Build trust

---

## US-PUBLIC-8.9 🟠 Contact Page Experience
**As a** website visitor, **I want to** easily contact Tarqumi, **so that** I can inquire about their services or get support.

**Acceptance Criteria:**
1. **Page Layout:**
   - Page title: "Contact Us"
   - Contact form (see US-CONTACT-7.1 for details)
   - Contact information sidebar
   - Map (optional)
2. **Contact Information:**
   - Email address (clickable mailto link)
   - Phone number (clickable tel link)
   - Office address
   - Business hours
   - Social media links
3. **Contact Form:**
   - Fields: Name, Email, Phone, Subject, Message
   - Real-time validation
   - Submit button
   - Success/error messages
4. **Map (Optional):**
   - Google Maps embed
   - Office location marked
   - Directions link
5. **Alternative Contact Methods:**
   - WhatsApp button
   - Live chat widget (optional)
   - Social media links
6. **SEO:**
   - Unique page title: "Contact - Tarqumi"
   - Meta description
   - Structured data (ContactPage schema)
7. **Performance:**
   - Page loads in < 2 seconds
   - Form submission < 2 seconds
8. **Accessibility:**
   - Form fully accessible
   - All fields labeled
   - Error messages announced
9. **Bilingual:**
   - All content in both languages
10. **Success State:**
    - Clear success message after submission
    - Option to send another message

**Edge Cases:**
- EC-1: Form submission fails → clear error message
- EC-2: Network timeout → retry option
- EC-3: Rate limit reached → clear message
- EC-4: Map fails to load → contact info still visible
- EC-5: User submits with invalid email → validation error
- EC-6: User submits empty form → validation errors
- EC-7: User on mobile → tel and mailto links work
- EC-8: User clicks WhatsApp → opens WhatsApp app
- EC-9: User submits duplicate message → prevented
- EC-10: Form fields pre-filled from URL params → works correctly

**Validation Rules:**
- See US-CONTACT-7.1 for full validation rules

**Security Considerations:**
- See US-CONTACT-7.1 for security details

**Responsive Design:**
- Mobile (375px): Stacked layout, form first
- Tablet (768px): Side-by-side layout
- Desktop (1024px+): Form left, info right

**Performance:**
- Page load: < 2 seconds
- Form submission: < 2 seconds

**UX Considerations:**
- Easy to find contact info
- Simple form
- Clear CTAs
- Multiple contact options

---

## US-PUBLIC-8.10 🟠 Footer Experience
**As a** website visitor, **I want to** access important links and information in the footer, **so that** I can navigate the site and connect with Tarqumi.

**Acceptance Criteria:**
1. **Footer Layout:**
   - Multi-column layout on desktop
   - Stacked layout on mobile
   - Consistent across all pages
2. **Company Info:**
   - Company logo
   - Tagline or brief description
   - Copyright notice: "© 2024 Tarqumi. All rights reserved."
   - Auto-updating year
3. **Quick Links:**
   - Links to main pages: Home, About, Services, Projects, Blog, Contact
   - Links to legal pages: Privacy Policy, Terms of Service (if applicable)
4. **Contact Info:**
   - Email address (clickable)
   - Phone number (clickable)
   - Office address
5. **Social Media:**
   - Icons for configured social platforms
   - Links open in new tab
   - Hover effects
6. **Newsletter Signup (Optional):**
   - Email input field
   - Subscribe button
   - Success/error messages
7. **Back to Top Button:**
   - Visible when scrolled down
   - Smooth scroll to top
   - Fixed position (bottom-right)
8. **Accessibility:**
   - All links keyboard accessible
   - Proper ARIA labels
   - Screen reader friendly
9. **Bilingual:**
   - All content in both languages
   - RTL/LTR support
10. **Performance:**
    - Footer loads with page
    - No layout shift

**Edge Cases:**
- EC-1: No social media configured → section hidden
- EC-2: Newsletter signup fails → error message
- EC-3: User clicks email → opens email client
- EC-4: User clicks phone → opens phone app (mobile)
- EC-5: Social link broken → icon still shows, link disabled
- EC-6: Very long footer on mobile → scrollable
- EC-7: Back to top button on short page → hidden
- EC-8: User at top of page → back to top button hidden
- EC-9: Copyright year wrong → auto-updates on new year
- EC-10: Footer links to 404 page → link removed or fixed

**Validation Rules:**
- All footer links must be valid
- Email must be valid format
- Phone must be valid format

**Security Considerations:**
- External links have rel="noopener noreferrer"
- Newsletter signup validated
- No XSS in footer content

**Responsive Design:**
- Mobile (375px): Single column, stacked
- Tablet (768px): 2-3 columns
- Desktop (1024px+): 4 columns

**Performance:**
- Footer renders with page
- No additional requests

**UX Considerations:**
- Easy to find important links
- Clear contact information
- Consistent branding
- Professional appearance

Done