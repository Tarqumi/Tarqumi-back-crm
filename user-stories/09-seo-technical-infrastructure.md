# SEO & Technical Infrastructure User Stories

> **Format**: Each story follows the pattern:
> `As a [role], I want to [action], so that [benefit].`
>
> **Priority Levels**: 🔴 Critical | 🟠 High | 🟡 Medium | 🟢 Nice-to-have
>
> **Acceptance Criteria (AC)**: Numbered conditions that MUST be true for the story to be complete.
>
> **Edge Cases (EC)**: Scenarios that must be handled gracefully.

---

## US-SEO-9.1 🔴 Server-Side Rendering (SSR) for All Public Pages
**As a** search engine crawler, **I want to** receive fully rendered HTML pages, **so that** I can properly index all content and improve search rankings.

**Acceptance Criteria:**
1. **SSR Implementation:**
   - All public landing pages rendered on server: Home, About, Services, Projects, Blog (list and detail), Contact
   - Full HTML content present in initial page load (not empty `<div id="root">`)
   - No client-side only rendering for public pages
   - JavaScript hydration after initial HTML load
2. **Content Verification:**
   - `curl` any public page → returns complete HTML with all text content
   - View page source → all content visible (not "Loading..." or empty divs)
   - Meta tags present in HTML head
   - Structured data (JSON-LD) present in HTML
3. **Dynamic Content:**
   - Blog posts rendered with full content
   - Project showcases rendered with all details
   - Service cards rendered with descriptions
   - Team member information rendered
4. **SEO Elements:**
   - Title tag present and unique per page
   - Meta description present and unique per page
   - Canonical URL present
   - Open Graph tags present
   - Twitter Card tags present
   - Hreflang tags present for bilingual pages
5. **Performance:**
   - Time to First Byte (TTFB): < 600ms
   - First Contentful Paint (FCP): < 1.5 seconds
   - Largest Contentful Paint (LCP): < 2.5 seconds
   - Server response time: < 500ms
6. **Caching Strategy:**
   - Static pages cached for 1 hour
   - Dynamic pages (blog) cached for 5 minutes
   - On-demand revalidation when content updated
   - Cache headers properly set (Cache-Control, ETag)
7. **Error Handling:**
   - 404 pages rendered on server
   - 500 errors show proper error page
   - Fallback to client-side rendering if SSR fails
8. **Crawlability:**
   - All internal links crawlable
   - No JavaScript-only navigation
   - Proper HTML semantic structure
   - No infinite scroll without pagination fallback
9. **Testing:**
   - Lighthouse SSR score > 90
   - Google Search Console shows no rendering issues
   - Bing Webmaster Tools shows proper indexing
   - PageSpeed Insights shows green scores
10. **Monitoring:**
    - SSR performance metrics tracked
    - Server errors logged and alerted
    - Cache hit rate monitored
    - TTFB monitored per page

**Edge Cases:**
- EC-1: SSR fails → fallback to client-side rendering, error logged
- EC-2: Database connection timeout → cached version served if available
- EC-3: External API timeout → page renders without that data, placeholder shown
- EC-4: Very large page (blog post with 100+ images) → lazy load images below fold
- EC-5: User with JavaScript disabled → page still fully functional
- EC-6: Bot with unusual user agent → still receives SSR content
- EC-7: Page requested with query parameters → SSR still works, parameters passed to client
- EC-8: Concurrent requests spike → server scales, caching prevents overload
- EC-9: Content updated during page render → stale content shown, revalidated on next request
- EC-10: SSR memory leak → server restarts automatically, monitoring alerts

**Validation Rules:**
- All public pages must return status 200 with full HTML
- HTML must be valid (W3C validator)
- Meta tags must be present and non-empty
- Page size should be < 500KB (initial HTML)

**Security Considerations:**
- No sensitive data in SSR HTML
- API keys not exposed in rendered HTML
- User session data not leaked in SSR
- CSRF tokens properly handled in SSR

**Responsive Design:**
- SSR works for all device types
- Mobile-specific rendering if needed
- Responsive images served based on device

**Performance:**
- SSR page generation: < 500ms
- Cache hit rate: > 80%
- Server CPU usage: < 70% under normal load
- Memory usage: < 80% under normal load

**UX Considerations:**
- No flash of unstyled content (FOUC)
- Smooth transition from SSR to hydrated app
- Loading states for dynamic content
- Progressive enhancement approach


---

## US-SEO-9.2 🔴 Dynamic Sitemap.xml Generation
**As a** search engine crawler, **I want to** access a comprehensive sitemap.xml file, **so that** I can discover and index all pages efficiently.

**Acceptance Criteria:**
1. **Sitemap Accessibility:**
   - Sitemap accessible at `/sitemap.xml`
   - Returns proper XML content type: `application/xml`
   - Valid XML format (no syntax errors)
   - Gzip compressed for faster download
2. **Sitemap Content:**
   - All public pages included:
     - Home page (both languages)
     - About page (both languages)
     - Services page (both languages)
     - Projects page (both languages)
     - Contact page (both languages)
     - All published blog posts (both languages)
     - All active showcase projects (if they have detail pages)
     - All blog categories (both languages)
     - All blog tags (both languages)
   - Minimum 12 URLs (6 main pages × 2 languages)
3. **URL Format:**
   - All URLs absolute (include domain): `https://tarqumi.com/en/about`
   - All URLs use HTTPS
   - All URLs properly encoded (special characters escaped)
   - Trailing slashes consistent
4. **Sitemap Fields:**
   - `<loc>` - URL of the page (required)
   - `<lastmod>` - Last modification date in ISO 8601 format (required)
   - `<changefreq>` - How frequently page changes (optional)
   - `<priority>` - Relative priority 0.0-1.0 (optional)
5. **Priority Values:**
   - Home page: 1.0
   - Main pages (About, Services, Projects, Contact): 0.8
   - Blog list page: 0.8
   - Individual blog posts: 0.6
   - Blog categories: 0.5
   - Blog tags: 0.4
6. **Change Frequency:**
   - Home page: daily
   - Blog list: daily
   - Individual blog posts: weekly
   - Main pages: monthly
   - Categories/Tags: monthly
7. **Dynamic Updates:**
   - Sitemap regenerated automatically when:
     - New blog post published
     - Blog post deleted
     - Page content updated
     - New category/tag created
   - Sitemap cached for 1 hour
   - Manual regeneration option in admin panel
8. **Sitemap Index:**
   - If URLs exceed 50,000 → split into multiple sitemaps
   - Sitemap index at `/sitemap.xml` pointing to sub-sitemaps
   - Sub-sitemaps: `/sitemap-pages.xml`, `/sitemap-blog.xml`, `/sitemap-projects.xml`
9. **Exclusions:**
   - Admin pages excluded
   - Login page excluded
   - API endpoints excluded
   - Draft blog posts excluded
   - Inactive projects excluded
   - Private pages excluded
10. **Robots.txt Reference:**
    - Sitemap URL listed in robots.txt
    - `Sitemap: https://tarqumi.com/sitemap.xml`
11. **Search Console Submission:**
    - Sitemap submitted to Google Search Console
    - Sitemap submitted to Bing Webmaster Tools
    - Submission status monitored
