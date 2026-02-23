---
inclusion: always
priority: 8
---

# Internationalization (i18n) & RTL Support

## Core i18n Rules
- **ZERO hardcoded strings** — every single text must use the i18n system
- **No exceptions** — buttons, labels, errors, placeholders, tooltips, everything
- Use `next-intl` or similar for Next.js
- Translation keys must be descriptive and hierarchical

## Translation Key Naming
❌ Bad:
```javascript
t('btn1')
t('text')
t('msg')
```

✅ Good:
```javascript
t('contact.form.submitButton')
t('home.hero.title')
t('errors.validation.emailInvalid')
t('admin.projects.createButton')
```

## Translation Files Structure
```
messages/
├── ar.json
└── en.json
```

Both files MUST have identical keys:
```json
{
  "home": {
    "hero": {
      "title": "Build Your Digital Future",
      "subtitle": "We create exceptional software solutions"
    }
  },
  "contact": {
    "form": {
      "name": "Name",
      "email": "Email",
      "submitButton": "Send Message"
    }
  }
}
```

## Adding New Text
When adding ANY new text:
1. Add key to `en.json`
2. Add key to `ar.json` (with Arabic translation)
3. Use `t('key')` in component
4. NEVER commit with missing translations

## RTL (Right-to-Left) Support
### HTML Attributes
```html
<!-- Arabic -->
<html lang="ar" dir="rtl">

<!-- English -->
<html lang="en" dir="ltr">
```

### CSS for RTL
Use logical properties or RTL-specific styles:
```css
/* Logical properties (auto-flip) */
.element {
  margin-inline-start: 1rem; /* left in LTR, right in RTL */
  padding-inline-end: 2rem;  /* right in LTR, left in RTL */
}

/* RTL-specific overrides */
[dir="rtl"] .element {
  text-align: right;
  /* Flip icons, arrows, etc. */
  transform: scaleX(-1);
}

/* LTR-specific */
[dir="ltr"] .element {
  text-align: left;
}
```

### Layout Considerations
- Navigation mirrors in RTL (right to left)
- Icons/arrows flip direction
- Forms align right in RTL
- Text alignment: right for Arabic, left for English
- Flexbox/Grid: use `flex-start`/`flex-end` instead of `left`/`right`

## URL Structure
- Arabic: `/ar/about`, `/ar/blog/slug-ar`
- English: `/en/about`, `/en/blog/slug-en`
- Default: Redirect `/about` → `/ar/about` (or detect browser language)

## Language Switcher
- Visible in header (flag icon or AR/EN toggle)
- Clicking switches language AND updates URL
- Persists preference (cookie or localStorage)
- Maintains current page: `/en/about` ↔ `/ar/about`

## Validation Messages
All validation errors MUST be in the active language:
```javascript
// Backend (Laravel)
'email.required' => [
  'en' => 'Email is required',
  'ar' => 'البريد الإلكتروني مطلوب'
]

// Frontend
t('errors.validation.emailRequired')
```

## Date & Number Formatting
Format according to locale:
```javascript
// Dates
const date = new Date();
date.toLocaleDateString('ar-SA'); // Arabic format
date.toLocaleDateString('en-US'); // English format

// Numbers
const number = 1234.56;
number.toLocaleString('ar-SA'); // ١٬٢٣٤٫٥٦
number.toLocaleString('en-US'); // 1,234.56
```

## Testing RTL
Test every page in both languages:
- Text renders correctly
- Layout doesn't break
- No text overflow
- Icons/arrows flip appropriately
- Forms work correctly
- Navigation is intuitive
- No horizontal scroll

## Common RTL Issues to Avoid
❌ Hardcoded `text-align: left`
❌ Hardcoded `margin-left` / `padding-right`
❌ Icons that don't flip (arrows, chevrons)
❌ Absolute positioning with `left: 0`
❌ Flexbox with `justify-content: flex-start` without RTL consideration

✅ Use logical properties
✅ Use `[dir="rtl"]` selectors
✅ Test in both languages
✅ Use CSS variables for spacing
✅ Flip directional icons in RTL

## Admin Panel i18n
- Admin panel MUST also be bilingual
- Admin can switch language
- All CRM text uses i18n
- Validation errors in admin's language
- Email notifications in recipient's language (or default)

## Content Management
- Admin enters content in BOTH languages
- Separate fields: `title_ar`, `title_en`
- Blog posts: full AR and EN versions
- Services: AR and EN text
- SEO meta: AR and EN versions
- No auto-translation — manual only
