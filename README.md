# Tarqumi CRM - Backend API

Laravel-based REST API for Tarqumi CRM system with bilingual support (Arabic/English).

## 🚀 Quick Start

### Prerequisites
- PHP 8.2+
- MySQL 8.0+
- Composer
- Node.js & npm (for frontend integration)

### Installation

```bash
# Clone repository
git clone <repository-url>
cd Tarqumi-back

# Install dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database in .env
DB_DATABASE=tarqumi_crm
DB_USERNAME=root
DB_PASSWORD=

# Run migrations
php artisan migrate

# Start server
php artisan serve

# Start queue worker (separate terminal)
php artisan queue:work --tries=3 --timeout=60
```

## 📚 Documentation

- **[BACKEND-TASKS-COMPLETE.md](BACKEND-TASKS-COMPLETE.md)** - Complete implementation details
- **[TESTING_GUIDE.md](TESTING_GUIDE.md)** - Testing procedures
- **[docs/](docs/)** - Business requirements and coding standards
- **[user-stories/](user-stories/)** - User stories and requirements
- **[tasks/](tasks/)** - Task breakdown and specifications

## 🔑 Key Features

- ✅ RESTful API with Laravel 11
- ✅ Bilingual support (Arabic/English)
- ✅ Laravel Sanctum authentication
- ✅ Email queue system
- ✅ Password reset functionality
- ✅ Auto-inactive user management
- ✅ Dynamic sitemap generation
- ✅ Role-based access control
- ✅ Rate limiting on sensitive endpoints

## 🛠️ Tech Stack

- **Framework**: Laravel 11
- **Database**: MySQL 8.0
- **Authentication**: Laravel Sanctum
- **Queue**: Database driver
- **Storage**: Local filesystem
- **Email**: SMTP

## 📡 API Endpoints

### Authentication
```
POST   /api/v1/auth/login
POST   /api/v1/auth/logout
POST   /api/v1/auth/refresh
```

### Password Reset
```
POST   /api/v1/password/forgot
POST   /api/v1/password/reset
```

### Team Management
```
GET    /api/v1/cms/team
POST   /api/v1/cms/team
PATCH  /api/v1/cms/team/{id}
DELETE /api/v1/cms/team/{id}
```

### Client Management
```
GET    /api/v1/cms/clients
POST   /api/v1/cms/clients
PATCH  /api/v1/cms/clients/{id}
DELETE /api/v1/cms/clients/{id}
```

### Project Management
```
GET    /api/v1/cms/projects
POST   /api/v1/cms/projects
PATCH  /api/v1/cms/projects/{id}
DELETE /api/v1/cms/projects/{id}
```

### Blog System
```
GET    /api/v1/blog/posts
GET    /api/v1/blog/posts/{slug}
POST   /api/v1/cms/blog/posts
PATCH  /api/v1/cms/blog/posts/{id}
DELETE /api/v1/cms/blog/posts/{id}
```

### Contact Form
```
POST   /api/v1/contact
GET    /api/v1/cms/contact
```

### Public Endpoints
```
GET    /sitemap.xml
GET    /robots.txt
GET    /api/v1/landing/showcase-projects
GET    /api/v1/landing/company-info
```

## 🔧 Configuration

### Environment Variables

```env
# Application
APP_NAME="Tarqumi CRM"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tarqumi_crm
DB_USERNAME=root
DB_PASSWORD=

# Queue
QUEUE_CONNECTION=database

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@tarqumi.com
MAIL_FROM_NAME="${APP_NAME}"

# Frontend
FRONTEND_URL=http://localhost:3000

# Revalidation
NEXTJS_REVALIDATE_URL=http://localhost:3000/api/revalidate
NEXTJS_REVALIDATION_SECRET=your-secret-key-here
```

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Check code quality
./vendor/bin/phpstan analyse
```

## 📦 Scheduled Tasks

The following tasks run automatically via Laravel scheduler:

- **Daily**: Check for inactive users (30+ days)

To enable scheduling in production:
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

## 🔐 Security Features

- SQL injection prevention (Eloquent ORM)
- XSS protection (input sanitization)
- CSRF protection (Sanctum)
- Rate limiting on sensitive endpoints
- Password hashing (bcrypt)
- Secure password reset tokens
- Input validation on all endpoints

## 🌍 Internationalization

The API supports bilingual responses (Arabic/English) based on the `Accept-Language` header:

```bash
# English response
curl -H "Accept-Language: en" http://localhost:8000/api/v1/...

# Arabic response
curl -H "Accept-Language: ar" http://localhost:8000/api/v1/...
```

## 🚀 Production Deployment

### 1. Configure Environment
```bash
APP_ENV=production
APP_DEBUG=false
```

### 2. Optimize Application
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Setup Queue Worker (Supervisor)
```ini
[program:tarqumi-queue]
command=php /path/to/artisan queue:work --tries=3 --timeout=60
autostart=true
autorestart=true
user=www-data
```

### 4. Setup Scheduler (Cron)
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

## 📝 Useful Commands

```bash
# Clear all caches
php artisan optimize:clear

# Check routes
php artisan route:list

# Check inactive users manually
php artisan users:check-inactive

# Process queue jobs
php artisan queue:work

# View failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all
```

## 🤝 Contributing

1. Follow PSR-12 coding standards
2. Write tests for new features
3. Update documentation
4. Follow SOLID principles
5. Use meaningful commit messages

## 📄 License

Proprietary - Tarqumi Company

## 📞 Support

For issues or questions, contact the development team.