12. **Error Handling:**
    - Invalid URLs excluded from sitemap
    - Broken links excluded
    - 404 pages excluded
    - Redirected URLs show final destination
13. **Validation:**
    - Sitemap validates against XML schema
    - No duplicate URLs
    - All URLs return 200 status
    - All lastmod dates valid
14. **Monitoring:**
    - Sitemap generation time tracked
    - Sitemap size monitored
    - Sitemap errors logged
    - Search Console crawl stats monitored
15. **Multilingual Support:**
    - Separate URLs for each language
    - Hreflang annotations in sitemap (optional)
    - Language-specific lastmod dates

**Edge Cases:**
- EC-1: No blog posts published → sitemap includes only main pages
- EC-2: Blog post published then immediately deleted → removed from sitemap on next generation
- EC-3: URL with special characters (Arabic) → properly encoded
- EC-4: Very large sitemap (100,000+ URLs) → split into multiple sitemaps with index
- EC-5: Sitemap generation fails → cached version served, error logged
- EC-6: Database connection timeout → sitemap generation retried
- EC-7: Invalid URL in database → excluded from sitemap, logged
- EC-8: Duplicate URLs → deduplicated, only one entry in sitemap
- EC-9: URL returns 404 → excluded from sitemap
- EC-10: URL redirects (301) → final destination URL included
- EC-11: Sitemap requested during generation → cached version served
- EC-12: Sitemap too large (> 50MB) → compressed, split if needed
- EC-13: lastmod date in future → corrected to current date
- EC-14: lastmod date missing → uses created_at date
- EC-15: Priority value invalid (> 1.0) → capped at 1.0

**Validation Rules:**
- Sitemap must be valid XML
- All URLs must be absolute and valid
- lastmod dates must be ISO 8601 format
- Priority values must be 0.0-1.0
- changefreq must be valid value (always, hourly, daily, weekly, monthly, yearly, never)
- Maximum 50,000 URLs per sitemap file
- Maximum 50MB uncompressed size per sitemap

**Security Considerations:**
- No sensitive URLs in sitemap
- No admin URLs exposed
- No API endpoints exposed
- No user-specific URLs
- Sitemap generation rate limited (prevent abuse)

**Responsive Design:**
- N/A (XML file)

**Performance:**
- Sitemap generation: < 5 seconds for 10,000 URLs
- Sitemap file size: < 10MB uncompressed
- Sitemap download: < 2 seconds
- Cache hit rate: > 90%

**UX Considerations:**
- Admin can view sitemap in browser (formatted XML)
- Admin can download sitemap
- Admin can manually regenerate sitemap
- Admin can view sitemap statistics (URL count, last generated)
- Admin can exclude specific URLs from sitemap

---

## US-SEO-9.3 🔴 Robots.txt Configuration and Management
**As a** search engine crawler, **I want to** access a robots.txt file with clear crawling instructions, **so that** I know which pages to crawl and which to avoid.

**Acceptance Criteria:**
1. **Robots.txt Accessibility:**
   - Accessible at `/robots.txt`
   - Returns plain text content type: `text/plain`
   - Always returns 200 status (never 404)
   - No authentication required
2. **Default Configuration:**
   ```
   User-agent: *
   Allow: /
   Disallow: /login
   Disallow: /admin
   Disallow: /api
   Disallow: /dashboard
   Disallow: /_next
   Disallow: /static
   
   Sitemap: https://tarqumi.com/sitemap.xml
   ```
3. **User-Agent Specific Rules:**
   - Allow all major search engines: Googlebot, Bingbot, Slurp (Yahoo), DuckDuckBot
   - Block bad bots: AhrefsBot, SemrushBot, MJ12bot (configurable)
   - Block AI scrapers: GPTBot, ChatGPT-User, CCBot (configurable)
4. **Allow Rules:**
   - All public pages allowed: `/`, `/en/*`, `/ar/*`
   - Blog pages allowed: `/en/blog/*`, `/ar/blog/*`
   - Static assets allowed: `/images/*`, `/fonts/*`
5. **Disallow Rules:**
   - Admin panel: `/admin/*`, `/dashboard/*`
   - Authentication: `/login`, `/logout`, `/register`
   - API endpoints: `/api/*`
   - Next.js internals: `/_next/*`
   - Search results: `/search*` (prevent duplicate content)
   - Filtered pages: `/*?filter=*` (prevent duplicate content)
   - Paginated pages: `/*?page=*` (optional, use rel=canonical instead)
6. **Crawl Delay:**
   - No crawl delay for major search engines
   - Crawl delay 10 seconds for aggressive bots
   - Configurable per user-agent
7. **Sitemap Reference:**
   - Sitemap URL included: `Sitemap: https://tarqumi.com/sitemap.xml`
   - Multiple sitemaps if using sitemap index
8. **Dynamic Configuration:**
   - Admin can edit robots.txt from CRM dashboard
   - Changes take effect immediately (no cache)
   - Validation before saving (syntax check)
   - Version history tracked
9. **Testing Tools:**
   - Google Search Console robots.txt tester integration
   - Built-in syntax validator
   - Test specific URLs against rules
   - Preview how different bots see the rules
10. **Special Directives:**
    - `Noindex` directives for specific paths (if needed)
    - `Host` directive for preferred domain (optional)
    - `Clean-param` for URL parameter handling (optional)
11. **Comments:**
    - Clear comments explaining each rule
    - Last updated date in comment
    - Contact information in comment
12. **Monitoring:**
    - Track robots.txt access in logs
    - Alert if robots.txt returns error
    - Monitor bot compliance
13. **Backup:**
    - Robots.txt backed up before changes
    - Restore previous version option
    - Export/import functionality
14. **Multi-Environment:**
    - Different robots.txt for dev/staging/production
    - Staging blocks all bots
    - Production allows search engines
15. **Validation:**
    - Syntax validation before saving
    - No conflicting rules
    - All paths valid

**Edge Cases:**
- EC-1: Robots.txt file missing → auto-generated with defaults
- EC-2: Robots.txt syntax error → validation prevents saving, shows error
- EC-3: Conflicting rules (Allow and Disallow same path) → Disallow takes precedence
- EC-4: Very long robots.txt (> 500KB) → warning shown, may affect crawling
- EC-5: Bot ignores robots.txt → logged, IP can be blocked
- EC-6: Robots.txt blocks important pages → warning shown in admin
- EC-7: Sitemap URL in robots.txt invalid → validation error
- EC-8: User-agent name misspelled → rule ignored by bots
- EC-9: Crawl delay too high (> 60 seconds) → warning shown
- EC-10: Admin accidentally blocks all pages → confirmation required
- EC-11: Robots.txt updated during bot crawl → bot sees old version until next fetch
- EC-12: Multiple sitemaps → all listed in robots.txt
- EC-13: Robots.txt accessed via HTTPS and HTTP → same content served
- EC-14: Robots.txt with Unicode characters → properly encoded
- EC-15: Robots.txt cache issue → cache cleared on update

**Validation Rules:**
- Valid robots.txt syntax
- User-agent names valid
- Paths start with `/`
- Sitemap URLs absolute and valid
- Crawl delay numeric (0-60 seconds)
- File size < 500KB

**Security Considerations:**
- Robots.txt doesn't expose sensitive paths (security through obscurity is not security)
- Admin-only access to edit robots.txt
- Changes logged in audit log
- No sensitive information in comments
- Robots.txt doesn't prevent access (only guides bots)

