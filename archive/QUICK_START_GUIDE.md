# Quick Start Guide - Tarqumi CRM

> **For:** Developer A (Backend) & Developer B (Frontend)
> **Start Here:** Day 1 Setup Commands

---

## 🚀 DAY 1: Initial Setup (2-3 hours)

### Developer A: Backend Setup

```bash
# 1. Create Laravel project
composer create-project laravel/laravel tarqumi-backend
cd tarqumi-backend

# 2. Install Sanctum
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# 3. Create database
mysql -u root -p
CREATE DATABASE tarqumi_crm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# 4. Configure .env
cp .env.example .env
# Edit .env:
# DB_DATABASE=tarqumi_crm
# DB_USERNAME=root
# DB_PASSWORD=your_password

# 5. Generate key and migrate
php artisan key:generate
php artisan migrate

# 6. Run server
php artisan serve
# Backend running at: http://localhost:8000
```

---

### Developer B: Frontend Setup

```bash
# 1. Create Next.js project
npx create-next-app@latest tarqumi-frontend --typescript --tailwind --app
cd tarqumi-frontend

# 2. Install dependencies
npm install next-intl axios
npm install -D @types/node

# 3. Create folder structure
mkdir -p app/[locale]
mkdir -p components/common
mkdir -p components/layout
mkdir -p components/landing
mkdir -p components/admin
mkdir -p lib
mkdir -p services
mkdir -p types
mkdir -p messages

# 4. Create translation files
echo '{}' > messages/en.json
echo '{}' > messages/ar.json

# 5. Run dev server
npm run dev
# Frontend running at: http://localhost:3000
```

---

## 📁 Project Structure

### Backend (Laravel)
```
tarqumi-backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/
│   │   │       └── V1/
│   │   ├── Middleware/
│   │   ├── Requests/
│   │   └── Resources/
│   ├── Models/
│   ├── Services/
│   └── Enums/
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── routes/
│   └── api.php
└── tests/
```

### Frontend (Next.js)
```
tarqumi-frontend/
├── app/
│   └── [locale]/
│       ├── page.tsx (Home)
│       ├── about/
│       ├── services/
│       ├── projects/
│       ├── blog/
│       ├── contact/
│       ├── login/
│       └── dashboard/
├── components/
│   ├── common/
│   ├── layout/
│   ├── landing/
│   └── admin/
├── lib/
│   └── api.ts
├── services/
├── types/
└── messages/
    ├── en.json
    └── ar.json
```

---

## 🔧 Essential Configuration Files

### Backend: config/cors.php
```php
<?php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:3000'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
```

### Backend: routes/api.php
```php
<?php
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Public routes
    Route::post('/login', [AuthController::class, 'login']);
    
    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::apiResource('clients', ClientController::class);
        Route::apiResource('projects', ProjectController::class);
        Route::apiResource('team', TeamController::class);
    });
});
```

### Frontend: lib/api.ts
```typescript
import axios from 'axios';

const api = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api/v1',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
  withCredentials: true,
});

// Add token to requests
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Handle errors
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('token');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

export default api;
```

### Frontend: next.config.js
```javascript
/** @type {import('next').NextConfig} */
const nextConfig = {
  images: {
    domains: ['localhost'],
  },
  env: {
    NEXT_PUBLIC_API_URL: process.env.NEXT_PUBLIC_API_URL,
  },
};

module.exports = nextConfig;
```

### Frontend: .env.local
```env
NEXT_PUBLIC_API_URL=http://localhost:8000/api/v1
```

---

## 🔐 First Feature: Authentication (Day 2-3)

### Developer A: Create Auth API

```bash
# 1. Create controller
php artisan make:controller Api/V1/AuthController

# 2. Create request
php artisan make:request LoginRequest

# 3. Create seeder for first admin
php artisan make:seeder AdminSeeder
```

**app/Http/Controllers/Api/V1/AuthController.php:**
```php
<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ]);
    }

    public function logout(): JsonResponse
    {
        auth()->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }
}
```

**database/seeders/AdminSeeder.php:**
```php
<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@tarqumi.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);
    }
}
```

