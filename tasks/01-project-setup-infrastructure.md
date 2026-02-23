# Module 1: Project Setup & Infrastructure

## Overview
This module covers the initial setup of both backend (Laravel) and frontend (Next.js) projects, including all configuration, folder structure, dependencies, and development environment setup.

---

## Task 1.1: Initialize Laravel Backend Project

**Priority:** 🔴 Critical  
**Estimated Time:** 4 hours  
**Dependencies:** None  
**Assigned To:** Backend Developer

**Objective:**
Set up a fresh Laravel 11 project with all required dependencies, configuration, and folder structure following the project's architectural standards.

**Detailed Steps:**

1. **Install Laravel 11:**
   ```bash
   composer create-project laravel/laravel backend "11.*"
   cd backend
   ```

2. **Verify PHP version:**
   ```bash
   php -v  # Must be 8.2 or higher
   ```

3. **Configure `.env` file:**
   - Copy `.env.example` to `.env`
   - Set `APP_NAME="Tarqumi CRM"`
   - Set `APP_ENV=local`
   - Set `APP_DEBUG=true`
   - Set `APP_URL=http://localhost:8000`
   - Configure database:
     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=tarqumi_crm
     DB_USERNAME=root
     DB_PASSWORD=your_password
     ```
   - Set `APP_TIMEZONE=Asia/Riyadh`
   - Set `APP_LOCALE=en`
   - Set `APP_FALLBACK_LOCALE=en`

4. **Generate application key:**
   ```bash
   php artisan key:generate
   ```

5. **Install Laravel Sanctum:**
   ```bash
   composer require laravel/sanctum
   ```

6. **Publish Sanctum configuration:**
   ```bash
   php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
   ```

7. **Create custom folder structure:**
   ```bash
   mkdir -p app/Services
   mkdir -p app/Enums
   mkdir -p app/Policies
   mkdir -p app/Observers
   mkdir -p app/Traits
   mkdir -p app/Http/Requests
   mkdir -p app/Http/Resources
   ```

8. **Add .gitkeep files to maintain folder structure:**
   ```bash
   touch app/Services/.gitkeep
   touch app/Enums/.gitkeep
   touch app/Policies/.gitkeep
   touch app/Observers/.gitkeep
   touch app/Traits/.gitkeep
   ```

9. **Configure CORS in `config/cors.php`:**
   ```php
   'paths' => ['api/*', 'sanctum/csrf-cookie'],
   'allowed_methods' => ['*'],
   'allowed_origins' => [env('FRONTEND_URL', 'http://localhost:3000')],
   'allowed_origins_patterns' => [],
   'allowed_headers' => ['*'],
   'exposed_headers' => ['Authorization'],
   'max_age' => 0,
   'supports_credentials' => true,
   ```

10. **Add Sanctum middleware to `bootstrap/app.php`:**
    ```php
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);
    })
    ```

11. **Update `.env.example` with all required variables:**
    - Add all database variables
    - Add `FRONTEND_URL=http://localhost:3000`
    - Add `SANCTUM_STATEFUL_DOMAINS=localhost:3000`
    - Add SMTP configuration placeholders
    - Add all custom configuration keys

12. **Verify `.gitignore` includes:**
    ```
    .env
    /vendor
    /node_modules
    /public/hot
    /public/storage
    /storage/*.key
    .phpunit.result.cache
    ```

13. **Initialize Git repository:**
    ```bash
    git init
    git add .
    git commit -m "feat: initialize Laravel 11 backend with Sanctum"
    ```

14. **Test server startup:**
    ```bash
    php artisan serve
    ```
    - Visit http://localhost:8000
    - Verify Laravel welcome page loads

15. **Create database:**
    ```bash
    mysql -u root -p
    CREATE DATABASE tarqumi_crm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    EXIT;
    ```

16. **Run initial migrations:**
    ```bash
    php artisan migrate
    ```

17. **Verify migration status:**
    ```bash
    php artisan migrate:status
    ```

**Acceptance Criteria:**
- [ ] Laravel 11 installed successfully
- [ ] PHP version 8.2+ confirmed
- [ ] Database connection successful
- [ ] Sanctum installed and configured
- [ ] Custom folder structure created
- [ ] CORS configured for frontend origin
- [ ] `.env` file configured (not in Git)
- [ ] `.env.example` updated with all variables
- [ ] Git repository initialized with initial commit
- [ ] Server starts without errors
- [ ] Migrations table created in database
- [ ] All default Laravel routes accessible

**Testing:**
```bash
# Test server
php artisan serve

# Test database connection
php artisan migrate:status

# Test routes
php artisan route:list

# Test configuration
php artisan config:show app
php artisan config:show database
```

**Files Created:**
- `backend/.env`
- `backend/config/sanctum.php`
- `backend/app/Services/.gitkeep`
- `backend/app/Enums/.gitkeep`
- `backend/app/Policies/.gitkeep`
- `backend/app/Observers/.gitkeep`
- `backend/app/Traits/.gitkeep`

**Security Considerations:**
- `.env` must be in `.gitignore`
- `APP_KEY` generated securely
- Database credentials not exposed
- `APP_DEBUG=true` only in local environment
- CORS restricted to frontend URL

