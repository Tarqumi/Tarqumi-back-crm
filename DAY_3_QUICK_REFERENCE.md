# Day 3 - Quick Reference Guide

## Authentication Endpoints

### Login
```bash
POST /api/v1/auth/login
Content-Type: application/json

{
  "email": "admin@tarqumi.com",
  "password": "password"
}

Response:
{
  "success": true,
  "data": {
    "user": { ... },
    "token": "1|..."
  },
  "message": "Login successful"
}
```

### Logout
```bash
POST /api/v1/auth/logout
Authorization: Bearer {token}

Response:
{
  "success": true,
  "message": "Logged out successfully"
}
```

### Get Current User
```bash
GET /api/v1/auth/user
Authorization: Bearer {token}

Response:
{
  "success": true,
  "data": { ... }
}
```

### Refresh Token
```bash
POST /api/v1/auth/refresh
Authorization: Bearer {token}

Response:
{
  "success": true,
  "data": {
    "token": "2|..."
  },
  "message": "Token refreshed successfully"
}
```

### Get Permissions
```bash
GET /api/v1/permissions
Authorization: Bearer {token}

Response:
{
  "success": true,
  "data": {
    "role": "admin",
    "founder_role": null,
    "permissions": {
      "can_manage_team": true,
      "can_delete_admin": false,
      ...
    }
  }
}
```

## Team Management Endpoints

### List Team Members
```bash
GET /api/v1/team?page=1&per_page=20&search=john&role=employee&status=active&sort_by=name&sort_order=asc
Authorization: Bearer {token}

Response:
{
  "success": true,
  "data": [ ... ],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 20,
    "total": 100,
    "from": 1,
    "to": 20
  }
}
```

### Create Team Member
```bash
POST /api/v1/team
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "employee",
  "department": "Engineering",
  "job_title": "Developer",
  "phone": "+1234567890",
  "whatsapp": "+1234567890",
  "is_active": true
}

Response:
{
  "success": true,
  "data": { ... },
  "message": "Team member created successfully"
}
```

### Update Team Member
```bash
PUT /api/v1/team/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "John Updated",
  "department": "Sales"
}

Response:
{
  "success": true,
  "data": { ... },
  "message": "Team member updated successfully"
}
```

### Delete Team Member
```bash
# Without projects
DELETE /api/v1/team/{id}
Authorization: Bearer {token}

# With project reassignment
DELETE /api/v1/team/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "new_manager_id": 5
}

Response:
{
  "success": true,
  "message": "Team member deleted successfully",
  "reassigned_projects": 3
}
```

### Get Departments
```bash
GET /api/v1/team/departments
Authorization: Bearer {token}

Response:
{
  "success": true,
  "data": ["Engineering", "Sales", "Marketing"]
}
```

### Show Team Member
```bash
GET /api/v1/team/{id}
Authorization: Bearer {token}

Response:
{
  "success": true,
  "data": { ... }
}
```

### Reassign Projects
```bash
POST /api/v1/team/{oldManagerId}/reassign/{newManagerId}
Authorization: Bearer {token}

Response:
{
  "success": true,
  "message": "3 projects reassigned successfully",
  "reassigned_count": 3
}
```

## Role Permissions Matrix

| Permission | Super Admin | Admin | CTO | CEO/CFO | HR | Employee |
|------------|-------------|-------|-----|---------|----|---------| 
| Manage Team | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ |
| Create Team Member | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ |
| Edit Team Member | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ |
| Delete Team Member | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| Delete Admin | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Manage Clients | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| View Clients | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Manage Projects | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| View Projects | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Edit Landing Page | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| Manage Blog | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| View Contact Submissions | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |

## Available Roles

### User Roles
- `super_admin` - Full system access
- `admin` - Full access except deleting Super Admins
- `founder` - View-only CRM access (requires founder_role)
- `hr` - Team management (create, edit, no delete)
- `employee` - Minimal access

### Founder Sub-Roles (when role = founder)
- `cto` - Can edit landing page + view CRM
- `ceo` - View CRM only
- `cfo` - View CRM only

## Common Use Cases

### 1. Admin Login Flow
```bash
# 1. Login
POST /api/v1/auth/login
{ "email": "admin@tarqumi.com", "password": "password" }

# 2. Save token from response

# 3. Get permissions
GET /api/v1/permissions
Authorization: Bearer {token}

# 4. Use token for all subsequent requests
```

### 2. Create Employee
```bash
POST /api/v1/team
Authorization: Bearer {admin_token}
{
  "name": "New Employee",
  "email": "employee@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "employee",
  "department": "Engineering"
}
```

### 3. Search Team Members
```bash
GET /api/v1/team?search=john&department=Engineering&status=active
Authorization: Bearer {token}
```

### 4. Delete PM with Project Reassignment
```bash
# 1. Check if user has projects
GET /api/v1/team/{pmId}

# 2. If has projects, provide new manager
DELETE /api/v1/team/{pmId}
{ "new_manager_id": 5 }
```

## Error Responses

### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

### 403 Forbidden
```json
{
  "success": false,
  "message": "Unauthorized. Insufficient permissions."
}
```

### 422 Validation Error
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["Email is required", "Email must be valid"]
  }
}
```

### 429 Too Many Requests
```json
{
  "message": "Too Many Attempts."
}
```

## Testing Commands

```bash
# Run all Day 3 tests
php artisan test --filter="AuthenticationTest|PermissionsTest|TeamManagementTest"

# Run specific test file
php artisan test --filter AuthenticationTest
php artisan test --filter PermissionsTest
php artisan test --filter TeamManagementTest

# Run with coverage
php artisan test --coverage

# Run specific test method
php artisan test --filter test_admin_can_create_team_member
```

## Important Notes

1. **Rate Limiting**: Login endpoint limited to 10 attempts per minute
2. **Email Normalization**: All emails converted to lowercase before storage
3. **Soft Delete**: Team members are soft deleted, data preserved
4. **Last Active**: Updated on every authenticated request
5. **Profile Pictures**: Stored in `storage/app/public/profile-pictures/`
6. **Token Expiration**: Tokens don't expire by default (use refresh for long sessions)
7. **HR Limitations**: HR can create/edit but NOT delete team members
8. **Super Admin Protection**: Cannot delete last Super Admin
9. **Admin Protection**: Only Super Admin can delete other Admins
10. **Project Reassignment**: Required before deleting a Project Manager
