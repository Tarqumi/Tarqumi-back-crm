---
inclusion: always
priority: 4
---

# Next.js Frontend Rules

## Project Structure
```
frontend/
├── app/                      # Next.js App Router
│   ├── [locale]/             # i18n locale routing (/ar, /en)
│   │   ├── page.tsx          # Home page
│   │   ├── about/
│   │   ├── services/
│   │   ├── projects/
│   │   ├── blog/
│   │   │   ├── page.tsx      # Blog listing
│   │   │   └── [slug]/       # Individual blog post
│   │   ├── contact/
│   │   └── login/            # Admin login
│   ├── [locale]/admin/       # CRM Admin Panel (protected)
│   │   ├── page.tsx          # Dashboard
│   │   ├── team/
│   │   ├── clients/
│   │   ├── projects/
│   │   ├── blog/
│   │   ├── cms/              # Landing page content editor
│   │   ├── contact/          # Contact submissions inbox
│   │   └── settings/
│   ├── api/                  # API routes (revalidation, etc.)
│   │   └── revalidate/
│   ├── layout.tsx
│   └── globals.css
├── components/
│   ├── common/               # Shared components
│   ├── layout/               # Header, Footer, Navigation
│   ├── landing/              # Landing page specific
│   ├── admin/                # Admin panel components
│   └── ui/                   # Base UI primitives
├── lib/                      # Utilities, API client
├── hooks/                    # Custom React hooks
├── services/                 # API service layer
├── types/                    # TypeScript type definitions
├── messages/                 # i18n translation files
│   ├── ar.json
│   └── en.json
├── public/
└── styles/
```

## Component Rules
- Use **functional components** with hooks — NO class components
- Keep components under **200 lines** — split if larger
- One component per file
- Use **TypeScript** for all components with proper type definitions
- Props must have TypeScript interfaces defined
- Use `React.memo()` for expensive components
- Use `useMemo` and `useCallback` to prevent unnecessary re-renders
- Use **error boundaries** for UI-level failure isolation

## Styling Rules
- Use **CSS Modules** or **global CSS with CSS variables** — NO inline styles
- **NEVER** hardcode colors — use CSS custom properties:
  ```css
  :root {
    --color-primary: #000000;
    --color-secondary: #FFFFFF;
    --color-accent: #333333;
    --color-gray-100: #F5F5F5;
    /* ... all colors as variables */
  }
  ```
- Use **responsive design** from the start — mobile-first approach
- Use CSS Grid and Flexbox for layouts
- Animations: use CSS transitions/animations or Framer Motion — keep them smooth (60fps)
- All interactive elements must have **hover states** and **focus states**

## i18n (Internationalization) Rules
- **ZERO hardcoded strings** in any component — every single text must use the i18n system
- Use `next-intl` or similar for Next.js i18n
- Translation keys must be descriptive:
  - ❌ `t('btn1')`, `t('text')`
  - ✅ `t('contact.form.submitButton')`, `t('home.hero.title')`
- Maintain translation files for both `ar.json` and `en.json`
- When adding ANY new text, immediately add it to BOTH translation files
- Support RTL layout switching for Arabic:
  ```css
  [dir="rtl"] .component { /* RTL-specific styles */ }
  ```

## SEO Rules (CRITICAL)
- Use **Server-Side Rendering (SSR)** for ALL landing page routes
- Every page must have:
  - `<title>` — dynamic, from admin settings
  - `<meta name="description">` — dynamic
  - `<meta name="keywords">` — dynamic
  - Open Graph meta tags (`og:title`, `og:description`, `og:image`, `og:url`)
  - Twitter Card meta tags
  - Canonical URL
  - `<html lang="ar">` or `<html lang="en">` based on locale
  - `<html dir="rtl">` or `<html dir="ltr">` based on locale
- Use **semantic HTML**:
  - One `<h1>` per page
  - Proper heading hierarchy (h1 → h2 → h3, never skip)
  - `<nav>`, `<main>`, `<article>`, `<section>`, `<aside>`, `<footer>`
  - `<img>` must have `alt` attributes
- Blog posts must have:
  - JSON-LD structured data (`Article` schema)
  - Proper heading hierarchy
  - Descriptive URLs (slugified titles)
  - `<time>` elements for publish dates
  - Author information
- Generate `sitemap.xml` dynamically
- Generate `robots.txt`
- Implement proper `<link rel="alternate" hreflang="...">` for bilingual pages

## API Communication Rules
- Create a **centralized API service** — never make fetch calls directly in components
- Handle loading, error, and success states for every API call
- Use proper error handling with user-friendly error messages
- Cache API responses where appropriate (SWR or React Query)
- Always include the Sanctum token in API requests
- Handle 401 responses by redirecting to login

## API Response Format
All API endpoints must return consistent JSON:
- Success: `{ "success": true, "data": {...}, "message": "..." }`
- Error: `{ "success": false, "message": "...", "errors": {...} }`
- Proper HTTP status codes: 200, 201, 204, 400, 401, 403, 404, 422, 429, 500
