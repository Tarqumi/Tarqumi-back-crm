# Day 2 API Testing Guide

## Quick Start

### 1. Setup
```bash
cd backend
php artisan migrate
php artisan db:seed --class=AdminSeeder
php artisan db:seed --class=DefaultClientSeeder
php artisan serve
```

### 2. Login to Get Token
```bash
POST http://localhost:8000/api/v1/login
Content-Type: application/json

{
  "email": "admin@tarqumi.com",
  "password": "password"
}
```

Response:
```json
{
  "success": true,
  "data": {
    "user": { ... },
    "token": "1|xxxxxxxxxxxxx"
  }
}
```

Use this token in all subsequent requests:
```
Authorization: Bearer 1|xxxxxxxxxxxxx
```

---

## Team Management Tests

### Create Employee
```bash
POST http://localhost:8000/api/v1/team
Authorization: Bearer {token}

{
  "name": "Alice Johnson",
  "email": "alice@tarqumi.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "employee",
  "department": "Engineering",
  "job_title": "Software Developer"
}
```

### Create Founder (CTO)
```bash
POST http://localhost:8000/api/v1/team

{
  "name": "Bob Smith",
  "email": "bob@tarqumi.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "founder",
  "founder_role": "cto",
  "department": "Technology"
}
```

### List Team Members
```bash
GET http://localhost:8000/api/v1/team
GET http://localhost:8000/api/v1/team?search=alice
GET http://localhost:8000/api/v1/team?role=employee
GET http://localhost:8000/api/v1/team?status=active
GET http://localhost:8000/api/v1/team?department=Engineering
GET http://localhost:8000/api/v1/team?per_page=10&page=1
GET http://localhost:8000/api/v1/team?sort_by=name&sort_order=asc
```

### Get Single Team Member
```bash
GET http://localhost:8000/api/v1/team/{user_id}
```

### Update Team Member
```bash
PUT http://localhost:8000/api/v1/team/{user_id}

{
  "department": "Sales",
  "job_title": "Senior Developer"
}
```

### Delete Team Member (No Projects)
```bash
DELETE http://localhost:8000/api/v1/team/{user_id}
```

### Reassign Projects Then Delete
```bash
# First reassign projects
POST http://localhost:8000/api/v1/team/{old_manager_id}/reassign/{new_manager_id}

# Then delete
DELETE http://localhost:8000/api/v1/team/{old_manager_id}
```

### Get Departments List
```bash
GET http://localhost:8000/api/v1/team/departments
```

---

## Client Management Tests

### Create Client
```bash
POST http://localhost:8000/api/v1/clients

{
  "name": "Tech Solutions Inc",
  "email": "contact@techsolutions.com",
  "company_name": "Tech Solutions Incorporated",
  "phone": "+1234567890",
  "whatsapp": "+1234567890",
  "website": "techsolutions.com",
  "industry": "Technology",
  "address": "123 Tech Street, Silicon Valley, CA",
  "notes": "Important client for Q1 2026"
}
```

### List Clients
```bash
GET http://localhost:8000/api/v1/clients
GET http://localhost:8000/api/v1/clients?search=tech
GET http://localhost:8000/api/v1/clients?status=active
GET http://localhost:8000/api/v1/clients?industry=Technology
GET http://localhost:8000/api/v1/clients?has_projects=true
GET http://localhost:8000/api/v1/clients?per_page=20&page=1
GET http://localhost:8000/api/v1/clients?sort_by=name&sort_order=asc
```

### Get Single Client
```bash
GET http://localhost:8000/api/v1/clients/{client_id}
```

### Update Client
```bash
PUT http://localhost:8000/api/v1/clients/{client_id}

{
  "phone": "+9876543210",
  "notes": "Updated contact information",
  "is_active": true
}
```

### Try to Update Default Client (Should Fail)
```bash
PUT http://localhost:8000/api/v1/clients/1

{
  "name": "New Name",
  "email": "newemail@example.com"
}
# Should return error: "Default client name cannot be changed"
```