```bash
# Run seeder
php artisan db:seed --class=AdminSeeder

# Test with Postman
# POST http://localhost:8000/api/v1/login
# Body: {"email": "admin@tarqumi.com", "password": "password"}
```

---

### Developer B: Create Login Page

**app/[locale]/login/page.tsx:**
```typescript
'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import api from '@/lib/api';

export default function LoginPage() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  const router = useRouter();

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError('');
    setLoading(true);

    try {
      const response = await api.post('/login', { email, password });
      
      if (response.data.success) {
        localStorage.setItem('token', response.data.data.token);
        localStorage.setItem('user', JSON.stringify(response.data.data.user));
        router.push('/dashboard');
      }
    } catch (err: any) {
      setError(err.response?.data?.message || 'Login failed');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-gray-100">
      <div className="bg-white p-8 rounded-lg shadow-md w-96">
        <h1 className="text-2xl font-bold mb-6">Login</h1>
        
        {error && (
          <div className="bg-red-100 text-red-700 p-3 rounded mb-4">
            {error}
          </div>
        )}

        <form onSubmit={handleSubmit}>
          <div className="mb-4">
            <label className="block text-sm font-medium mb-2">Email</label>
            <input
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              className="w-full px-3 py-2 border rounded"
              required
            />
          </div>

          <div className="mb-6">
            <label className="block text-sm font-medium mb-2">Password</label>
            <input
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              className="w-full px-3 py-2 border rounded"
              required
            />
          </div>

          <button
            type="submit"
            disabled={loading}
            className="w-full bg-black text-white py-2 rounded hover:bg-gray-800 disabled:bg-gray-400"
          >
            {loading ? 'Logging in...' : 'Login'}
          </button>
        </form>
      </div>
    </div>
  );
}
```

**Test:**
1. Go to http://localhost:3000/en/login
2. Enter: admin@tarqumi.com / password
3. Should redirect to dashboard

---

## 📋 Daily Checklist

### Every Morning
- [ ] Pull latest code: `git pull origin main`
- [ ] Check for conflicts
- [ ] Review what you'll work on today
- [ ] 15-min standup with your partner

### During Development
- [ ] Commit frequently (every 1-2 hours)
- [ ] Write clear commit messages
- [ ] Test your code before committing
- [ ] Update API documentation (Developer A)
- [ ] Test API integration (Developer B)

### Every Evening
- [ ] Push your code: `git push origin main`
- [ ] Update task status
- [ ] Demo to your partner
- [ ] Plan tomorrow's tasks

---

## 🐛 Common Issues & Solutions

### Issue: CORS Error
**Solution (Developer A):**
```bash
php artisan config:clear
# Check config/cors.php has 'http://localhost:3000' in allowed_origins
```

### Issue: 401 Unauthorized
**Solution (Developer B):**
```typescript
// Check token is being sent
console.log(localStorage.getItem('token'));
// Check API interceptor is adding Authorization header
```

### Issue: Database Connection Failed
**Solution (Developer A):**
```bash
# Check .env file
# Test connection
php artisan tinker
> DB::connection()->getPdo();
```

### Issue: Next.js Build Error
**Solution (Developer B):**
```bash
# Clear cache
rm -rf .next
npm run build
```

---

## 📞 When You're Stuck

1. **Check the error message carefully**
2. **Google the exact error**
3. **Check Laravel/Next.js documentation**
4. **Ask your partner (they might have faced it)**
5. **Check Stack Overflow**
6. **Ask in Discord/Slack**

---

## 🎯 Week 1 Goals

By end of Week 1, you should have:
- [ ] Both projects running locally
- [ ] Database connected
- [ ] Login working (backend + frontend)
- [ ] First admin user created
- [ ] Basic dashboard layout
- [ ] Git workflow established

**If you achieve this, you're on track! 🚀**

---

## 📚 Resources

### Developer A (Backend)
- Laravel Docs: https://laravel.com/docs
- Laravel Sanctum: https://laravel.com/docs/sanctum
- Eloquent ORM: https://laravel.com/docs/eloquent
- Postman: https://www.postman.com/

