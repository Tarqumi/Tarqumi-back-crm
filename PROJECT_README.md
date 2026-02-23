# Tarqumi CRM - Backend

This is the backend API for Tarqumi CRM, built with Laravel 11 and PHP.

## Project Structure

This folder contains:
- Backend API code (Laravel)
- Shared documentation (docs, archive, tasks, user-stories)
- Shared configuration (.kiro)

## Getting Started

1. Install dependencies:
```bash
composer install
```

2. Set up environment:
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

5. Start the development server:
```bash
php artisan serve
```

The API will be available at [http://localhost:8000](http://localhost:8000)

## Features

- RESTful API
- Authentication with Laravel Sanctum
- Role-based access control (Admin, Manager, Employee)
- Client management endpoints
- Project management endpoints
- Team management endpoints
- Permission system

## Tech Stack

- Laravel 11
- PHP 8.2+
- MySQL/PostgreSQL
- Laravel Sanctum for authentication
- RESTful API architecture

## API Documentation

See `docs/` folder for detailed API documentation.

## Related

Frontend application: See `../Tarqumi-front/`