### Delete Client
```bash
DELETE http://localhost:8000/api/v1/clients/{client_id}
```

### Try to Delete Default Client (Should Fail)
```bash
DELETE http://localhost:8000/api/v1/clients/1
# Should return error: "Default client cannot be deleted"
```

### Restore Deleted Client
```bash
POST http://localhost:8000/api/v1/clients/{client_id}/restore
```

---

## Project Management Tests

### Create Project with Single Client
```bash
POST http://localhost:8000/api/v1/projects

{
  "name": "Website Redesign",
  "description": "Complete redesign of company website with modern UI/UX",
  "clients": [2],
  "manager_id": 3,
  "budget": 50000,
  "currency": "USD",
  "priority": 8,
  "start_date": "2026-03-01",
  "end_date": "2026-06-30",
  "estimated_hours": 500
}
```

### Create Project with Multiple Clients
```bash
POST http://localhost:8000/api/v1/projects

{
  "name": "Mobile App Development",
  "description": "iOS and Android app for client portal",
  "clients": [2, 3, 4],
  "manager_id": 3,
  "budget": 100000,
  "currency": "USD",
  "priority": 9,
  "start_date": "2026-04-01",
  "end_date": "2026-09-30",
  "estimated_hours": 1000
}
```

### Create Project Without Clients (Defaults to Tarqumi)
```bash
POST http://localhost:8000/api/v1/projects

{
  "name": "Internal Tool",
  "description": "Internal project management tool",
  "manager_id": 3,
  "priority": 5,
  "start_date": "2026-03-15"
}
```

### List Projects
```bash
GET http://localhost:8000/api/v1/projects
GET http://localhost:8000/api/v1/projects?search=website
GET http://localhost:8000/api/v1/projects?status=planning
GET http://localhost:8000/api/v1/projects?priority_min=7
GET http://localhost:8000/api/v1/projects?manager_id=3
GET http://localhost:8000/api/v1/projects?client_id=2
GET http://localhost:8000/api/v1/projects?is_active=true
GET http://localhost:8000/api/v1/projects?per_page=25&page=1
GET http://localhost:8000/api/v1/projects?sort_by=start_date&sort_order=desc
```

### Get Kanban Board Data
```bash
GET http://localhost:8000/api/v1/projects/kanban
```

### Get Single Project
```bash
GET http://localhost:8000/api/v1/projects/{project_id}
```

### Update Project
```bash
PUT http://localhost:8000/api/v1/projects/{project_id}

{
  "status": "implementation",
  "priority": 10,
  "budget": 60000
}
```

### Update Project Clients
```bash
PUT http://localhost:8000/api/v1/projects/{project_id}

{
  "clients": [2, 3, 4, 5]
}
```

### Change Project Manager
```bash
PUT http://localhost:8000/api/v1/projects/{project_id}

{
  "manager_id": 5
}
```

### Delete Project
```bash
DELETE http://localhost:8000/api/v1/projects/{project_id}
```

### Restore Deleted Project
```bash
POST http://localhost:8000/api/v1/projects/{project_id}/restore
```

---

## Validation Tests

### Test Email Uniqueness (Team)
```bash
POST http://localhost:8000/api/v1/team

{
  "name": "Test User",
  "email": "alice@tarqumi.com",  # Already exists
  "password": "password123",
  "password_confirmation": "password123",
  "role": "employee"
}
# Should return: "This email is already registered"
```

### Test Password Confirmation
```bash
POST http://localhost:8000/api/v1/team

{
  "name": "Test User",
  "email": "test@tarqumi.com",
  "password": "password123",
  "password_confirmation": "wrongpassword",
  "role": "employee"
}
# Should return: "Password confirmation does not match"
```