**Responsive Design:**
- N/A (plain text file)

**Performance:**
- Robots.txt served from memory (no database query)
- Response time: < 50ms
- No caching needed (file rarely changes)

**UX Considerations:**
- Admin interface with syntax highlighting
- Real-time validation
- Preview how bots interpret rules
- Test URL against rules
- Suggested rules for common scenarios
- Import/export functionality
- Version history with diff view


---

## US-SEO-9.4 🔴 Hreflang Tags for Bilingual Pages
**As a** search engine, **I want to** understand the language and regional targeting of pages, **so that** I can serve the correct language version to users.

**Acceptance Criteria:**
1. **Hreflang Implementation:**
   - All bilingual pages include hreflang tags in HTML head
   - Tags point to alternate language versions
   - Tags include x-default for default language
2. **Tag Format:**
   ```html
   <link rel="alternate" hreflang="ar" href="https://tarqumi.com/ar/about" />
   <link rel="alternate" hreflang="en" href="https://tarqumi.com/en/about" />
   <link rel="alternate" hreflang="x-default" href="https://tarqumi.com/en/about" />
   ```
3. **Language Codes:**
   - Arabic: `ar` (not ar-SA, keep it simple)
   - English: `en` (not en-US, keep it simple)
   - Default: `x-default` (points to English version)
4. **Page Coverage:**
   - Home page: `/ar/` ↔ `/en/`
   - About page: `/ar/about` ↔ `/en/about`
   - Services page: `/ar/services` ↔ `/en/services`
   - Projects page: `/ar/projects` ↔ `/en/projects`
   - Contact page: `/ar/contact` ↔ `/en/contact`
   - Blog list: `/ar/blog` ↔ `/en/blog`
   - Blog posts: `/ar/blog/[slug-ar]` ↔ `/en/blog/[slug-en]`
   - Blog categories: `/ar/blog/category/[slug]` ↔ `/en/blog/category/[slug]`
5. **Self-Referencing:**
   - Each page includes hreflang to itself
   - Example: English page includes `hreflang="en"` pointing to itself
6. **Bidirectional Links:**
   - If page A links to page B, page B must link back to page A
   - Arabic page links to English, English links to Arabic
7. **Absolute URLs:**
   - All hreflang URLs must be absolute (include domain)
   - Use HTTPS protocol
   - Include trailing slash if site uses them
8. **Canonical Consistency:**
   - Hreflang URLs match canonical URLs
   - No conflicts between canonical and hreflang
9. **Missing Translations:**
   - If translation doesn't exist, omit that hreflang tag
   - Don't link to 404 pages
   - Don't link to auto-translated pages
10. **Dynamic Content:**
    - Blog posts with both languages → both hreflang tags
    - Blog posts with only one language → only that language's hreflang
    - Showcase projects → hreflang if they have detail pages
11. **Validation:**
    - No broken hreflang links (all return 200)
    - No redirect chains in hreflang URLs
    - No hreflang to non-canonical URLs
    - No duplicate hreflang tags for same language
12. **Testing:**
    - Google Search Console hreflang report shows no errors
    - Hreflang validator tools show no issues
    - Manual testing with VPN (access from different countries)
13. **Monitoring:**
    - Track hreflang errors in Search Console
    - Alert if hreflang tags missing
    - Alert if hreflang links broken
14. **Sitemap Integration:**
    - Hreflang annotations in sitemap (optional enhancement)
    - Sitemap shows language relationships
15. **HTML Lang Attribute:**
    - `<html lang="ar" dir="rtl">` for Arabic pages
    - `<html lang="en" dir="ltr">` for English pages
    - Consistent with hreflang tags

**Edge Cases:**
- EC-1: Blog post exists only in Arabic → only Arabic hreflang tag, no English
- EC-2: Blog post exists only in English → only English hreflang tag, no Arabic
- EC-3: Page URL changes → hreflang updated automatically
- EC-4: Translation added later → hreflang tag added automatically
- EC-5: Translation deleted → hreflang tag removed automatically
- EC-6: Hreflang URL returns 404 → tag removed, error logged
- EC-7: Hreflang URL redirects → final destination used in tag
- EC-8: User manually changes language → hreflang helps search engines understand
- EC-9: Search engine shows wrong language → hreflang helps correct it over time
- EC-10: Multiple domains (future) → hreflang works across domains
- EC-11: Subdomain for language (future) → hreflang works with subdomains
- EC-12: URL parameters in hreflang → parameters stripped or canonical used
- EC-13: Hreflang conflicts with canonical → canonical takes precedence
- EC-14: Page has 3+ languages (future) → all languages in hreflang tags
- EC-15: Hreflang tag malformed → validation prevents deployment

**Validation Rules:**
- Language codes must be valid ISO 639-1
- URLs must be absolute and valid
- All hreflang URLs must return 200 status
- Bidirectional linking required
- No duplicate language codes
- x-default must be present

**Security Considerations:**
- Hreflang URLs validated to prevent injection
- No sensitive URLs in hreflang tags
- Hreflang doesn't expose hidden pages

**Responsive Design:**
- N/A (HTML meta tags)

**Performance:**
- Hreflang tags add minimal overhead (< 1KB)
- Generated during SSR (no additional request)
- Cached with page

**UX Considerations:**
- Language switcher in header uses hreflang URLs
- User's language preference respected
- Smooth transition between languages
- No content flash when switching languages

---

## US-SEO-9.5 🔴 Comprehensive Meta Tags Management
**As a** content manager, **I want to** manage meta tags for all pages, **so that** pages display correctly in search results and social media.

**Acceptance Criteria:**
1. **Basic Meta Tags:**
   - `<title>` - Unique per page, 50-60 characters
   - `<meta name="description">` - Unique per page, 150-160 characters
   - `<meta name="keywords">` - Comma-separated, max 10 keywords
   - `<meta name="author">` - Site or content author
   - `<meta name="viewport">` - Responsive design
   - `<meta charset="UTF-8">` - Character encoding
2. **Open Graph Tags (Facebook, LinkedIn):**
   - `og:title` - Page title for social sharing
   - `og:description` - Page description for social sharing
   - `og:image` - Image for social sharing (1200x630px)
   - `og:url` - Canonical URL of page
   - `og:type` - Page type (website, article, etc.)
   - `og:site_name` - Site name (Tarqumi)
   - `og:locale` - Language (ar_AR or en_US)
   - `article:published_time` - For blog posts
   - `article:modified_time` - For blog posts
   - `article:author` - For blog posts
   - `article:section` - Blog category
   - `article:tag` - Blog tags
3. **Twitter Card Tags:**
   - `twitter:card` - Card type (summary, summary_large_image)
   - `twitter:site` - Twitter handle (@tarqumi)
   - `twitter:creator` - Content creator Twitter handle
   - `twitter:title` - Title for Twitter
   - `twitter:description` - Description for Twitter
   - `twitter:image` - Image for Twitter
   - `twitter:image:alt` - Image alt text
4. **Additional SEO Tags:**
   - `<link rel="canonical">` - Canonical URL
   - `<meta name="robots">` - Indexing instructions (index, follow)
   - `<meta name="googlebot">` - Google-specific instructions
   - `<meta name="bingbot">` - Bing-specific instructions
   - `<meta name="rating">` - Content rating (general)
   - `<meta name="revisit-after">` - Crawl frequency hint