**Performance Considerations:**
- Opcache enabled in production
- Config cached in production
- Route cached in production

**Notes:**
- Keep `APP_DEBUG=true` only in local
- Document all environment variables
- Use `php artisan key:generate` if APP_KEY missing
- Sanctum requires session configuration

---

## Task 1.2: Initialize Next.js Frontend Project

**Priority:** 🔴 Critical  
**Estimated Time:** 4 hours  
**Dependencies:** None  
**Assigned To:** Frontend Developer

**Objective:**
Set up a fresh Next.js 14+ project with TypeScript, App Router, and all required dependencies for SSR, i18n, and styling.

**Detailed Steps:**

1. **Create Next.js project with TypeScript:**
   ```bash
   npx create-next-app@latest frontend --typescript --tailwind --app --src-dir --import-alias "@/*"
   cd frontend
   ```
   - Select: Yes to TypeScript
   - Select: Yes to ESLint
   - Select: Yes to Tailwind CSS
   - Select: Yes to App Router
   - Select: Yes to customize import alias

2. **Install required dependencies:**
   ```bash
   npm install next-intl
   npm install axios
   npm install @tanstack/react-query
   npm install framer-motion
   npm install react-hook-form
   npm install zod
   npm install date-fns
   npm install clsx
   npm install tailwind-merge
   ```

3. **Install dev dependencies:**
   ```bash
   npm install -D @types/node
   npm install -D prettier
   npm install -D prettier-plugin-tailwindcss
   npm install -D eslint-config-prettier
   ```

4. **Create folder structure:**
   ```bash
   mkdir -p src/app/[locale]
   mkdir -p src/components/common
   mkdir -p src/components/layout
   mkdir -p src/components/landing
   mkdir -p src/components/admin
   mkdir -p src/components/ui
   mkdir -p src/lib
   mkdir -p src/hooks
   mkdir -p src/services
   mkdir -p src/types
   mkdir -p src/messages
   mkdir -p src/styles
   mkdir -p public/images
   mkdir -p public/fonts
   ```

5. **Create i18n configuration file `src/i18n.ts`:**
   ```typescript
   import { getRequestConfig } from 'next-intl/server';
   
   export default getRequestConfig(async ({ locale }) => ({
     messages: (await import(`./messages/${locale}.json`)).default
   }));
   ```

6. **Create translation files:**
   - Create `src/messages/en.json`:
   ```json
   {
     "common": {
       "loading": "Loading...",
       "error": "An error occurred",
       "success": "Success"
     }
   }
   ```
   - Create `src/messages/ar.json`:
   ```json
   {
     "common": {
       "loading": "جاري التحميل...",
       "error": "حدث خطأ",
       "success": "نجح"
     }
   }
   ```

7. **Create middleware for i18n `src/middleware.ts`:**
   ```typescript
   import createMiddleware from 'next-intl/middleware';
   
   export default createMiddleware({
     locales: ['en', 'ar'],
     defaultLocale: 'en',
     localePrefix: 'always'
   });
   
   export const config = {
     matcher: ['/((?!api|_next|_vercel|.*\\..*).*)']
   };
   ```

8. **Update `next.config.js`:**
   ```javascript
   const withNextIntl = require('next-intl/plugin')('./src/i18n.ts');
   
   /** @type {import('next').NextConfig} */
   const nextConfig = {
     images: {
       domains: ['localhost'],
       formats: ['image/webp', 'image/avif'],
     },
     env: {
       NEXT_PUBLIC_API_URL: process.env.NEXT_PUBLIC_API_URL,
     },
   };
   
   module.exports = withNextIntl(nextConfig);
   ```

9. **Create `.env.local` file:**
   ```
   NEXT_PUBLIC_API_URL=http://localhost:8000/api/v1
   NEXT_PUBLIC_APP_URL=http://localhost:3000
   ```

10. **Create `.env.example`:**
    ```
    NEXT_PUBLIC_API_URL=
    NEXT_PUBLIC_APP_URL=
    ```

11. **Update `tailwind.config.ts` with CSS variables:**
    ```typescript
    import type { Config } from 'tailwindcss'
    
    const config: Config = {
      content: [
        './src/pages/**/*.{js,ts,jsx,tsx,mdx}',
        './src/components/**/*.{js,ts,jsx,tsx,mdx}',
        './src/app/**/*.{js,ts,jsx,tsx,mdx}',
      ],
      theme: {
        extend: {
          colors: {
            primary: 'var(--color-primary)',
            secondary: 'var(--color-secondary)',
            accent: 'var(--color-accent)',
          },
        },
      },
      plugins: [],
    }
    export default config
    ```

