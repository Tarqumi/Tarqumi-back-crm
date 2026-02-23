# Getting Started - Tarqumi CRM Backend

## Installation

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

6. API available at http://localhost:8000

## Default Admin Credentials

After running seeders:
- Email: admin@tarqumi.com
- Password: password

## API Endpoints

### Authentication
- POST `/api/v1/login` - User login
- POST `/api/v1/logout` - User logout
- GET `/api/v1/user` - Get authenticated user

### Clients
- GET `/api/v1/clients` - List clients
- POST `/api/v1/clients` - Create client
- GET `/api/v1/clients/{id}` - Get client
- PUT `/api/v1/clients/{id}` - Update client
- DELETE `/api/v1/clients/{id}` - Delete client

### Projects
- GET `/api/v1/projects` - List projects
- POST `/api/v1/projects` - Create project
- GET `/api/v1/projects/{id}` - Get project
- PUT `/api/v1/projects/{id}` - Update project
- DELETE `/api/v1/projects/{id}` - Delete project

### Team
- GET `/api/v1/team` - List team members
- POST `/api/v1/team` - Add team member
- PUT `/api/v1/team/{id}` - Update team member
- DELETE `/api/v1/team/{id}` - Remove team member

## Roles & Permissions

- **Admin**: Full access to all features
- **Manager**: Manage clients, projects, and view team
- **Employee**: View clients and projects only

## Project Structure

```
app/
├── Enums/           # Enumerations
├── Http/            # Controllers, Middleware, Requests, Resources
├── Models/          # Eloquent models
├── Policies/        # Authorization policies
├── Services/        # Business logic
└── Traits/          # Reusable traits
```

## Available Commands

- `php artisan serve` - Start development server
- `php artisan migrate` - Run migrations
- `php artisan db:seed` - Run seeders
- `php artisan test` - Run tests

## Documentation

- API documentation: `docs/`
- User stories: `user-stories/`
- Tasks: `tasks/`
- Archive: `archive/`
