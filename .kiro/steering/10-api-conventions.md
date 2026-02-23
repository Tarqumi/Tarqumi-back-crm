---
inclusion: always
priority: 11
---

# API Conventions & Standards

## RESTful API Structure
All APIs versioned: `/api/v1/...`

### Standard Endpoints
```
GET    /api/v1/projects          # List all (paginated)
GET    /api/v1/projects/{id}     # Show single
POST   /api/v1/projects          # Create
PUT    /api/v1/projects/{id}     # Update
DELETE /api/v1/projects/{id}     # Delete
```

## Response Format (ALWAYS)
### Success Response
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Project Name"
  },
  "message": "Operation successful"
}
```

### Error Response
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["Email is required", "Email must be valid"]
  }
}
```

## HTTP Status Codes
- `200` OK — Successful GET, PUT
- `201` Created — Successful POST
- `204` No Content — Successful DELETE
- `400` Bad Request — Malformed request
- `401` Unauthorized — Not authenticated
- `403` Forbidden — Not authorized
- `404` Not Found — Resource doesn't exist
- `422` Unprocessable Entity — Validation failed
- `429` Too Many Requests — Rate limited
- `500` Internal Server Error — Server error

## Pagination
All list endpoints MUST paginate:
```json
{
  "success": true,
  "data": [...],
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 100,
    "last_page": 7
  },
  "links": {
    "first": "/api/v1/projects?page=1",
    "last": "/api/v1/projects?page=7",
    "prev": null,
    "next": "/api/v1/projects?page=2"
  }
}
```

Default: 15 items per page

## Filtering & Sorting
```
GET /api/v1/projects?status=active&priority_min=5&sort=name&order=asc
GET /api/v1/clients?search=john&is_active=1
```

## Authentication
- Use Laravel Sanctum tokens
- Include in header: `Authorization: Bearer {token}`
- Token returned on login
- Token invalidated on logout

## Rate Limiting
- Contact form: 5 requests/minute
- Login: 10 attempts/minute
- General API: 60 requests/minute
- Return 429 with `Retry-After` header

## Error Handling
Never expose:
- Stack traces in production
- Database errors
- Internal paths
- Sensitive data

Always return user-friendly messages:
- ❌ "SQLSTATE[23000]: Integrity constraint violation"
- ✅ "This email is already registered"

## API Resources (Laravel)
Use API Resources for all responses:
```php
class ProjectResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'manager' => new UserResource($this->manager),
            'clients' => ClientResource::collection($this->clients),
            'created_at' => $this->created_at->toISOString(),
        ];
    }
}
```

## Validation
- Use Form Request classes
- Validate on backend (frontend is UX only)
- Return 422 with field-level errors
- Sanitize all inputs

## CORS
Configure for frontend domain:
```php
'paths' => ['api/*'],
'allowed_origins' => [env('FRONTEND_URL')],
'allowed_methods' => ['*'],
'allowed_headers' => ['*'],
'supports_credentials' => true,
```

## API Documentation
Document all endpoints with:
- Method & URL
- Request body example
- Response example
- Status codes
- Required permissions