### Test Priority Range
```bash
POST http://localhost:8000/api/v1/projects

{
  "name": "Test Project",
  "clients": [2],
  "manager_id": 3,
  "priority": 15,  # Invalid (must be 1-10)
  "start_date": "2026-03-01"
}
# Should return: "Priority must be between 1 and 10"
```

### Test End Date Before Start Date
```bash
POST http://localhost:8000/api/v1/projects

{
  "name": "Test Project",
  "clients": [2],
  "manager_id": 3,
  "priority": 5,
  "start_date": "2026-06-01",
  "end_date": "2026-03-01"  # Before start date
}
# Should return: "End date must be after start date"
```

### Test Max Clients
```bash
POST http://localhost:8000/api/v1/projects

{
  "name": "Test Project",
  "clients": [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],  # 11 clients (max is 10)
  "manager_id": 3,
  "priority": 5,
  "start_date": "2026-03-01"
}
# Should return: "Maximum 10 clients allowed per project"
```

---

## Authorization Tests

### Try to Create Team Member as Employee (Should Fail)
```bash
# Login as employee first
POST http://localhost:8000/api/v1/login
{
  "email": "alice@tarqumi.com",
  "password": "password123"
}

# Try to create team member
POST http://localhost:8000/api/v1/team
Authorization: Bearer {employee_token}

{
  "name": "Test User",
  "email": "test@tarqumi.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "employee"
}
# Should return 403 Forbidden
```

### Try to Delete Super Admin as Admin (Should Fail)
```bash
DELETE http://localhost:8000/api/v1/team/1
Authorization: Bearer {admin_token}
# Should return 403 Forbidden
```

---

## Edge Cases

### Create Team Member with Arabic Name
```bash
POST http://localhost:8000/api/v1/team

{
  "name": "محمد أحمد",
  "email": "mohammed@tarqumi.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "employee"
}
```

### Create Client with Website Without Protocol
```bash
POST http://localhost:8000/api/v1/clients

{
  "name": "Test Client",
  "email": "test@client.com",
  "website": "testclient.com"  # Will auto-prefix with https://
}
```

### Create Project with No End Date
```bash
POST http://localhost:8000/api/v1/projects

{
  "name": "Ongoing Project",
  "clients": [2],
  "manager_id": 3,
  "priority": 5,
  "start_date": "2026-03-01"
  # No end_date - ongoing project
}
```

---

## Expected Responses

### Success (201 Created)
```json
{
  "success": true,
  "data": { ... },
  "message": "Resource created successfully"
}
```

### Success (200 OK)
```json
{
  "success": true,
  "data": { ... }
}
```

### Validation Error (422)
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["This email is already registered"],
    "priority": ["Priority must be between 1 and 10"]
  }
}
```

### Authorization Error (403)
```json
{
  "success": false,
  "message": "Unauthorized"
}
```

### Not Found (404)
```json
{
  "success": false,
  "message": "Resource not found"
}
```

---

## Postman Collection

Import this collection to Postman for easy testing:

1. Create new collection "Tarqumi CRM - Day 2"
2. Add environment variable `base_url` = `http://localhost:8000/api/v1`
3. Add environment variable `token` = (get from login response)
4. Add all endpoints above with Authorization header: `Bearer {{token}}`

---

## Common Issues

### Issue: 401 Unauthorized
**Solution**: Make sure you're including the Bearer token in Authorization header

### Issue: 403 Forbidden
**Solution**: Check that your user has the correct role (super_admin, admin, or hr)

### Issue: 422 Validation Error
**Solution**: Check the error response for specific field validation errors

### Issue: 500 Server Error
**Solution**: Check Laravel logs at `storage/logs/laravel.log`

---

## Database Inspection

Check data directly in database:
```bash
php artisan tinker

# List all users
User::all();

# List all clients
Client::all();

# List all projects with clients
Project::with('clients')->get();

# Find default client
Client::where('is_default', true)->first();

# Count projects per manager
Project::selectRaw('manager_id, count(*) as count')->groupBy('manager_id')->get();
```

---

Happy Testing! 🚀
