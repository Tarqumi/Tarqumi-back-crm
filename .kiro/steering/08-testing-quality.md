---
inclusion: always
priority: 9
---

# Testing & Quality Assurance

## Minimum Quality Standards
- **Total Score**: ≥80/100 (weighted)
- **Security Score**: ≥90/100 (non-negotiable)
- **All tests pass** before any merge to main

## Test Coverage Requirements

### Backend Tests (Laravel PHPUnit)
Required test files:
```
tests/
├── Feature/
│   ├── Auth/
│   │   ├── LoginTest.php
│   │   ├── LogoutTest.php
│   │   └── PasswordResetTest.php
│   ├── Client/
│   │   └── ClientCrudTest.php
│   ├── Project/
│   │   └── ProjectCrudTest.php
│   ├── Team/
│   │   └── TeamManagementTest.php
│   ├── Blog/
│   │   └── BlogCrudTest.php
│   ├── LandingPage/
│   │   ├── SeoTest.php
│   │   └── ContentTest.php
│   └── Contact/
│       └── ContactFormTest.php
└── Unit/
    ├── Services/
    ├── Models/
    └── Policies/
```

### What to Test
1. **Authentication**: Login, logout, password reset, unauthorized access
2. **CRUD Operations**: Create, read, update, delete for all entities
3. **Validation**: Every field's validation rules with invalid data
4. **Authorization**: Each role tested against each endpoint
5. **Business Logic**: PM reassignment, 30-day inactivity, default client protection
6. **Edge Cases**: Empty data, max lengths, special characters, Arabic text
7. **Security**: SQL injection, XSS, CSRF, rate limiting

### Frontend Tests (Jest/Testing Library)
```
__tests__/
├── components/
├── pages/
└── integration/
```

Test:
- Component rendering (AR + EN)
- Form validation
- API error handling
- RTL rendering
- User interactions

## Pre-Commit Checklist
Run before EVERY commit:
- [ ] `php artisan test` — all tests pass
- [ ] `npx tsc --noEmit` — no TypeScript errors
- [ ] No ESLint errors/warnings
- [ ] No hardcoded strings (all text in translation files)
- [ ] No `console.log`, `dd()`, or `dump()` in code
- [ ] No hardcoded colors (CSS variables used)
- [ ] File sizes < 300 lines
- [ ] `.env` not staged for commit

## Pre-Deployment Checklist
Before ANY deployment:
- [ ] `APP_DEBUG=false`
- [ ] `APP_ENV=production`
- [ ] All tests pass
- [ ] Lighthouse Performance ≥ 80
- [ ] Lighthouse SEO ≥ 90
- [ ] Lighthouse Accessibility ≥ 80
- [ ] No security vulnerabilities (`npm audit`, `composer audit`)
- [ ] Rate limiting configured
- [ ] HTTPS enforced
- [ ] Database migrations up to date
- [ ] First admin seeded
- [ ] SMTP configured and tested
- [ ] Sitemap accessible
- [ ] Robots.txt accessible
- [ ] Both languages working
- [ ] RTL layout working

## Security Test Cases (Must Pass)
- [ ] SQL injection: `' OR 1=1 --` in all inputs → rejected
- [ ] XSS: `<script>alert(1)</script>` in all inputs → sanitized
- [ ] Login SQL injection: `admin' OR '1'='1` → rejected
- [ ] File upload: `.exe`, `.php` files → rejected
- [ ] File size: 25MB file → rejected (max 20MB)
- [ ] Rate limiting: 6 contact forms in 1 min → 6th rejected
- [ ] Unauthorized access: Employee accessing admin endpoint → 403
- [ ] Password hashing: Check DB, passwords are hashed
- [ ] CSRF: POST without token → 419 error

## Code Quality Checks
- [ ] Controllers < 100 lines, max 5 methods
- [ ] Functions < 30 lines
- [ ] Files < 300 lines
- [ ] No god classes
- [ ] No duplicate logic (DRY)
- [ ] Proper type hints
- [ ] Meaningful variable names
- [ ] No magic numbers
- [ ] Services layer exists
- [ ] Eloquent eager loading used (no N+1)

## Manual Testing Checklist
Test in both AR and EN:
- [ ] All pages load without errors
- [ ] All forms submit successfully
- [ ] All validation works
- [ ] All CRUD operations work
- [ ] Language switcher works
- [ ] RTL layout correct
- [ ] Images display correctly
- [ ] Contact form sends emails
- [ ] Admin CMS updates reflect instantly
- [ ] Blog posts have proper SEO
- [ ] Sitemap includes all pages
- [ ] Mobile responsive (375px, 768px, 1440px)
- [ ] No console errors
- [ ] No broken links

## Performance Benchmarks
- [ ] Landing page loads < 3 seconds
- [ ] API responses < 500ms
- [ ] No N+1 query problems
- [ ] Images optimized
- [ ] CSS/JS minified
- [ ] Lazy loading implemented

## Accessibility Checks
- [ ] Keyboard navigation works
- [ ] Focus indicators visible
- [ ] Color contrast sufficient (WCAG AA)
- [ ] Alt text on all images
- [ ] Semantic HTML used
- [ ] ARIA labels where needed

## Run Tests Command
```bash
# Backend
php artisan test

# Frontend
npm run test

# Lighthouse (in CI or manually)
lighthouse https://localhost:3000 --view
```