12. **Create global CSS with variables `src/app/globals.css`:**
    ```css
    @tailwind base;
    @tailwind components;
    @tailwind utilities;
    
    :root {
      --color-primary: #000000;
      --color-secondary: #FFFFFF;
      --color-accent: #333333;
      --color-gray-100: #F5F5F5;
      --color-gray-200: #E5E5E5;
      --color-gray-300: #D4D4D4;
      --color-gray-400: #A3A3A3;
      --color-gray-500: #737373;
      --color-gray-600: #525252;
      --color-gray-700: #404040;
      --color-gray-800: #262626;
      --color-gray-900: #171717;
    }
    
    [dir="rtl"] {
      direction: rtl;
    }
    
    [dir="ltr"] {
      direction: ltr;
    }
    ```

13. **Create root layout `src/app/[locale]/layout.tsx`:**
    ```typescript
    import { NextIntlClientProvider } from 'next-intl';
    import { getMessages } from 'next-intl/server';
    import '../globals.css';
    
    export default async function LocaleLayout({
      children,
      params: { locale }
    }: {
      children: React.ReactNode;
      params: { locale: string };
    }) {
      const messages = await getMessages();
      const direction = locale === 'ar' ? 'rtl' : 'ltr';
    
      return (
        <html lang={locale} dir={direction}>
          <body>
            <NextIntlClientProvider messages={messages}>
              {children}
            </NextIntlClientProvider>
          </body>
        </html>
      );
    }
    ```

14. **Create home page `src/app/[locale]/page.tsx`:**
    ```typescript
    import { useTranslations } from 'next-intl';
    
    export default function HomePage() {
      const t = useTranslations('common');
      
      return (
        <main>
          <h1>Tarqumi CRM</h1>
          <p>{t('loading')}</p>
        </main>
      );
    }
    ```

15. **Create TypeScript configuration for paths `tsconfig.json`:**
    ```json
    {
      "compilerOptions": {
        "target": "ES2017",
        "lib": ["dom", "dom.iterable", "esnext"],
        "allowJs": true,
        "skipLibCheck": true,
        "strict": true,
        "noEmit": true,
        "esModuleInterop": true,
        "module": "esnext",
        "moduleResolution": "bundler",
        "resolveJsonModule": true,
        "isolatedModules": true,
        "jsx": "preserve",
        "incremental": true,
        "plugins": [
          {
            "name": "next"
          }
        ],
        "paths": {
          "@/*": ["./src/*"]
        }
      },
      "include": ["next-env.d.ts", "**/*.ts", "**/*.tsx", ".next/types/**/*.ts"],
      "exclude": ["node_modules"]
    }
    ```

16. **Create Prettier configuration `.prettierrc`:**
    ```json
    {
      "semi": true,
      "trailingComma": "es5",
      "singleQuote": true,
      "printWidth": 100,
      "tabWidth": 2,
      "plugins": ["prettier-plugin-tailwindcss"]
    }
    ```

17. **Update `.gitignore`:**
    ```
    .env*.local
    .next/
    node_modules/
    out/
    .DS_Store
    *.pem
    npm-debug.log*
    yarn-debug.log*
    yarn-error.log*
    .vercel
    ```

18. **Initialize Git:**
    ```bash
    git init
    git add .
    git commit -m "feat: initialize Next.js 14 frontend with TypeScript and i18n"
    ```

19. **Test development server:**
    ```bash
    npm run dev
    ```
    - Visit http://localhost:3000/en
    - Visit http://localhost:3000/ar
    - Verify both routes work

20. **Run TypeScript check:**
    ```bash
    npx tsc --noEmit
    ```

**Acceptance Criteria:**
- [ ] Next.js 14+ installed with TypeScript
- [ ] App Router configured
- [ ] next-intl installed and configured
- [ ] Both `/en` and `/ar` routes work
- [ ] RTL direction applied for Arabic
- [ ] Tailwind CSS configured with CSS variables
- [ ] All required dependencies installed
- [ ] Folder structure matches specification
- [ ] `.env.local` configured (not in Git)
- [ ] Git repository initialized
- [ ] Development server starts without errors
- [ ] No TypeScript errors
- [ ] Translation files created for both languages

**Testing:**
```bash
# Start dev server
npm run dev

# Check TypeScript
npx tsc --noEmit

# Check ESLint
npm run lint

# Build for production
npm run build
```

**Files Created:**
- `frontend/.env.local`
- `frontend/src/i18n.ts`
- `frontend/src/middleware.ts`
- `frontend/src/messages/en.json`
- `frontend/src/messages/ar.json`
- `frontend/src/app/[locale]/layout.tsx`
- `frontend/src/app/[locale]/page.tsx`
- `frontend/src/app/globals.css`
- `frontend/.prettierrc`

**Security Considerations:**
- `.env.local` in `.gitignore`
- No API keys in client-side code
- Environment variables prefixed with `NEXT_PUBLIC_`

**Performance Considerations:**
- SSR enabled for SEO
- Image optimization configured
- Tailwind CSS purging enabled

**i18n Considerations:**
- Locale routing configured
- RTL support for Arabic
- Translation files structured
- Middleware handles locale detection

**Notes:**
- Use `npm run dev` for development
- Use `npm run build` before deployment
- All text must use translation keys
- CSS variables for all colors

---

## Task 1.3: Configure Database and Create Initial Migration

**Priority:** 🔴 Critical  
**Estimated Time:** 2 hours  
**Dependencies:** Task 1.1  
**Assigned To:** Backend Developer

