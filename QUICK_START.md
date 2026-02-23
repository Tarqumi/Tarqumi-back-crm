# Tarqumi CRM - Quick Start Guide

## 🚀 Get Started in 3 Minutes

### Step 1: Start Backend (Terminal 1)
```bash
cd backend
php artisan serve
```
Backend will run at: http://localhost:8000

### Step 2: Start Frontend (Terminal 2)
```bash
cd frontend
npm run dev
```
Frontend will run at: http://localhost:3000

### Step 3: Login
1. Open: http://localhost:3000/en/login
2. Use credentials:
   - Email: `admin@tarqumi.com`
   - Password: `password`
3. Click "Sign In"

You're in! 🎉

---

## 📍 Important URLs

| Page | URL |
|------|-----|
| Home (English) | http://localhost:3000/en |
| Home (Arabic) | http://localhost:3000/ar |
| Login (English) | http://localhost:3000/en/login |
| Login (Arabic) | http://localhost:3000/ar/login |
| Admin Dashboard | http://localhost:3000/en/admin |
| Backend API | http://localhost:8000/api/v1 |

---

## 🧪 Quick Tests

### Test 1: Login Flow
1. Go to login page
2. Try empty form → See validation errors ✅
3. Try wrong password → See error message ✅
4. Use correct credentials → Redirect to admin ✅

### Test 2: Language Switching
1. Click language switcher (top right)
2. Switch to Arabic → See RTL layout ✅
3. Switch back to English → See LTR layout ✅

### Test 3: Protected Routes
1. Logout from admin
2. Try to access http://localhost:3000/en/admin
3. Should redirect to login ✅

### Test 4: Remember Me
1. Login with "Remember me" checked
2. Close browser
3. Open again → Still logged in ✅

---

## 🔑 Test Credentials

From backend seeder:
```
Email: admin@tarqumi.com
Password: password
Role: Super Admin
```

---

## 🛠️ Troubleshooting

### Frontend won't start?
```bash
cd frontend
npm install
npm run dev
```

### Backend won't start?
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

### Can't login?
1. Check backend is running: http://localhost:8000
2. Check database is migrated
3. Check admin user exists: `php artisan tinker` → `User::first()`
4. Check `.env.local` has correct API URL

### CORS errors?
Check `backend/config/cors.php`:
```php
'allowed_origins' => ['http://localhost:3000'],
'supports_credentials' => true,
```

---

## 📦 What's Included

✅ Complete authentication system
✅ Bilingual support (EN/AR)
✅ RTL layout for Arabic
✅ Protected admin routes
✅ Modern UI components
✅ Form validation
✅ Error handling
✅ Loading states
✅ Responsive design

---

## 🎯 Next: Build Features

Day 1 is complete! Now you can build:
- Team Management
- Client Management
- Project Management
- Landing Page CMS
- Blog System

See `FRONTEND_DAY_1_COMPLETE.md` for full documentation.

---

**Happy Coding! 🚀**