### Developer B (Frontend)
- Next.js Docs: https://nextjs.org/docs
- React Docs: https://react.dev/
- Tailwind CSS: https://tailwindcss.com/docs
- next-intl: https://next-intl-docs.vercel.app/

### Both
- Git: https://git-scm.com/doc
- GitHub: https://docs.github.com/
- Stack Overflow: https://stackoverflow.com/

---

**Ready? Start with Day 1 setup! Good luck! 💪**

---

## 🎯 Complete Feature Implementation Checklist

### Phase 1: Core Features (Weeks 1-8)

#### ✅ Week 1: Foundation
- [ ] Laravel 11 backend setup with Sanctum
- [ ] Next.js 14 frontend setup with TypeScript
- [ ] MySQL database with UTF8MB4
- [ ] i18n configured (Arabic + English)
- [ ] RTL support implemented
- [ ] API client with interceptors
- [ ] React Query setup
- [ ] Users table with roles
- [ ] Clients table with default "Tarqumi"
- [ ] Projects table with multiple clients
- [ ] Authentication working (login/logout)
- [ ] Role-based access control (RBAC)
- [ ] Protected routes

#### ✅ Week 2-3: Team & Client Management
- [ ] Create team member API + UI
- [ ] List team members with pagination/search/filters
- [ ] Edit team member API + UI
- [ ] Delete team member with project reassignment
- [ ] 30-day auto-inactive functionality
- [ ] Create client API + UI
- [ ] List clients with pagination/search/filters
- [ ] Edit client API + UI
- [ ] Delete client (soft delete, preserve projects)
- [ ] Default "Tarqumi" client protected

#### ✅ Week 3-4: Project Management
- [ ] Create project API + UI
- [ ] Multiple client selection
- [ ] Auto-generated project codes (PROJ-2024-0001)
- [ ] 6 SDLC status phases
- [ ] Priority slider (1-10)
- [ ] Budget with currency
- [ ] List projects with pagination/search/filters
- [ ] Kanban board view
- [ ] Edit project API + UI
- [ ] Delete project (soft delete)
- [ ] Project detail view

#### ✅ Week 4-5: Landing Page & Blog
- [ ] Landing page content API
- [ ] Service cards CRUD
- [ ] Showcase projects CRUD
- [ ] Home page with SSR
- [ ] About, Services, Projects pages
- [ ] Admin CMS UI
- [ ] On-demand revalidation
- [ ] Blog posts CRUD API
- [ ] Blog categories & tags
- [ ] Blog list page with SSR
- [ ] Blog detail page with SSR
- [ ] Blog admin UI with rich text editor
- [ ] Maximum SEO optimization

#### ✅ Week 6: Contact Form & Email
- [ ] Contact form API with validation
- [ ] Rate limiting (5 emails/min)
- [ ] SMTP email sending
- [ ] Email queue with retry logic
- [ ] Multiple recipient configuration
- [ ] Store submissions in database
- [ ] Public contact page
- [ ] Admin contact management UI
- [ ] Email settings UI

#### ✅ Week 7: SEO & Performance
- [ ] Dynamic sitemap.xml
- [ ] robots.txt
- [ ] Meta tags on all pages
- [ ] Open Graph tags
- [ ] Twitter Card tags
- [ ] JSON-LD structured data
- [ ] Hreflang tags
- [ ] Canonical URLs
- [ ] Image optimization (WebP, lazy loading)
- [ ] Code splitting
- [ ] Bundle optimization
- [ ] Lighthouse score 80+
- [ ] Database indexing
- [ ] Query optimization (N+1 prevention)
- [ ] API response caching

#### ✅ Week 8: Testing & Bug Fixes
- [ ] Authentication tests
- [ ] Client CRUD tests
- [ ] Project CRUD tests
- [ ] Team CRUD tests
- [ ] Blog CRUD tests
- [ ] Contact form tests
- [ ] Authorization tests
- [ ] Validation tests
- [ ] SQL injection testing
- [ ] XSS testing
- [ ] CSRF verification
- [ ] Component tests
- [ ] Cross-browser testing
- [ ] Mobile responsiveness
- [ ] UI/UX polish