**Objective:**
Create the MySQL database with proper character set and collation, and set up the initial migration structure.

**Detailed Steps:**

1. **Create database with proper encoding:**
   ```sql
   CREATE DATABASE tarqumi_crm 
   CHARACTER SET utf8mb4 
   COLLATE utf8mb4_unicode_ci;
   ```

2. **Verify database creation:**
   ```sql
   SHOW DATABASES LIKE 'tarqumi_crm';
   SHOW CREATE DATABASE tarqumi_crm;
   ```

3. **Create database user (optional, for production):**
   ```sql
   CREATE USER 'tarqumi_user'@'localhost' IDENTIFIED BY 'secure_password';
   GRANT SELECT, INSERT, UPDATE, DELETE ON tarqumi_crm.* TO 'tarqumi_user'@'localhost';
   FLUSH PRIVILEGES;
   ```

4. **Update `.env` with database credentials:**
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=tarqumi_crm
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

5. **Test database connection:**
   ```bash
   php artisan db:show
   ```

6. **Run default Laravel migrations:**
   ```bash
   php artisan migrate
   ```

7. **Verify migrations table:**
   ```bash
   php artisan migrate:status
   ```

8. **Create migration for users table enhancement:**
   ```bash
   php artisan make:migration enhance_users_table
   ```

9. **Update the migration file to add custom fields:**
   ```php
   public function up(): void
   {
       Schema::table('users', function (Blueprint $table) {
           $table->string('phone', 20)->nullable()->after('email');
           $table->string('whatsapp', 20)->nullable()->after('phone');
           $table->enum('role', ['super_admin', 'admin', 'founder', 'hr', 'employee'])
                 ->default('employee')->after('password');
           $table->enum('founder_role', ['ceo', 'cto', 'cfo'])->nullable()->after('role');
           $table->boolean('is_active')->default(true)->after('founder_role');
           $table->timestamp('last_login_at')->nullable()->after('is_active');
           $table->softDeletes();
           
           $table->index('email');
           $table->index('role');
           $table->index('is_active');
       });
   }
   
   public function down(): void
   {
       Schema::table('users', function (Blueprint $table) {
           $table->dropColumn([
               'phone', 'whatsapp', 'role', 'founder_role', 
               'is_active', 'last_login_at'
           ]);
           $table->dropSoftDeletes();
       });
   }
   ```

10. **Run the new migration:**
    ```bash
    php artisan migrate
    ```

11. **Create database seeder for first admin:**
    ```bash
    php artisan make:seeder AdminSeeder
    ```

12. **Update `database/seeders/AdminSeeder.php`:**
    ```php
    use Illuminate\Support\Facades\Hash;
    use App\Models\User;
    
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@tarqumi.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }
    ```

13. **Update `database/seeders/DatabaseSeeder.php`:**
    ```php
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
        ]);
    }
    ```

14. **Run seeders:**
    ```bash
    php artisan db:seed
    ```

15. **Verify admin user created:**
    ```bash
    php artisan tinker
    >>> User::where('email', 'admin@tarqumi.com')->first();
    >>> exit
    ```

16. **Create database backup script:**
    ```bash
    mkdir scripts
    touch scripts/backup-db.sh
    chmod +x scripts/backup-db.sh
    ```

17. **Add backup script content:**
    ```bash
    #!/bin/bash
    DATE=$(date +%Y%m%d_%H%M%S)
    mysqldump -u root -p tarqumi_crm > backups/tarqumi_crm_$DATE.sql
    ```

18. **Create backups directory:**
    ```bash
    mkdir backups
    echo "backups/" >> .gitignore
    ```

**Acceptance Criteria:**
- [ ] Database created with UTF8MB4 encoding
- [ ] Database connection successful
- [ ] Default migrations run successfully
- [ ] Users table enhanced with custom fields
- [ ] Indexes created on searchable columns
- [ ] Soft deletes enabled on users table
- [ ] First admin user seeded
- [ ] Admin user can be queried from database
- [ ] Database backup script created
- [ ] All migrations reversible

**Testing:**
```bash
# Test connection
php artisan db:show

# Test migrations
php artisan migrate:status

# Test rollback
php artisan migrate:rollback
php artisan migrate

# Test seeder
php artisan db:seed --class=AdminSeeder

# Query admin user
php artisan tinker
>>> User::first()
```

**Files Created:**
- `database/migrations/YYYY_MM_DD_enhance_users_table.php`
- `database/seeders/AdminSeeder.php`
- `scripts/backup-db.sh`

**Security Considerations:**
- Database user has minimal privileges
- Passwords hashed with bcrypt
- Soft deletes preserve data
- Indexes improve query performance

**Performance Considerations:**
- Indexes on frequently queried columns
- UTF8MB4 supports emoji and Arabic
- InnoDB engine for transactions

**Notes:**
- Change default admin password after first login
- Backup database before major changes
- Use migrations for all schema changes
- Never edit migration files after running them

---


## Task 1.4: Setup API Service Layer (Frontend)