5. **Mobile Meta Tags:**
   - `<meta name="viewport" content="width=device-width, initial-scale=1">` - Responsive
   - `<meta name="mobile-web-app-capable">` - PWA support
   - `<meta name="apple-mobile-web-app-capable">` - iOS support
   - `<meta name="apple-mobile-web-app-status-bar-style">` - iOS status bar
   - `<meta name="theme-color">` - Browser theme color
6. **Favicon and Icons:**
   - `<link rel="icon" type="image/x-icon" href="/favicon.ico">`
   - `<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">`
   - `<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">`
   - `<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">`
   - `<link rel="manifest" href="/site.webmanifest">` - PWA manifest
7. **Per-Page Customization:**
   - Admin can edit meta tags per page from CMS
   - Default values if not customized
   - Preview how page appears in search results
   - Preview how page appears on Facebook
   - Preview how page appears on Twitter
8. **Dynamic Meta Tags:**
   - Blog posts: title, description, image from post data
   - Projects: title, description, image from project data
   - Categories: title, description from category data
   - Tags: title, description from tag data
9. **Template Variables:**
   - `{site_name}` - Tarqumi
   - `{page_title}` - Current page title
   - `{page_description}` - Current page description
   - `{current_year}` - Current year
   - `{author_name}` - Content author name
10. **Validation:**
    - Title length validation (50-60 characters)
    - Description length validation (150-160 characters)
    - Image dimensions validation (1200x630px for OG)
    - URL validation (absolute URLs)
    - No duplicate meta tags
11. **Fallbacks:**
    - If custom title empty → use default site title
    - If custom description empty → use default site description
    - If custom image empty → use default site logo
    - If OG tags empty → use basic meta tags
12. **Bilingual Support:**
    - Separate meta tags for Arabic and English
    - Language-specific titles and descriptions
    - Language-specific images (optional)
13. **Testing Tools:**
    - Facebook Sharing Debugger integration
    - Twitter Card Validator integration
    - LinkedIn Post Inspector integration
    - Google Rich Results Test integration
14. **Monitoring:**
    - Track meta tag errors in Search Console
    - Alert if meta tags missing
    - Alert if meta tags too long/short
    - Track social sharing performance
15. **Bulk Edit:**
    - Admin can bulk edit meta tags for multiple pages
    - Import/export meta tags (CSV)
    - Find and replace in meta tags

**Edge Cases:**
- EC-1: Title exceeds 60 characters → truncated with "..." in search results
- EC-2: Description exceeds 160 characters → truncated in search results
- EC-3: OG image missing → default logo used, warning shown
- EC-4: OG image wrong size → resized automatically, warning shown
- EC-5: Meta tags contain HTML → stripped automatically
- EC-6: Meta tags contain special characters → properly escaped
- EC-7: Duplicate meta tags → last one wins, warning shown
- EC-8: Meta robots set to noindex → page not indexed, warning shown
- EC-9: Canonical URL points to different domain → warning shown
- EC-10: Twitter handle missing → twitter:site tag omitted
- EC-11: Blog post without featured image → default image used
- EC-12: Meta tags not updated after content change → manual refresh option
- EC-13: Social media cache not cleared → admin can force refresh
- EC-14: Meta tags conflict with JSON-LD → JSON-LD takes precedence
- EC-15: Very long keyword list → truncated, warning shown

**Validation Rules:**
- Title: 10-60 characters recommended
- Description: 50-160 characters recommended
- Keywords: max 10, comma-separated
- OG Image: 1200x630px recommended, max 8MB
- Twitter Image: 1200x675px recommended, max 5MB
- URLs: absolute, valid format
- No HTML in meta tags
- No duplicate meta tags

**Security Considerations:**
- Meta tags sanitized to prevent XSS
- No sensitive data in meta tags
- Image URLs validated
- External URLs validated

**Responsive Design:**
- Meta tags work on all devices
- Mobile-specific meta tags included

**Performance:**
- Meta tags generated during SSR
- No additional requests for meta tags
- Minimal overhead (< 2KB)

**UX Considerations:**
- Admin interface with live preview
- Character counters for title and description
- Social media preview cards
- Validation warnings
- Suggested improvements
- Template library for common pages


---

## US-SEO-9.6 🔴 JSON-LD Structured Data Implementation
**As a** search engine, **I want to** understand structured data on pages, **so that** I can display rich snippets in search results.

**Acceptance Criteria:**
1. **Organization Schema (All Pages):**
   ```json
   {
     "@context": "https://schema.org",
     "@type": "Organization",
     "name": "Tarqumi",
     "url": "https://tarqumi.com",
     "logo": "https://tarqumi.com/logo.png",
     "description": "Software development company",
     "address": {
       "@type": "PostalAddress",
       "addressCountry": "SA"
     },
     "contactPoint": {
       "@type": "ContactPoint",
       "telephone": "+966-XXX-XXXX",
       "contactType": "customer service",
       "email": "info@tarqumi.com"
     },
     "sameAs": [
       "https://facebook.com/tarqumi",
       "https://twitter.com/tarqumi",
       "https://linkedin.com/company/tarqumi"
     ]
   }
   ```
2. **Website Schema (Home Page):**
   ```json
   {
     "@context": "https://schema.org",
     "@type": "WebSite",
     "name": "Tarqumi",
     "url": "https://tarqumi.com",
     "potentialAction": {
       "@type": "SearchAction",
       "target": "https://tarqumi.com/search?q={search_term_string}",
       "query-input": "required name=search_term_string"
     }
   }
   ```
3. **Article Schema (Blog Posts):**
   ```json
   {
     "@context": "https://schema.org",
     "@type": "Article",
     "headline": "Blog Post Title",
     "image": "https://tarqumi.com/blog/image.jpg",
     "datePublished": "2024-01-01T10:00:00Z",
     "dateModified": "2024-01-02T15:30:00Z",
     "author": {
       "@type": "Person",
       "name": "Author Name"
     },
     "publisher": {
       "@type": "Organization",
       "name": "Tarqumi",
       "logo": {
         "@type": "ImageObject",
         "url": "https://tarqumi.com/logo.png"
       }
     },
     "description": "Blog post excerpt",
     "articleBody": "Full article content",
     "articleSection": "Category Name",
     "keywords": "keyword1, keyword2, keyword3"
   }
   ```
4. **BreadcrumbList Schema (All Pages):**
   ```json
   {
     "@context": "https://schema.org",
     "@type": "BreadcrumbList",
     "itemListElement": [
       {
         "@type": "ListItem",
         "position": 1,
         "name": "Home",
         "item": "https://tarqumi.com"
       },
       {
         "@type": "ListItem",
         "position": 2,
         "name": "Blog",
         "item": "https://tarqumi.com/blog"
       },
       {
         "@type": "ListItem",
         "position": 3,
         "name": "Post Title",
         "item": "https://tarqumi.com/blog/post-slug"
       }
     ]
   }
   ```
5. **Service Schema (Services Page):**
   ```json
   {
     "@context": "https://schema.org",
     "@type": "Service",
     "serviceType": "Web Development",
     "provider": {
       "@type": "Organization",
       "name": "Tarqumi"
     },
     "areaServed": "SA",
     "description": "Service description"
   }
   ```
