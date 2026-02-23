# Tarqumi CRM - Backend API

A robust CRM backend API built with Laravel 11 and PHP.

## Features

- 🔐 Authentication with Laravel Sanctum
- 👥 User management with role-based access control
- 📋 Client management
- 📊 Project management
- 🤝 Team management
- 🔒 Permission system
- 📝 RESTful API architecture

## Tech Stack

- **Framework**: Laravel 11
- **Language**: PHP 8.2+
- **Database**: MySQL/PostgreSQL
- **Authentication**: Laravel Sanctum
- **API**: RESTful

## Getting Started

### Prerequisites

- PHP 8.2 or higher
- Composer
- MySQL or PostgreSQL
- Node.js (for asset compilation)

### Installation

1. Install dependencies:
```bash
composer install
```

2. Configure environment:
```bash
cp .env.example .env
# Edit .env with your database credentials
```

3. Generate application key:
```bash
php artisan key:generate
```

4. Run migrations and seeders:
```bash
php artisan migrate --seed
```

5. Start development server:
```bash
php artisan serve
```

6. API available at [http://localhost:8000](http://localhost:8000)

## Default Admin Credentials

After running seeders:
- **Email**: admin@tarqumi.com
- **Password**: password

## API Endpoints

### Authentication
- `POST /api/v1/login` - User login
- `POST /api/v1/logout` - User logout
- `GET /api/v1/user` - Get authenticated user

### Clients
- `GET /api/v1/clients` - List clients
- `POST /api/v1/clients` - Create client
- `GET /api/v1/clients/{id}` - Get client details
- `PUT /api/v1/clients/{id}` - Update client
- `DELETE /api/v1/clients/{id}` - Delete client

### Projects
- `GET /api/v1/projects` - List projects
- `POST /api/v1/projects` - Create project
- `GET /api/v1/projects/{id}` - Get project details
- `PUT /api/v1/projects/{id}` - Update project
- `DELETE /api/v1/projects/{id}` - Delete project

### Team
- `GET /api/v1/team` - List team members
- `POST /api/v1/team` - Add team member
- `PUT /api/v1/team/{id}` - Update team member
- `DELETE /api/v1/team/{id}` - Remove team member

## Roles & Permissions

- **Admin**: Full access to all features
- **Manager**: Manage clients, projects, and view team
- **Employee**: View clients and projects only

## Project Structure

```
app/
├── Enums/           # Enumerations (Roles, Status, etc.)
├── Http/
│   ├── Controllers/ # API Controllers
│   ├── Middleware/  # Custom middleware
│   ├── Requests/    # Form request validation
│   └── Resources/   # API resources
├── Models/          # Eloquent models
├── Policies/        # Authorization policies
├── Services/        # Business logic layer
└── Traits/          # Reusable traits

database/
├── factories/       # Model factories
├── migrations/      # Database migrations
└── seeders/         # Database seeders
```

## Available Commands

- `php artisan serve` - Start development server
- `php artisan migrate` - Run migrations
- `php artisan db:seed` - Run seeders
- `php artisan migrate:fresh --seed` - Fresh migration with seeders
- `php artisan test` - Run tests

## Frontend Repository

This backend serves the Tarqumi CRM Frontend application.
Frontend repository: `../Tarqumi-front/`

## Documentation

- Getting Started: `GETTING_STARTED.md`
- Project Details: `PROJECT_README.md`
- Testing Guide: `TESTING_GUIDE.md`
- API Documentation: `docs/`
- User Stories: `user-stories/`
- Tasks: `tasks/`

## License

Private project - All rights reserved