**Priority:** 🔴 Critical  
**Estimated Time:** 3 hours  
**Dependencies:** Task 1.2  
**Assigned To:** Frontend Developer

**Objective:**
Create a centralized API service layer for all backend communication with proper error handling, authentication, and request/response interceptors.

**Detailed Steps:**

1. **Create API client configuration `src/lib/api-client.ts`:**
   ```typescript
   import axios, { AxiosInstance, AxiosError, InternalAxiosRequestConfig } from 'axios';
   
   const apiClient: AxiosInstance = axios.create({
     baseURL: process.env.NEXT_PUBLIC_API_URL,
     timeout: 10000,
     headers: {
       'Content-Type': 'application/json',
       'Accept': 'application/json',
     },
     withCredentials: true,
   });
   
   // Request interceptor
   apiClient.interceptors.request.use(
     (config: InternalAxiosRequestConfig) => {
       const token = localStorage.getItem('auth_token');
       if (token && config.headers) {
         config.headers.Authorization = `Bearer ${token}`;
       }
       return config;
     },
     (error: AxiosError) => {
       return Promise.reject(error);
     }
   );
   
   // Response interceptor
   apiClient.interceptors.response.use(
     (response) => response,
     async (error: AxiosError) => {
       if (error.response?.status === 401) {
         localStorage.removeItem('auth_token');
         window.location.href = '/en/login';
       }
       return Promise.reject(error);
     }
   );
   
   export default apiClient;
   ```

2. **Create API response types `src/types/api.ts`:**
   ```typescript
   export interface ApiResponse<T = any> {
     success: boolean;
     data: T;
     message?: string;
     errors?: Record<string, string[]>;
   }
   
   export interface PaginatedResponse<T> {
     data: T[];
     current_page: number;
     last_page: number;
     per_page: number;
     total: number;
     from: number;
     to: number;
   }
   
   export interface ApiError {
     message: string;
     errors?: Record<string, string[]>;
     status?: number;
   }
   ```

3. **Create authentication service `src/services/auth.service.ts`:**
   ```typescript
   import apiClient from '@/lib/api-client';
   import { ApiResponse } from '@/types/api';
   
   export interface LoginCredentials {
     email: string;
     password: string;
   }
   
   export interface AuthUser {
     id: number;
     name: string;
     email: string;
     role: string;
     founder_role?: string;
   }
   
   export interface LoginResponse {
     user: AuthUser;
     token: string;
   }
   
   class AuthService {
     async login(credentials: LoginCredentials): Promise<ApiResponse<LoginResponse>> {
       const response = await apiClient.post('/auth/login', credentials);
       if (response.data.success && response.data.data.token) {
         localStorage.setItem('auth_token', response.data.data.token);
       }
       return response.data;
     }
   
     async logout(): Promise<void> {
       await apiClient.post('/auth/logout');
       localStorage.removeItem('auth_token');
     }
   
     async getCurrentUser(): Promise<ApiResponse<AuthUser>> {
       const response = await apiClient.get('/auth/user');
       return response.data;
     }
   
     async refreshToken(): Promise<ApiResponse<{ token: string }>> {
       const response = await apiClient.post('/auth/refresh');
       if (response.data.success && response.data.data.token) {
         localStorage.setItem('auth_token', response.data.data.token);
       }
       return response.data;
     }
   
     getToken(): string | null {
       return localStorage.getItem('auth_token');
     }
   
     isAuthenticated(): boolean {
       return !!this.getToken();
     }
   }
   
   export default new AuthService();
   ```

4. **Create base CRUD service `src/services/base.service.ts`:**
   ```typescript
   import apiClient from '@/lib/api-client';
   import { ApiResponse, PaginatedResponse } from '@/types/api';
   
   export class BaseService<T> {
     constructor(protected endpoint: string) {}
   
     async getAll(params?: Record<string, any>): Promise<ApiResponse<PaginatedResponse<T>>> {
       const response = await apiClient.get(this.endpoint, { params });
       return response.data;
     }
   
     async getById(id: number | string): Promise<ApiResponse<T>> {
       const response = await apiClient.get(`${this.endpoint}/${id}`);
       return response.data;
     }
   
     async create(data: Partial<T>): Promise<ApiResponse<T>> {
       const response = await apiClient.post(this.endpoint, data);
       return response.data;
     }
   
     async update(id: number | string, data: Partial<T>): Promise<ApiResponse<T>> {
       const response = await apiClient.put(`${this.endpoint}/${id}`, data);
       return response.data;
     }
   
     async delete(id: number | string): Promise<ApiResponse<void>> {
       const response = await apiClient.delete(`${this.endpoint}/${id}`);
       return response.data;
     }
   }
   ```

5. **Create error handler utility `src/lib/error-handler.ts`:**
   ```typescript
   import { AxiosError } from 'axios';
   import { ApiError } from '@/types/api';
   
   export function handleApiError(error: unknown): ApiError {
     if (error instanceof AxiosError) {
       const status = error.response?.status;
       const data = error.response?.data;
   
       return {
         message: data?.message || error.message || 'An error occurred',
         errors: data?.errors,
         status,
       };
     }
   
     if (error instanceof Error) {
       return {
         message: error.message,
       };
     }
   
     return {
       message: 'An unknown error occurred',
     };
   }
   
   export function getErrorMessage(error: ApiError, field?: string): string {
     if (field && error.errors?.[field]) {
       return error.errors[field][0];
     }
     return error.message;
   }
   ```