6. **FAQPage Schema (If FAQ Section):**
   ```json
   {
     "@context": "https://schema.org",
     "@type": "FAQPage",
     "mainEntity": [
       {
         "@type": "Question",
         "name": "Question text?",
         "acceptedAnswer": {
           "@type": "Answer",
           "text": "Answer text"
         }
       }
     ]
   }
   ```
7. **ContactPage Schema (Contact Page):**
   ```json
   {
     "@context": "https://schema.org",
     "@type": "ContactPage",
     "name": "Contact Us",
     "description": "Get in touch with Tarqumi"
   }
   ```
8. **Implementation:**
   - JSON-LD script tag in HTML head
   - Valid JSON format (no syntax errors)
   - Proper escaping of special characters
   - Minified for production
9. **Dynamic Generation:**
   - Schema generated based on page type
   - Blog posts: Article schema with actual data
   - Services: Service schema for each service
   - Projects: CreativeWork schema (optional)
10. **Validation:**
    - Google Rich Results Test shows no errors
    - Schema.org validator shows valid markup
    - No missing required fields
    - No invalid property values
11. **Testing:**
    - Test with Google Rich Results Test
    - Test with Schema Markup Validator
    - Test with Bing Markup Validator
    - Monitor Search Console for structured data errors
12. **Monitoring:**
    - Track structured data errors in Search Console
    - Alert if required fields missing
    - Track rich snippet appearance in search results
13. **Multiple Schemas:**
    - Pages can have multiple schemas (Organization + Article)
    - Schemas properly nested or separate
    - No conflicting data between schemas
14. **Bilingual Support:**
    - Schema in language of page content
    - Language-specific descriptions
    - inLanguage property set correctly
15. **Updates:**
    - Schema updated when content changes
    - dateModified updated automatically
    - Cache invalidated on schema changes

**Edge Cases:**
- EC-1: Required field missing → schema omitted, error logged
- EC-2: Invalid JSON syntax → schema omitted, error logged
- EC-3: Image URL broken → schema includes URL anyway, warning logged
- EC-4: Date format invalid → corrected to ISO 8601
- EC-5: Author name missing → uses "Tarqumi Team"
- EC-6: Very long description → truncated to reasonable length
- EC-7: Special characters in text → properly escaped
- EC-8: Multiple authors → array of Person objects
- EC-9: Schema conflicts with meta tags → schema takes precedence
- EC-10: Blog post without category → articleSection omitted
- EC-11: Blog post without tags → keywords omitted
- EC-12: Service without description → schema omitted
- EC-13: Breadcrumb with 10+ levels → truncated to 5 levels
- EC-14: Schema validation fails → error logged, schema omitted
- EC-15: Search Console shows errors → admin notified

**Validation Rules:**
- Valid JSON syntax
- Required fields present for each schema type
- URLs absolute and valid
- Dates in ISO 8601 format
- Image URLs return 200 status
- No conflicting data

**Security Considerations:**
- Schema data sanitized to prevent XSS
- No sensitive data in schema
- URLs validated

**Responsive Design:**
- N/A (JSON-LD in head)

**Performance:**
- Schema generated during SSR
- Minimal overhead (< 2KB per schema)
- No additional requests

**UX Considerations:**
- Admin can preview schema
- Admin can validate schema
- Admin can see how rich snippets appear
- Warnings for missing required fields
- Suggestions for improving schema

---

## US-SEO-9.7 🟠 Canonical URLs and Duplicate Content Prevention
**As a** search engine, **I want to** understand which URL is the canonical version, **so that** I don't index duplicate content.

**Acceptance Criteria:**
1. **Canonical Tag Implementation:**
   - All pages include `<link rel="canonical">` tag
   - Canonical URL is absolute (includes domain)
   - Canonical URL uses HTTPS
   - Canonical URL is self-referencing (points to itself)
2. **Canonical URL Format:**
   - Consistent trailing slash usage (with or without)
   - Lowercase URLs preferred
   - No query parameters in canonical (unless necessary)
   - Clean, readable URLs
3. **Duplicate Content Scenarios:**
   - HTTP vs HTTPS → canonical points to HTTPS
   - www vs non-www → canonical points to preferred version
   - Trailing slash vs no trailing slash → canonical consistent
   - Query parameters → canonical without parameters
   - Paginated content → canonical to page 1 or self
   - Filtered content → canonical to unfiltered version
   - Print versions → canonical to regular version
   - Mobile versions → canonical to responsive version
4. **Pagination Handling:**
   - Page 1: canonical to self
   - Page 2+: canonical to self (not page 1)
   - rel="prev" and rel="next" for pagination (optional)
   - View All page: canonical to self
5. **Language Versions:**
   - Each language has its own canonical
   - Arabic page canonical: `/ar/about`
   - English page canonical: `/en/about`
   - No cross-language canonicals
6. **Dynamic Content:**
   - Blog posts: canonical to post URL
   - Categories: canonical to category URL
   - Tags: canonical to tag URL
   - Search results: noindex, no canonical
7. **301 Redirects:**
   - Old URLs redirect to new URLs
   - Redirect target matches canonical URL
   - No redirect chains (A → B → C)
   - Redirect status 301 (permanent)
8. **Parameter Handling:**
   - UTM parameters → canonical without parameters
   - Tracking parameters → canonical without parameters
   - Functional parameters → canonical includes them
   - Sort/filter parameters → canonical without them
9. **Validation:**
   - Canonical URL returns 200 status
   - Canonical URL not redirected
   - Canonical URL not blocked by robots.txt
   - Canonical URL matches hreflang URL
10. **Testing:**
    - Google Search Console shows no canonical errors
    - No duplicate content issues
    - Preferred version indexed
11. **Monitoring:**
    - Track canonical errors in Search Console
    - Alert if canonical missing
    - Alert if canonical points to 404
    - Alert if canonical redirects
12. **Cross-Domain Canonicals:**
    - If content syndicated → canonical to original
    - If migrating domains → canonical to new domain
    - If using CDN → canonical to main domain
13. **AMP Pages (Future):**
    - AMP page canonical to regular page
    - Regular page has amphtml link to AMP
14. **Sitemap Consistency:**
    - Sitemap URLs match canonical URLs
    - No non-canonical URLs in sitemap
15. **Admin Tools:**
    - Admin can view canonical URL per page
    - Admin can override canonical if needed
    - Bulk canonical URL audit tool

