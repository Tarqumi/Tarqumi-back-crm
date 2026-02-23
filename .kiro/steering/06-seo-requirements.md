---
inclusion: always
priority: 7
---

# SEO Requirements (CRITICAL)

## Server-Side Rendering (SSR)
- **ALL landing page routes MUST use SSR** — no client-side only rendering
- `curl` any landing page → full HTML including all text content (not empty `<div id="root">`)
- Meta tags must be present in source HTML
- SSR for: Home, About, Services, Projects, Blog (list + individual), Contact
- CRM admin pages can be client-side rendered (no SEO needed)

## Meta Tags (Every Page)
Every page MUST have:
- `<title>` — dynamic, from admin settings, ≤60 chars (soft limit)
- `<meta name="description">` — dynamic, ≤160 chars (soft limit)
- `<meta name="keywords">` — dynamic, from admin settings
- `<link rel="canonical">` — pointing to self
- `<html lang="ar">` or `<html lang="en">` based on locale
- `<html dir="rtl">` or `<html dir="ltr">` based on locale

## Open Graph & Social Sharing
Every page MUST have:
- `og:title` — from admin settings or page title
- `og:description` — from admin settings or meta description
- `og:image` — admin-uploaded OG image
- `og:url` — current page URL
- `og:type` — "website" for pages, "article" for blog posts
- `twitter:card` — "summary_large_image"
- `twitter:title`, `twitter:description`, `twitter:image`

Test with:
- WhatsApp link preview
- [Facebook Sharing Debugger](https://developers.facebook.com/tools/debug/)
- Twitter Card Validator

## Semantic HTML Structure
- **One `<h1>` per page** — exactly one, no more, no less
- **Proper heading hierarchy**: h1 → h2 → h3 (never skip levels)
- Use semantic elements: `<nav>`, `<main>`, `<article>`, `<section>`, `<aside>`, `<footer>`
- All `<img>` tags MUST have descriptive `alt` attributes
- Use `<time datetime="...">` for dates (especially blog posts)
- Class names should be semantic (describe content, not presentation)

## Blog SEO (MAXIMUM PRIORITY)
Blog posts MUST have:
1. **JSON-LD structured data** with `Article` schema:
   ```json
   {
     "@context": "https://schema.org",
     "@type": "Article",
     "headline": "Post Title",
     "author": { "@type": "Person", "name": "Author Name" },
     "datePublished": "2026-02-17",
     "dateModified": "2026-02-17",
     "image": "https://example.com/image.jpg",
     "publisher": { "@type": "Organization", "name": "Tarqumi" }
   }
   ```
2. **Clean URLs**: `/en/blog/descriptive-slug` (slugified from title)
3. **Unique meta description** per post (from excerpt)
4. **Featured image** with proper alt text
5. **Author information** visible and in schema
6. **Publish/update dates** with `<time>` elements
7. **Proper heading hierarchy** (h1 for title, h2 for sections)
8. **Internal linking** where relevant
9. **Bilingual versions** with hreflang tags

## Bilingual SEO (hreflang)
Every page MUST have:
```html
<link rel="alternate" hreflang="ar" href="https://tarqumi.com/ar/about" />
<link rel="alternate" hreflang="en" href="https://tarqumi.com/en/about" />
<link rel="alternate" hreflang="x-default" href="https://tarqumi.com/en/about" />
```

## Dynamic Sitemap
- Accessible at `/sitemap.xml`
- Includes ALL pages in both languages
- Includes ALL published blog posts (AR + EN URLs)
- Updates automatically when content changes
- Excludes admin/CRM pages
- Proper `<lastmod>` dates
- Format:
  ```xml
  <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
      <loc>https://tarqumi.com/en/</loc>
      <lastmod>2026-02-17</lastmod>
      <priority>1.0</priority>
    </url>
  </urlset>
  ```

## Robots.txt
Accessible at `/robots.txt`:
```
User-agent: *
Allow: /
Disallow: /login
Disallow: /admin
Disallow: /api

Sitemap: https://tarqumi.com/sitemap.xml
```

## URL Structure
- Clean URLs: `/en/blog/my-post-title` NOT `/blog?id=123`
- Locale prefix: `/ar/` for Arabic, `/en/` for English
- No query parameters in public URLs
- Slugs from titles (transliterated if Arabic)
- Duplicate slugs auto-append number: `my-post-2`

## Performance & SEO
- Page load < 3 seconds
- Images optimized (WebP preferred)
- Lazy loading for below-fold images
- Minified CSS/JS in production
- Lighthouse Performance ≥ 80
- Lighthouse SEO ≥ 90
- Lighthouse Accessibility ≥ 80

## Admin CMS for SEO
Admin can edit per page:
- SEO Title (defaults to page title)
- SEO Description (defaults to excerpt)
- SEO Keywords
- OG Image (upload, max 20MB)
- Character count warnings at 60 (title) and 160 (description)

## Instant Revalidation
- Admin saves CMS change → Next.js on-demand revalidation triggered
- Affected page(s) regenerated within seconds
- No manual cache clearing required
- Targeted revalidation (only changed pages)