6. **Create React Query configuration `src/lib/react-query.ts`:**
   ```typescript
   import { QueryClient } from '@tanstack/react-query';
   
   export const queryClient = new QueryClient({
     defaultOptions: {
       queries: {
         staleTime: 5 * 60 * 1000, // 5 minutes
         retry: 1,
         refetchOnWindowFocus: false,
       },
       mutations: {
         retry: 0,
       },
     },
   });
   ```

7. **Create Query Provider component `src/components/providers/query-provider.tsx`:**
   ```typescript
   'use client';
   
   import { QueryClientProvider } from '@tanstack/react-query';
   import { queryClient } from '@/lib/react-query';
   import { ReactNode } from 'react';
   
   export function QueryProvider({ children }: { children: ReactNode }) {
     return (
       <QueryClientProvider client={queryClient}>
         {children}
       </QueryClientProvider>
     );
   }
   ```

8. **Update root layout to include QueryProvider:**
   ```typescript
   import { QueryProvider } from '@/components/providers/query-provider';
   
   export default async function LocaleLayout({
     children,
     params: { locale }
   }: {
     children: React.ReactNode;
     params: { locale: string };
   }) {
     const messages = await getMessages();
     const direction = locale === 'ar' ? 'rtl' : 'ltr';
   
     return (
       <html lang={locale} dir={direction}>
         <body>
           <NextIntlClientProvider messages={messages}>
             <QueryProvider>
               {children}
             </QueryProvider>
           </NextIntlClientProvider>
         </body>
       </html>
     );
   }
   ```

9. **Create custom hooks for API calls `src/hooks/use-api.ts`:**
   ```typescript
   import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
   import { handleApiError } from '@/lib/error-handler';
   
   export function useApiQuery<T>(
     key: string[],
     queryFn: () => Promise<T>,
     options?: any
   ) {
     return useQuery({
       queryKey: key,
       queryFn,
       ...options,
     });
   }
   
   export function useApiMutation<TData, TVariables>(
     mutationFn: (variables: TVariables) => Promise<TData>,
     options?: {
       onSuccess?: (data: TData) => void;
       onError?: (error: any) => void;
       invalidateQueries?: string[][];
     }
   ) {
     const queryClient = useQueryClient();
   
     return useMutation({
       mutationFn,
       onSuccess: (data) => {
         options?.onSuccess?.(data);
         options?.invalidateQueries?.forEach((key) => {
           queryClient.invalidateQueries({ queryKey: key });
         });
       },
       onError: (error) => {
         const apiError = handleApiError(error);
         options?.onError?.(apiError);
       },
     });
   }
   ```

10. **Create loading component `src/components/ui/loading.tsx`:**
    ```typescript
    export function Loading() {
      return (
        <div className="flex items-center justify-center p-8">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
        </div>
      );
    }
    ```

11. **Create error component `src/components/ui/error-message.tsx`:**
    ```typescript
    interface ErrorMessageProps {
      message: string;
      onRetry?: () => void;
    }
    
    export function ErrorMessage({ message, onRetry }: ErrorMessageProps) {
      return (
        <div className="bg-red-50 border border-red-200 rounded-lg p-4">
          <p className="text-red-800">{message}</p>
          {onRetry && (
            <button
              onClick={onRetry}
              className="mt-2 text-sm text-red-600 hover:text-red-800"
            >
              Try Again
            </button>
          )}
        </div>
      );
    }
    ```

12. **Create environment variables type definitions `src/types/env.d.ts`:**
    ```typescript
    declare namespace NodeJS {
      interface ProcessEnv {
        NEXT_PUBLIC_API_URL: string;
        NEXT_PUBLIC_APP_URL: string;
      }
    }
    ```

13. **Test API client with a simple request:**
    ```typescript
    // Create test file: src/app/[locale]/test-api/page.tsx
    'use client';
    
    import { useEffect, useState } from 'react';
    import apiClient from '@/lib/api-client';
    
    export default function TestApiPage() {
      const [status, setStatus] = useState('Testing...');
    
      useEffect(() => {
        apiClient.get('/health')
          .then(() => setStatus('API Connected ✓'))
          .catch(() => setStatus('API Connection Failed ✗'));
      }, []);
    
      return <div>{status}</div>;
    }
    ```

**Acceptance Criteria:**
- [ ] API client configured with base URL
- [ ] Request interceptor adds auth token
- [ ] Response interceptor handles 401 errors
- [ ] Authentication service created
- [ ] Base CRUD service created
- [ ] Error handler utility created
- [ ] React Query configured
- [ ] Query Provider added to layout
- [ ] Custom hooks for API calls created
- [ ] Loading and error components created
- [ ] TypeScript types for API responses
- [ ] Environment variables typed
- [ ] Test page verifies API connection