**Edge Cases:**
- EC-1: Page accessible via multiple URLs → canonical to preferred URL
- EC-2: Canonical URL returns 404 → error logged, canonical removed
- EC-3: Canonical URL redirects → error logged, canonical updated
- EC-4: Canonical points to different language → error logged, corrected
- EC-5: Canonical missing → auto-generated from current URL
- EC-6: Canonical conflicts with hreflang → hreflang takes precedence
- EC-7: Paginated content with 100+ pages → canonical to self, not page 1
- EC-8: Filtered content with many combinations → canonical to base URL
- EC-9: Search results page → noindex, no canonical
- EC-10: Print version of page → canonical to regular version
- EC-11: Mobile-specific URL (m.tarqumi.com) → canonical to responsive version
- EC-12: Content syndicated to other sites → canonical to original
- EC-13: Canonical URL too long (> 2000 chars) → warning shown
- EC-14: Canonical URL contains fragment (#) → fragment removed
- EC-15: Multiple canonical tags → last one wins, error logged

**Validation Rules:**
- Canonical URL must be absolute
- Canonical URL must use HTTPS
- Canonical URL must return 200 status
- Canonical URL must not redirect
- Only one canonical tag per page
- Canonical URL must be valid format

**Security Considerations:**
- Canonical URLs validated to prevent injection
- No sensitive URLs in canonical tags
- Canonical doesn't expose hidden pages

**Responsive Design:**
- N/A (HTML meta tag)

**Performance:**
- Canonical tag generated during SSR
- No additional requests
- Minimal overhead

**UX Considerations:**
- Admin can see canonical URL in CMS
- Warnings for canonical issues
- Bulk audit tool for canonical URLs
- Automatic canonical generation

---

## US-SEO-9.8 🟠 301 Redirects Management System
**As a** content manager, **I want to** manage 301 redirects, **so that** old URLs redirect to new URLs without losing SEO value.

**Acceptance Criteria:**
1. **Redirect Management Interface:**
   - Admin can add/edit/delete redirects from CRM dashboard
   - Accessible from Settings → SEO → Redirects
   - List view showing all redirects
   - Search and filter redirects
2. **Redirect Fields:**
   - Source URL (old URL, relative or absolute)
   - Destination URL (new URL, relative or absolute)
   - Redirect Type (301 Permanent, 302 Temporary, 307 Temporary)
   - Status (Active, Inactive)
   - Created Date
   - Last Used Date
   - Hit Count (how many times used)
3. **Redirect Types:**
   - 301 Permanent: Default, passes SEO value
   - 302 Temporary: For temporary changes
   - 307 Temporary: Preserves HTTP method
4. **Bulk Import:**
   - Import redirects from CSV
   - CSV format: source,destination,type
   - Validation before import
   - Preview import results
5. **Automatic Redirects:**
   - When blog post slug changes → 301 redirect created automatically
   - When page URL changes → 301 redirect created automatically
   - When category/tag slug changes → 301 redirect created
6. **Redirect Validation:**
   - Source URL must be valid
   - Destination URL must be valid
   - Destination URL must return 200 status
   - No redirect loops (A → B → A)
   - No redirect chains (A → B → C → D)
   - No redirect to self
7. **Wildcard Redirects:**
   - Support for wildcard patterns: `/old-blog/*` → `/blog/*`
   - Regex support for complex patterns (optional)
8. **Query Parameter Handling:**
   - Preserve query parameters by default
   - Option to strip query parameters
   - Option to append query parameters
9. **Testing:**
   - Test redirect before saving
   - Bulk test all redirects
   - Identify broken redirects
10. **Monitoring:**
    - Track redirect usage (hit count)
    - Track redirect errors (404s)
    - Alert if redirect destination returns 404
    - Alert if redirect chain detected
11. **Performance:**
    - Redirects cached in memory
    - Fast lookup (< 10ms)
    - No database query per redirect
12. **Export:**
    - Export redirects to CSV
    - Export for backup
    - Export for migration
13. **Redirect History:**
    - Track when redirect created
    - Track who created redirect
    - Track redirect modifications
14. **Cleanup:**
    - Identify unused redirects (0 hits in 90 days)
    - Bulk delete unused redirects
    - Archive old redirects
15. **Integration:**
    - Redirects work with SSR
    - Redirects work with static pages
    - Redirects work with dynamic pages

**Edge Cases:**
- EC-1: Redirect to external URL → allowed, warning shown
- EC-2: Redirect loop detected → redirect disabled, error logged
- EC-3: Redirect chain detected → warning shown, suggest direct redirect
- EC-4: Redirect destination returns 404 → error logged, redirect disabled
- EC-5: Redirect destination redirects → warning shown
- EC-6: Multiple redirects for same source → last one wins, warning shown
- EC-7: Redirect with query parameters → parameters preserved by default
- EC-8: Redirect to URL with fragment (#) → fragment preserved
- EC-9: Case-sensitive URLs → redirects case-insensitive by default
- EC-10: Trailing slash mismatch → normalized automatically
- EC-11: Very long redirect chain (10+ hops) → error, chain broken
- EC-12: Redirect import with 10,000+ redirects → background job
- EC-13: Redirect conflicts with existing page → warning shown
- EC-14: Wildcard redirect too broad → warning shown
- EC-15: Redirect to same URL → validation error

**Validation Rules:**
- Source URL: required, valid format
- Destination URL: required, valid format, returns 200
- Redirect type: 301, 302, or 307
- No redirect loops
- No redirect chains > 3 hops
- No redirect to self

**Security Considerations:**
- Only Admin and Super Admin can manage redirects
- Redirect URLs validated to prevent open redirect
- No redirects to malicious sites
- All redirect actions logged in audit log

**Responsive Design:**
- Redirect management interface responsive
- Mobile-friendly list and forms

**Performance:**
- Redirect lookup: < 10ms
- Redirects cached in memory
- Cache invalidated on redirect changes
- No database query per redirect

**UX Considerations:**
- Bulk import with validation
- Test redirect before saving
- Visual redirect chain diagram
- Unused redirect identification
- One-click redirect creation from 404 errors
- Suggested redirects based on similar URLs


---

## US-SEO-9.9 🟠 Custom 404 Error Page with SEO Best Practices
**As a** website visitor, **I want to** see a helpful 404 error page when I access a non-existent URL, **so that** I can find what I'm looking for or navigate back to the site.

**Acceptance Criteria:**
1. **404 Page Design:**
   - Custom branded 404 page (not default server error)
   - Clear message: "Page Not Found" or "404 Error"
   - Friendly explanation: "The page you're looking for doesn't exist or has been moved"
   - Tarqumi logo and branding
   - Consistent with site design
2. **Navigation Options:**
   - Link to home page
   - Link to sitemap
   - Search bar to find content
   - Popular pages links (Home, About, Services, Projects, Blog, Contact)
   - Recent blog posts (3-5 posts)
   - Suggested pages based on URL (if possible)
3. **HTTP Status Code:**
   - Returns proper 404 status code (not 200)
   - Search engines understand page doesn't exist
   - No soft 404 errors
4. **SEO Considerations:**
   - 404 page not indexed (noindex meta tag)
   - 404 page not in sitemap
   - Canonical tag omitted or self-referencing
   - No follow links to other pages
5. **Logging and Monitoring:**
   - All 404 errors logged with:
     - Requested URL
     - Referrer URL
     - User agent
     - IP address
     - Timestamp
   - Admin dashboard shows 404 report
   - Most common 404 URLs identified
   - Suggested redirects based on 404 patterns
6. **Automatic Redirect Suggestions:**
   - If URL similar to existing page → suggest redirect
   - If URL is old blog post → suggest new URL
   - If URL has typo → suggest correct URL
   - Admin can create redirect from 404 report
7. **Bilingual Support:**
   - 404 page in both Arabic and English
   - Language detected from URL or browser
   - Language switcher available
8. **Contact Option:**
   - "Can't find what you're looking for? Contact us" link
   - Contact form or email link
9. **Analytics:**
   - Track 404 errors in Google Analytics
   - Custom event for 404 page views
   - Track which pages lead to 404s
10. **Testing:**
    - Test 404 page returns 404 status
    - Test 404 page displays correctly
    - Test 404 page on mobile and desktop
11. **Performance:**
    - 404 page loads quickly (< 1 second)
    - Minimal assets loaded
    - Cached for performance
12. **Accessibility:**
    - 404 page accessible to screen readers
    - Proper heading hierarchy
    - Keyboard navigation works
13. **Humor/Personality (Optional):**
    - Friendly, helpful tone
    - Optional illustration or animation
    - Lighthearted message to reduce frustration
14. **Breadcrumbs:**
    - Breadcrumb showing: Home > 404 Error
    - Helps user understand location
15. **Footer:**
    - Standard site footer included
    - All footer links work

**Edge Cases:**
- EC-1: 404 for image/asset → returns 404 status, no custom page
- EC-2: 404 for API endpoint → returns JSON 404 response
- EC-3: 404 for admin page → redirects to login if not authenticated
- EC-4: 404 with query parameters → parameters ignored, standard 404 shown
- EC-5: 404 for very long URL (> 2000 chars) → truncated in logs
- EC-6: 404 for URL with special characters → properly decoded and logged
- EC-7: 404 for URL with SQL injection attempt → logged as security event
- EC-8: 404 for URL with XSS attempt → sanitized and logged
- EC-9: Repeated 404s from same IP → potential bot, rate limited
- EC-10: 404 for recently deleted page → suggest redirect to similar page
- EC-11: 404 for URL in different language → suggest correct language version
- EC-12: 404 during site migration → bulk redirect creation tool
- EC-13: 404 page itself returns 404 → fallback to server default
- EC-14: 404 page assets missing → graceful degradation
- EC-15: Search on 404 page returns no results → suggest popular pages

**Validation Rules:**
- 404 page must return 404 status code
- 404 page must not be indexed
- 404 page must load in < 2 seconds
- All links on 404 page must work

**Security Considerations:**
- 404 URLs sanitized before logging
- No sensitive data in 404 logs
- Rate limiting for repeated 404s (prevent scanning)
- 404 logs don't expose site structure

**Responsive Design:**
- Mobile (375px): Single column, stacked elements
- Tablet (768px): Two-column layout
- Desktop (1024px+): Centered content with sidebar

**Performance:**
- 404 page load: < 1 second
- 404 page size: < 100KB
- Minimal JavaScript
- Cached for 1 hour

**UX Considerations:**
- Friendly, helpful tone
- Clear navigation options
- Search functionality
- Suggested pages
- Contact option
- No dead ends

---

## US-SEO-9.10 🟠 Performance Optimization and Core Web Vitals
**As a** website visitor, **I want to** experience fast page loads and smooth interactions, **so that** I have a great user experience.

**Acceptance Criteria:**
1. **Core Web Vitals Targets:**
   - Largest Contentful Paint (LCP): < 2.5 seconds
   - First Input Delay (FID): < 100 milliseconds
   - Cumulative Layout Shift (CLS): < 0.1
   - First Contentful Paint (FCP): < 1.8 seconds
   - Time to Interactive (TTI): < 3.5 seconds
   - Total Blocking Time (TBT): < 200 milliseconds
2. **Image Optimization:**
   - All images compressed (WebP format preferred)
   - Responsive images with srcset
   - Lazy loading for below-fold images
   - Proper image dimensions (no layout shift)
   - Alt text for all images
   - Image CDN for faster delivery (optional)
3. **Code Optimization:**
   - Minified CSS and JavaScript
   - Tree shaking (remove unused code)
   - Code splitting (load only what's needed)
   - Critical CSS inlined
   - Non-critical CSS deferred
   - JavaScript deferred or async
4. **Font Optimization:**
   - Web fonts preloaded
   - Font display: swap (prevent invisible text)
   - Subset fonts (only needed characters)
   - Self-hosted fonts (no external requests)
5. **Caching Strategy:**
   - Browser caching with proper headers
   - Service worker for offline support (optional)
   - Static assets cached for 1 year
   - HTML cached for 1 hour
   - API responses cached appropriately
6. **Resource Hints:**
   - DNS prefetch for external domains
   - Preconnect for critical resources
   - Prefetch for next page navigation
   - Preload for critical resources
7. **Third-Party Scripts:**
   - Minimize third-party scripts
   - Load third-party scripts async
   - Self-host scripts when possible
   - Monitor third-party performance impact
8. **Database Optimization:**
   - Proper indexing on all tables
   - Query optimization (no N+1 queries)
   - Database connection pooling
   - Query result caching
9. **Server Optimization:**
   - HTTP/2 or HTTP/3 enabled
   - Gzip or Brotli compression
   - Keep-alive connections
   - Proper server resources (CPU, RAM)
10. **Monitoring:**
    - Real User Monitoring (RUM)
    - Synthetic monitoring (Lighthouse CI)
    - Core Web Vitals tracked in Google Analytics
    - Performance budgets enforced
11. **Testing:**
    - Lighthouse score > 90 for all pages
    - PageSpeed Insights green scores
    - WebPageTest performance grade A
    - GTmetrix grade A
12. **Mobile Performance:**
    - Mobile-first optimization
    - Reduced payload for mobile
    - Touch-friendly interactions
    - No mobile-specific performance issues
13. **Accessibility Performance:**
    - Fast keyboard navigation
    - Screen reader performance
    - No accessibility-related delays
14. **Progressive Enhancement:**
    - Core functionality works without JavaScript
    - Enhanced experience with JavaScript
    - Graceful degradation
15. **Performance Budget:**
    - Total page size: < 1MB
    - JavaScript bundle: < 300KB
    - CSS bundle: < 100KB
    - Images: < 500KB total
    - Fonts: < 100KB

**Edge Cases:**
- EC-1: Slow network connection → progressive loading, skeleton screens
- EC-2: Large images → lazy loaded, compressed
- EC-3: Many third-party scripts → loaded async, monitored
- EC-4: Database slow query → cached, optimized
- EC-5: Server overload → caching prevents, auto-scaling
- EC-6: CDN failure → fallback to origin server
- EC-7: Font loading delay → font-display: swap prevents invisible text
- EC-8: JavaScript error → doesn't block page render
- EC-9: CSS not loaded → content still readable
- EC-10: Service worker error → app still works
- EC-11: Cache stale → revalidated in background
- EC-12: Performance regression → CI fails, deployment blocked
- EC-13: Core Web Vitals fail → alert sent, investigation triggered
- EC-14: Mobile performance poor → mobile-specific optimizations applied
- EC-15: Accessibility performance issue → fixed with priority

**Validation Rules:**
- LCP < 2.5 seconds
- FID < 100 milliseconds
- CLS < 0.1
- Lighthouse score > 90
- Page size < 1MB

**Security Considerations:**
- Performance optimizations don't compromise security
- Caching doesn't expose sensitive data
- CDN properly configured
- Third-party scripts vetted

**Responsive Design:**
- Performance optimized for all devices
- Mobile-specific optimizations
- Responsive images

**Performance:**
- All metrics meet Core Web Vitals thresholds
- Continuous monitoring and optimization
- Performance budgets enforced

**UX Considerations:**
- Fast page loads
- Smooth interactions
- No layout shifts
- Progressive loading
- Offline support (optional)

---

## US-SEO-9.11 🟡 Database Indexing Strategy
**As a** database administrator, **I want to** implement proper indexing on all database tables, **so that** queries are fast and the application performs well.

**Acceptance Criteria:**
1. **Primary Keys:**
   - All tables have primary key
   - Primary keys are indexed automatically
   - Use BIGINT UNSIGNED for IDs
   - Auto-increment for sequential IDs
2. **Foreign Keys:**
   - All foreign keys indexed
   - Foreign key constraints defined
   - Proper ON DELETE and ON UPDATE actions
   - Cascade, SET NULL, or RESTRICT as appropriate
3. **Unique Indexes:**
   - Email fields: unique index
   - Slug fields: unique index
   - Username fields: unique index (if applicable)
   - Composite unique indexes where needed
4. **Search Indexes:**
   - Full-text search on blog content
   - Full-text search on project descriptions
   - Full-text search on service descriptions
   - Indexes on frequently searched columns
5. **Filter Indexes:**
   - Status columns indexed (active, published, etc.)
   - Category/tag columns indexed
   - Date columns indexed (created_at, published_at)
   - Boolean columns indexed (is_featured, is_active)
6. **Sort Indexes:**
   - Columns used in ORDER BY indexed
   - Composite indexes for multi-column sorts
   - Created_at and updated_at indexed
7. **Composite Indexes:**
   - (user_id, created_at) for user activity queries
   - (status, published_at) for published content queries
   - (category_id, published_at) for category listings
   - (is_active, priority) for active items by priority
8. **Index Maintenance:**
   - Regular index analysis
   - Identify unused indexes
   - Remove redundant indexes
   - Rebuild fragmented indexes
9. **Query Optimization:**
   - Explain plan for slow queries
   - Optimize N+1 query problems
   - Use eager loading in Eloquent
   - Avoid SELECT *
10. **Monitoring:**
    - Track slow queries (> 1 second)
    - Monitor index usage
    - Alert on missing indexes
    - Track query performance over time
11. **Testing:**
    - Load testing with realistic data volumes
    - Test queries with and without indexes
    - Measure query performance improvement
12. **Documentation:**
    - Document indexing strategy
    - Document why each index exists
    - Document composite index rationale
13. **Specific Table Indexes:**
    - users: email (unique), created_at
    - blog_posts: slug (unique), status, published_at, author_id, (status, published_at)
    - projects: slug (unique), status, client_id, manager_id, (status, priority)
    - clients: email, name, status
    - contact_submissions: status, created_at, (status, created_at)
    - categories: slug (unique), parent_id
    - tags: slug (unique)
14. **Full-Text Indexes:**
    - blog_posts: FULLTEXT(title, content)
    - projects: FULLTEXT(name, description)
    - services: FULLTEXT(title, description)
15. **Index Size Monitoring:**
    - Track index sizes
    - Alert if index too large
    - Optimize large indexes

**Edge Cases:**
- EC-1: Index too large → consider partitioning or archiving old data
- EC-2: Composite index not used → reorder columns or create separate indexes
- EC-3: Full-text search slow → optimize search query or add more indexes
- EC-4: Index fragmentation → rebuild index
- EC-5: Too many indexes → query performance degrades, remove unused indexes
- EC-6: Index on low-cardinality column → may not be beneficial
- EC-7: Index on frequently updated column → consider trade-offs
- EC-8: Covering index needed → create composite index with all query columns
- EC-9: Index not used by query optimizer → force index or rewrite query
- EC-10: Index size exceeds memory → increase buffer pool or optimize index

**Validation Rules:**
- All foreign keys must be indexed
- All unique constraints must have unique index
- All frequently queried columns should be indexed
- No redundant indexes
- Index names follow convention: idx_table_column

**Security Considerations:**
- Indexes don't expose sensitive data
- Index creation logged
- Only authorized users can create indexes

**Responsive Design:**
- N/A (database optimization)

**Performance:**
- Query response time: < 100ms for simple queries
- Query response time: < 500ms for complex queries
- Index lookup: < 10ms
- Full-text search: < 200ms

**UX Considerations:**
- Fast page loads due to optimized queries
- No noticeable delays
- Smooth user experience

---

## US-SEO-9.12 🟡 CDN Integration and Asset Delivery (Phase 2)
**As a** website visitor, **I want to** load assets quickly from a nearby server, **so that** the website loads fast regardless of my location.

**Acceptance Criteria:**
1. **CDN Provider:**
   - Integrate with CDN provider (Cloudflare, AWS CloudFront, Fastly, etc.)
   - Configure CDN for static assets
   - Configure CDN for images
   - Configure CDN for fonts
2. **Asset Types:**
   - Images served from CDN
   - CSS files served from CDN
   - JavaScript files served from CDN
   - Fonts served from CDN
   - Videos served from CDN (if applicable)
3. **CDN Configuration:**
   - Proper cache headers set
   - Cache TTL configured per asset type
   - Gzip/Brotli compression enabled
   - HTTP/2 or HTTP/3 enabled
4. **Cache Invalidation:**
   - Manual cache purge option
   - Automatic cache purge on asset update
   - Selective cache purge (specific files)
   - Bulk cache purge
5. **Image Optimization:**
   - Automatic image resizing
   - Automatic format conversion (WebP)
   - Automatic compression
   - Responsive images via CDN
6. **Performance:**
   - Assets load from nearest edge location
   - Reduced latency
   - Faster page loads globally
   - Improved Core Web Vitals
7. **Monitoring:**
   - CDN cache hit rate
   - CDN bandwidth usage
   - CDN error rate
   - CDN performance metrics
8. **Fallback:**
   - If CDN fails → fallback to origin server
   - Graceful degradation
   - No broken assets
9. **Security:**
   - HTTPS only
   - Signed URLs for private assets (optional)
   - DDoS protection via CDN
   - WAF rules configured
10. **Cost Optimization:**
    - Monitor CDN costs
    - Optimize cache hit rate
    - Reduce origin requests
    - Use appropriate cache TTLs

**Edge Cases:**
- EC-1: CDN down → fallback to origin server
- EC-2: CDN cache stale → purged and refreshed
- EC-3: CDN costs too high → optimize caching strategy
- EC-4: CDN serves wrong asset → cache purged
- EC-5: CDN SSL certificate expired → auto-renewed
- EC-6: CDN bandwidth limit reached → alert sent
- EC-7: CDN configuration error → rollback to previous config
- EC-8: CDN serves 404 for existing asset → cache purged, asset re-uploaded
- EC-9: CDN performance poor in specific region → investigate and optimize
- EC-10: CDN cache poisoning → security measures prevent

**Validation Rules:**
- All static assets served via CDN
- CDN cache hit rate > 80%
- CDN response time < 100ms
- HTTPS enforced

**Security Considerations:**
- CDN properly configured
- HTTPS only
- DDoS protection enabled
- WAF rules configured
- No sensitive data in CDN

**Responsive Design:**
- CDN serves responsive images
- Proper srcset attributes

**Performance:**
- Asset load time: < 200ms
- CDN cache hit rate: > 80%
- Global performance improved

**UX Considerations:**
- Fast asset loading
- No broken images
- Smooth user experience globally

Done