#### ✅ Week 9-10: Deployment
- [ ] Production environment setup
- [ ] Database migration scripts
- [ ] Production seeders
- [ ] SMTP configuration
- [ ] API documentation
- [ ] Build optimization
- [ ] Final SEO audit
- [ ] Backend deployed
- [ ] Frontend deployed
- [ ] Domain and SSL configured
- [ ] Production testing
- [ ] User documentation
- [ ] Client handover

---

## 📋 Critical Requirements Checklist

### Security Requirements
- [ ] SQL injection prevention (Eloquent ORM, parameterized queries)
- [ ] Input validation on EVERY field (even button types)
- [ ] XSS prevention (Blade {{ }}, sanitized output)
- [ ] CSRF protection (Laravel built-in)
- [ ] Password hashing (bcrypt)
- [ ] Rate limiting (5 contact emails/min, login attempts)
- [ ] Sanctum token authentication
- [ ] Environment variables for secrets (.env)
- [ ] Role-based access control
- [ ] Authorization checks on all endpoints

### Architecture Requirements
- [ ] SOLID principles followed
- [ ] OOP (Object-Oriented Programming)
- [ ] Clean Code practices
- [ ] DRY (Don't Repeat Yourself)
- [ ] Services layer for business logic
- [ ] Thin controllers (< 100 lines)
- [ ] Small functions (< 30 lines)
- [ ] Small files (< 300 lines)
- [ ] Meaningful variable names
- [ ] No magic numbers (use constants/enums)

### i18n Requirements
- [ ] Zero hardcoded strings
- [ ] All text in translation files (en.json, ar.json)
- [ ] RTL layout for Arabic
- [ ] LTR layout for English
- [ ] URL structure: /ar/page and /en/page
- [ ] <html lang> attribute correct
- [ ] <html dir> attribute correct
- [ ] Forms support Arabic input
- [ ] Bilingual admin panel

### SEO Requirements (CRITICAL!)
- [ ] SSR (Server-Side Rendering) for all public pages
- [ ] Dynamic meta tags (title, description, keywords)
- [ ] Open Graph tags for social sharing
- [ ] Twitter Card tags
- [ ] JSON-LD structured data
- [ ] Hreflang tags for bilingual pages
- [ ] Canonical URLs
- [ ] Semantic HTML (h1, nav, main, article, section, footer)
- [ ] One h1 per page
- [ ] Proper heading hierarchy (h1 → h2 → h3)
- [ ] Alt attributes on all images
- [ ] Dynamic sitemap.xml
- [ ] robots.txt
- [ ] Blog SEO exceptional (structured data, slugs, meta)
- [ ] Lighthouse SEO score 90+

### Design Requirements
- [ ] Black & white color scheme with shades
- [ ] CSS variables for all colors
- [ ] No dark mode toggle
- [ ] Animations and micro-interactions
- [ ] Responsive design (mobile-first)
- [ ] Hover states on interactive elements
- [ ] Focus states for accessibility
- [ ] Loading states for API calls
- [ ] Error states with user-friendly messages
- [ ] Success feedback for actions

### Business Logic Requirements
- [ ] Default "Tarqumi" client cannot be deleted
- [ ] Projects can have multiple clients
- [ ] 6 SDLC status phases (Planning, Analysis, Design, Implementation, Testing, Deployment)
- [ ] Priority scale 1-10 (not High/Medium/Low)
- [ ] Auto-generated project codes
- [ ] 30-day inactivity rule (auto-inactive team members)
- [ ] Employee cannot edit own profile
- [ ] Only Super Admin can delete other Admins
- [ ] Only CTO founder can edit landing page
- [ ] Deleting PM requires project reassignment
- [ ] Contact form: 5 emails/min, NO CAPTCHA
- [ ] Max image upload: 20MB
- [ ] Instant revalidation when admin updates content

### Performance Requirements
- [ ] Landing page loads < 3 seconds
- [ ] No N+1 query problems
- [ ] Images optimized (WebP, compressed)
- [ ] Lazy loading for below-fold content
- [ ] CSS/JS bundles minimized
- [ ] Database queries efficient (indexes, EXPLAIN)
- [ ] Pagination on all list endpoints
- [ ] Lighthouse Performance 80+
- [ ] API response times < 500ms
- [ ] SSR pages render correctly

### Testing Requirements
- [ ] Unit tests for services, models, helpers
- [ ] Feature tests for API endpoints
- [ ] Test authentication and authorization
- [ ] Test validation rules
- [ ] Test edge cases
- [ ] Test both happy path and error cases
- [ ] Test Arabic text handling
- [ ] Test RTL layout
- [ ] Cross-browser testing
- [ ] Mobile responsiveness testing
- [ ] All tests pass before commit

---

## 🔧 Advanced Configuration

### Laravel Backend Configuration

**config/database.php - MySQL Optimization:**
```php
'mysql' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'tarqumi_crm'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => 'InnoDB',
    'options' => extension_loaded('pdo_mysql') ? array_filter([
        PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
        PDO::ATTR_EMULATE_PREPARES => true,
    ]) : [],
],
```

**config/queue.php - Email Queue:**
```php
'connections' => [
    'database' => [
        'driver' => 'database',
        'table' => 'jobs',
        'queue' => 'default',
        'retry_after' => 90,
        'after_commit' => false,
    ],
],
```

**config/mail.php - SMTP Configuration:**
```php
'mailers' => [
    'smtp' => [
        'transport' => 'smtp',
        'host' => env('MAIL_HOST', 'smtp.gmail.com'),
        'port' => env('MAIL_PORT', 587),
        'encryption' => env('MAIL_ENCRYPTION', 'tls'),
        'username' => env('MAIL_USERNAME'),
        'password' => env('MAIL_PASSWORD'),
        'timeout' => null,
    ],
],
```

**app/Console/Kernel.php - Scheduled Tasks:**
```php
protected function schedule(Schedule $schedule): void
{
    // Check for inactive team members (30 days)
    $schedule->command('team:check-inactive')->daily();
    
    // Send inactivity warnings (25 days)
    $schedule->command('team:send-warnings')->daily();
    
    // Clean up old soft-deleted records (90 days)
    $schedule->command('cleanup:soft-deletes')->weekly();
    
    // Generate sitemap
    $schedule->command('sitemap:generate')->daily();
}
```

### Next.js Frontend Configuration

**next.config.js - Production Optimization:**
```javascript
const withNextIntl = require('next-intl/plugin')('./src/i18n.ts');

/** @type {import('next').NextConfig} */
const nextConfig = {
  images: {
    domains: ['localhost', 'your-production-domain.com'],
    formats: ['image/webp', 'image/avif'],
    minimumCacheTTL: 60,
  },
  env: {
    NEXT_PUBLIC_API_URL: process.env.NEXT_PUBLIC_API_URL,
  },
  compiler: {
    removeConsole: process.env.NODE_ENV === 'production',
  },
  experimental: {
    optimizeCss: true,
  },
  poweredByHeader: false,
  compress: true,
};

module.exports = withNextIntl(nextConfig);
```

**src/middleware.ts - Complete i18n Middleware:**
```typescript
import createMiddleware from 'next-intl/middleware';
import { NextRequest, NextResponse } from 'next/server';

const intlMiddleware = createMiddleware({
  locales: ['en', 'ar'],
  defaultLocale: 'en',
  localePrefix: 'always',
  localeDetection: true,
});

export default function middleware(request: NextRequest) {
  // Handle i18n
  const response = intlMiddleware(request);
  
  // Add security headers
  response.headers.set('X-Frame-Options', 'DENY');
  response.headers.set('X-Content-Type-Options', 'nosniff');
  response.headers.set('Referrer-Policy', 'strict-origin-when-cross-origin');
  
  return response;
}

export const config = {
  matcher: ['/((?!api|_next|_vercel|.*\\..*).*)']
};
```

---

## 🚨 Common Pitfalls & Solutions

### Backend Pitfalls

**1. N+1 Query Problem**
```php
// ❌ BAD - N+1 queries
$projects = Project::all();
foreach ($projects as $project) {
    echo $project->client->name; // Separate query for each project
}

// ✅ GOOD - Eager loading
$projects = Project::with('client', 'manager')->get();
foreach ($projects as $project) {
    echo $project->client->name; // No additional queries
}
```

**2. SQL Injection**
```php
// ❌ BAD - SQL injection vulnerability
$projects = DB::select("SELECT * FROM projects WHERE name = '$name'");

// ✅ GOOD - Parameterized query
$projects = DB::select('SELECT * FROM projects WHERE name = ?', [$name]);

// ✅ BEST - Eloquent ORM
$projects = Project::where('name', $name)->get();
```

**3. Mass Assignment Vulnerability**
```php
// ❌ BAD - No protection
class User extends Model {
    // No $fillable or $guarded
}

// ✅ GOOD - Explicit fillable fields
class User extends Model {
    protected $fillable = ['name', 'email', 'password'];
}
```

### Frontend Pitfalls

**1. Hardcoded Strings**
```typescript
// ❌ BAD - Hardcoded text
<button>Submit</button>

// ✅ GOOD - Translation key
<button>{t('common.submit')}</button>
```

**2. Missing RTL Support**
```css
/* ❌ BAD - Fixed direction */
.container {
  text-align: left;
  margin-left: 20px;
}

/* ✅ GOOD - RTL-aware */
.container {
  text-align: start;
  margin-inline-start: 20px;
}

[dir="rtl"] .container {
  /* RTL-specific overrides if needed */
}
```

**3. Client-Side Only Rendering (Bad for SEO)**
```typescript
// ❌ BAD - Client-side only
'use client';
export default function Page() {
  const [data, setData] = useState(null);
  useEffect(() => {
    fetch('/api/data').then(r => r.json()).then(setData);
  }, []);
  return <div>{data?.title}</div>;
}

// ✅ GOOD - Server-side rendering
export default async function Page() {
  const data = await fetch('http://localhost:8000/api/data').then(r => r.json());
  return <div>{data.title}</div>;
}
```

---

## 📞 Emergency Troubleshooting

### Backend Issues

**Database Connection Failed:**
```bash
# Test connection
php artisan db:show

# Clear config cache
php artisan config:clear

# Check .env file
cat .env | grep DB_

# Test MySQL connection
mysql -u root -p -e "SHOW DATABASES;"
```

**Sanctum Token Not Working:**
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Check Sanctum config
php artisan config:show sanctum

# Verify CORS settings
php artisan config:show cors
```

**Migration Failed:**
```bash
# Rollback last migration
php artisan migrate:rollback

# Rollback all and re-run
php artisan migrate:fresh

# Check migration status
php artisan migrate:status
```

### Frontend Issues

**Build Failed:**
```bash
# Clear cache
rm -rf .next
rm -rf node_modules
npm install

# Check TypeScript errors
npx tsc --noEmit

# Check for syntax errors
npm run lint
```

**API Connection Failed:**
```bash
# Check environment variables
cat .env.local

# Test API directly
curl http://localhost:8000/api/v1/auth/login

# Check CORS in browser console
# Look for CORS errors in Network tab
```

**i18n Not Working:**
```bash
# Verify middleware
cat src/middleware.ts

# Check translation files exist
ls src/messages/

# Verify next.config.js
cat next.config.js | grep intl
```

---

## 🎓 Learning Resources

### Laravel
- Official Docs: https://laravel.com/docs/11.x
- Laracasts: https://laracasts.com/
- Laravel News: https://laravel-news.com/
- Laravel Daily: https://laraveldaily.com/

### Next.js
- Official Docs: https://nextjs.org/docs
- Next.js Learn: https://nextjs.org/learn
- Vercel Blog: https://vercel.com/blog
- React Docs: https://react.dev/

### Database
- MySQL Docs: https://dev.mysql.com/doc/
- Database Design: https://www.databasestar.com/
- SQL Performance: https://use-the-index-luke.com/

### SEO
- Google Search Central: https://developers.google.com/search
- Schema.org: https://schema.org/
- Lighthouse: https://developer.chrome.com/docs/lighthouse/

---

**Now you have EVERYTHING you need to build the complete Tarqumi CRM! 🚀**