**Testing:**
```bash
# Start frontend
npm run dev

# Visit test page
# http://localhost:3000/en/test-api

# Check browser console for API calls
# Check Network tab in DevTools
```

**Files Created:**
- `src/lib/api-client.ts`
- `src/types/api.ts`
- `src/services/auth.service.ts`
- `src/services/base.service.ts`
- `src/lib/error-handler.ts`
- `src/lib/react-query.ts`
- `src/components/providers/query-provider.tsx`
- `src/hooks/use-api.ts`
- `src/components/ui/loading.tsx`
- `src/components/ui/error-message.tsx`
- `src/types/env.d.ts`

**Security Considerations:**
- Auth token stored in localStorage
- Token included in all authenticated requests
- 401 responses trigger logout
- CORS credentials enabled
- No sensitive data in error messages

**Performance Considerations:**
- Request timeout set to 10 seconds
- React Query caching configured
- Stale time set to 5 minutes
- Retry logic configured

**Notes:**
- All API calls go through this service
- Never use fetch directly in components
- Always handle loading and error states
- Use React Query for data fetching

---

## Task 1.5: Setup Development Environment and Tools

**Priority:** 🟠 High  
**Estimated Time:** 2 hours  
**Dependencies:** Task 1.1, Task 1.2  
**Assigned To:** Full-Stack Developer

**Objective:**
Configure development tools, linters, formatters, and scripts for consistent code quality across the team.

**Detailed Steps:**

1. **Install Laravel development dependencies:**
   ```bash
   cd backend
   composer require --dev laravel/pint
   composer require --dev barryvdh/laravel-debugbar
   composer require --dev nunomaduro/larastan
   ```

2. **Configure Laravel Pint `backend/pint.json`:**
   ```json
   {
     "preset": "laravel",
     "rules": {
       "simplified_null_return": true,
       "braces": false,
       "new_with_braces": true,
       "method_argument_space": {
         "on_multiline": "ensure_fully_multiline"
       }
     }
   }
   ```

3. **Add Pint script to `backend/composer.json`:**
   ```json
   "scripts": {
     "pint": "pint",
     "pint-test": "pint --test"
   }
   ```

4. **Configure PHPStan `backend/phpstan.neon`:**
   ```neon
   includes:
     - ./vendor/nunomaduro/larastan/extension.neon
   
   parameters:
     paths:
       - app
     level: 5
     ignoreErrors:
       - '#PHPDoc tag @var#'
     excludePaths:
       - ./*/*/FileToBeExcluded.php
   ```

5. **Add PHPStan script to `backend/composer.json`:**
   ```json
   "scripts": {
     "analyse": "phpstan analyse"
   }
   ```

6. **Configure ESLint `frontend/.eslintrc.json`:**
   ```json
   {
     "extends": [
       "next/core-web-vitals",
       "prettier"
     ],
     "rules": {
       "no-console": ["warn", { "allow": ["warn", "error"] }],
       "no-unused-vars": "warn",
       "@typescript-eslint/no-unused-vars": "warn",
       "react-hooks/exhaustive-deps": "warn"
     }
   }
   ```

7. **Update `frontend/package.json` scripts:**
   ```json
   "scripts": {
     "dev": "next dev",
     "build": "next build",
     "start": "next start",
     "lint": "next lint",
     "lint:fix": "next lint --fix",
     "format": "prettier --write \"src/**/*.{js,jsx,ts,tsx,json,css,md}\"",
     "format:check": "prettier --check \"src/**/*.{js,jsx,ts,tsx,json,css,md}\"",
     "type-check": "tsc --noEmit"
   }
   ```

8. **Create VS Code workspace settings `.vscode/settings.json`:**
   ```json
   {
     "editor.formatOnSave": true,
     "editor.defaultFormatter": "esbenp.prettier-vscode",
     "editor.codeActionsOnSave": {
       "source.fixAll.eslint": true
     },
     "[php]": {
       "editor.defaultFormatter": "bmewburn.vscode-intelephense-client"
     },
     "files.associations": {
       "*.blade.php": "blade"
     },
     "emmet.includeLanguages": {
       "blade": "html"
     }
   }
   ```

9. **Create VS Code extensions recommendations `.vscode/extensions.json`:**
   ```json
   {
     "recommendations": [
       "bmewburn.vscode-intelephense-client",
       "amiralizadeh9480.laravel-extra-intellisense",
       "onecentlin.laravel-blade",
       "dbaeumer.vscode-eslint",
       "esbenp.prettier-vscode",
       "bradlc.vscode-tailwindcss",
       "ms-vscode.vscode-typescript-next"
     ]
   }
   ```

10. **Create EditorConfig `.editorconfig`:**
    ```ini
    root = true
    
    [*]
    charset = utf-8
    end_of_line = lf
    insert_final_newline = true
    indent_style = space
    indent_size = 4
    trim_trailing_whitespace = true
    
    [*.md]
    trim_trailing_whitespace = false
    
    [*.{yml,yaml,json}]
    indent_size = 2
    
    [*.{js,jsx,ts,tsx}]
    indent_size = 2
    ```

11. **Create Git hooks directory:**
    ```bash
    mkdir -p .git/hooks
    ```

12. **Create pre-commit hook `.git/hooks/pre-commit`:**
    ```bash
    #!/bin/sh
    
    echo "Running pre-commit checks..."
    
    # Backend checks
    cd backend
    echo "Checking PHP code style..."
    composer pint-test
    if [ $? -ne 0 ]; then
        echo "PHP code style check failed. Run 'composer pint' to fix."
        exit 1
    fi
    
    # Frontend checks
    cd ../frontend
    echo "Checking TypeScript..."
    npm run type-check
    if [ $? -ne 0 ]; then
        echo "TypeScript check failed."
        exit 1
    fi
    
    echo "Checking code formatting..."
    npm run format:check
    if [ $? -ne 0 ]; then
        echo "Code formatting check failed. Run 'npm run format' to fix."
        exit 1
    fi
    
    echo "All pre-commit checks passed!"
    exit 0
    ```

13. **Make pre-commit hook executable:**
    ```bash
    chmod +x .git/hooks/pre-commit
    ```

14. **Create development documentation `docs/DEVELOPMENT.md`:**
    ```markdown
    # Development Guide
    
    ## Prerequisites
    - PHP 8.2+
    - Node.js 18+
    - MySQL 8.0+
    - Composer
    - npm
    
    ## Setup
    1. Clone repository
    2. Install backend: `cd backend && composer install`
    3. Install frontend: `cd frontend && npm install`
    4. Configure `.env` files
    5. Run migrations: `php artisan migrate`
    6. Seed database: `php artisan db:seed`
    
    ## Development
    - Backend: `php artisan serve`
    - Frontend: `npm run dev`
    
    ## Code Quality
    - Format PHP: `composer pint`
    - Analyze PHP: `composer analyse`
    - Format JS/TS: `npm run format`
    - Lint JS/TS: `npm run lint`
    - Type check: `npm run type-check`
    
    ## Testing
    - Backend: `php artisan test`
    - Frontend: `npm run test`
    ```

15. **Create README.md in project root:**
    ```markdown
    # Tarqumi CRM
    
    Internal CRM system with bilingual landing page.
    
    ## Tech Stack
    - Backend: Laravel 11
    - Frontend: Next.js 14
    - Database: MySQL 8
    - Authentication: Laravel Sanctum
    
    ## Quick Start
    See [Development Guide](docs/DEVELOPMENT.md)
    
    ## Project Structure
    - `backend/` - Laravel API
    - `frontend/` - Next.js application
    - `docs/` - Documentation
    - `tasks/` - Task breakdown
    
    ## License
    Proprietary - Tarqumi
    ```

16. **Create contributing guidelines `CONTRIBUTING.md`:**
    ```markdown
    # Contributing Guidelines
    
    ## Code Style
    - Follow Laravel conventions for PHP
    - Follow Airbnb style guide for JavaScript/TypeScript
    - Use Prettier for formatting
    - Use ESLint for linting
    
    ## Commit Messages
    - Use semantic commit format
    - Examples: `feat:`, `fix:`, `refactor:`, `test:`, `docs:`
    
    ## Pull Requests
    - Create feature branch from `main`
    - Write descriptive PR title
    - Include tests for new features
    - Ensure all checks pass
    
    ## Testing
    - Write tests for all new features
    - Maintain test coverage above 80%
    - Run full test suite before PR
    ```

17. **Test all development tools:**
    ```bash
    # Backend
    cd backend
    composer pint
    composer analyse
    php artisan test
    
    # Frontend
    cd frontend
    npm run lint
    npm run format
    npm run type-check
    npm run build
    ```

**Acceptance Criteria:**
- [ ] Laravel Pint installed and configured
- [ ] PHPStan installed and configured
- [ ] ESLint configured with Prettier
- [ ] Prettier configured
- [ ] VS Code settings created
- [ ] EditorConfig created
- [ ] Git hooks created and executable
- [ ] Development documentation created
- [ ] README created
- [ ] Contributing guidelines created
- [ ] All linters run without errors
- [ ] All formatters run successfully
- [ ] TypeScript compiles without errors

**Testing:**
```bash
# Test backend tools
cd backend
composer pint-test
composer analyse

# Test frontend tools
cd frontend
npm run lint
npm run format:check
npm run type-check

# Test git hooks
git add .
git commit -m "test: verify pre-commit hook"
```

**Files Created:**
- `backend/pint.json`
- `backend/phpstan.neon`
- `frontend/.eslintrc.json`
- `.vscode/settings.json`
- `.vscode/extensions.json`
- `.editorconfig`
- `.git/hooks/pre-commit`
- `docs/DEVELOPMENT.md`
- `README.md`
- `CONTRIBUTING.md`

**Security Considerations:**
- Git hooks prevent committing bad code
- Linters catch potential security issues
- Code analysis detects vulnerabilities

**Performance Considerations:**
- Pre-commit hooks run quickly
- Linters configured for speed
- Type checking catches errors early

**Notes:**
- Run formatters before committing
- Fix linter errors immediately
- Keep documentation updated
- Follow commit message conventions

