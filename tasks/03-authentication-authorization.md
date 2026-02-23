# Module 3: Authentication & Authorization

## Overview
Complete authentication system using Laravel Sanctum for API authentication, including login, logout, password reset, role-based access control, and session management.

---

## Task 3.1: Implement Laravel Sanctum Authentication

**Priority:** 🔴 Critical  
**Estimated Time:** 4 hours  
**Dependencies:** Task 2.1  
**Assigned To:** Backend Developer

**Objective:**
Set up Laravel Sanctum for API token authentication with proper configuration for SPA authentication and CORS.

**Detailed Steps:**

1. **Verify Sanctum installation:**
   ```bash
   composer show laravel/sanctum
   ```

2. **Publish Sanctum migrations if not already done:**
   ```bash
   php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
   ```

3. **Run Sanctum migrations:**
   ```bash
   php artisan migrate
   ```

4. **Configure Sanctum in `config/sanctum.php`:**
   ```php
   <?php
   
   return [
       'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
           '%s%s',
           'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
           env('APP_URL') ? ','.parse_url(env('APP_URL'), PHP_URL_HOST) : ''
       ))),
   
       'guard' => ['web'],
   
       'expiration' => null,
   
       'token_prefix' => env('SANCTUM_TOKEN_PREFIX', ''),
   
       'middleware' => [
           'authenticate_session' => Laravel\Sanctum\Http\Middleware\AuthenticateSession::class,
           'encrypt_cookies' => App\Http\Middleware\EncryptCookies::class,
           'validate_csrf_token' => App\Http\Middleware\VerifyCsrfToken::class,
       ],
   ];
   ```

5. **Update `.env` with Sanctum configuration:**
   ```
   SANCTUM_STATEFUL_DOMAINS=localhost:3000,localhost
   SESSION_DRIVER=cookie
   SESSION_LIFETIME=120
   SESSION_DOMAIN=localhost
   SESSION_SECURE_COOKIE=false
   ```

6. **Update CORS configuration `config/cors.php`:**
   ```php
   <?php
   
   return [
       'paths' => ['api/*', 'sanctum/csrf-cookie'],
   
       'allowed_methods' => ['*'],
   
       'allowed_origins' => [env('FRONTEND_URL', 'http://localhost:3000')],
   
       'allowed_origins_patterns' => [],
   
       'allowed_headers' => ['*'],
   
       'exposed_headers' => ['Authorization'],
   
       'max_age' => 0,
   
       'supports_credentials' => true,
   ];
   ```

7. **Create AuthController:**
   ```bash
   php artisan make:controller Api/V1/AuthController
   ```

8. **Implement AuthController `app/Http/Controllers/Api/V1/AuthController.php`:**
   ```php
   <?php
   
   namespace App\Http\Controllers\Api\V1;
   
   use App\Http\Controllers\Controller;
   use App\Http\Requests\LoginRequest;
   use App\Models\User;
   use Illuminate\Http\JsonResponse;
   use Illuminate\Http\Request;
   use Illuminate\Support\Facades\Auth;
   use Illuminate\Support\Facades\Hash;
   use Illuminate\Validation\ValidationException;
   
   class AuthController extends Controller
   {
       public function login(LoginRequest $request): JsonResponse
       {
           $user = User::where('email', $request->email)->first();
   
           if (!$user || !Hash::check($request->password, $user->password)) {
               throw ValidationException::withMessages([
                   'email' => ['The provided credentials are incorrect.'],
               ]);
           }
   
           if (!$user->is_active) {
               throw ValidationException::withMessages([
                   'email' => ['Your account has been deactivated. Contact administrator.'],
               ]);
           }
   
           // Update last login
           $user->update([
               'last_login_at' => now(),
               'last_active_at' => now(),
           ]);
   
           // Create token
           $token = $user->createToken('auth-token')->plainTextToken;
   
           return response()->json([
               'success' => true,
               'data' => [
                   'user' => [
                       'id' => $user->id,
                       'name' => $user->name,
                       'email' => $user->email,
                       'role' => $user->role,
                       'founder_role' => $user->founder_role,
                       'can_edit_landing_page' => $user->can_edit_landing_page,
                   ],
                   'token' => $token,
               ],
               'message' => 'Login successful',
           ]);
       }
   
       public function logout(Request $request): JsonResponse
       {
           $request->user()->currentAccessToken()->delete();
   
           return response()->json([
               'success' => true,
               'message' => 'Logged out successfully',
           ]);
       }
   
       public function user(Request $request): JsonResponse
       {
           $user = $request->user();
   
           return response()->json([
               'success' => true,
               'data' => [
                   'id' => $user->id,
                   'name' => $user->name,
                   'email' => $user->email,
                   'role' => $user->role,
                   'founder_role' => $user->founder_role,
                   'can_edit_landing_page' => $user->can_edit_landing_page,
                   'is_active' => $user->is_active,
                   'last_login_at' => $user->last_login_at,
               ],
           ]);
       }
   
       public function refresh(Request $request): JsonResponse
       {
           $user = $request->user();
           
           // Delete old token
           $request->user()->currentAccessToken()->delete();
           
           // Create new token
           $token = $user->createToken('auth-token')->plainTextToken;
   
           return response()->json([
               'success' => true,
               'data' => [
                   'token' => $token,
               ],
               'message' => 'Token refreshed successfully',
           ]);
       }
   }
   ```

9. **Create LoginRequest:**
   ```bash
   php artisan make:request LoginRequest
   ```

10. **Implement LoginRequest `app/Http/Requests/LoginRequest.php`:**
    ```php
    <?php
    
    namespace App\Http\Requests;
    
    use Illuminate\Foundation\Http\FormRequest;
    
    class LoginRequest extends FormRequest
    {
        public function authorize(): bool
        {
            return true;
        }
    
        public function rules(): array
        {
            return [
                'email' => ['required', 'email', 'max:255'],
                'password' => ['required', 'string', 'min:8'],
            ];
        }
    
        public function messages(): array
        {
            return [
                'email.required' => 'Email is required',
                'email.email' => 'Please enter a valid email address',
                'password.required' => 'Password is required',
                'password.min' => 'Password must be at least 8 characters',
            ];
        }
    }
    ```

11. **Add authentication routes to `routes/api.php`:**
    ```php
    <?php
    
    use App\Http\Controllers\Api\V1\AuthController;
    use Illuminate\Support\Facades\Route;
    
    Route::prefix('v1')->group(function () {
        // Public routes
        Route::post('/auth/login', [AuthController::class, 'login']);
        
        // Protected routes
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/auth/logout', [AuthController::class, 'logout']);
            Route::get('/auth/user', [AuthController::class, 'user']);
            Route::post('/auth/refresh', [AuthController::class, 'refresh']);
        });
    });
    ```

12. **Create middleware for updating last active:**
    ```bash
    php artisan make:middleware UpdateLastActive
    ```

13. **Implement UpdateLastActive middleware:**
    ```php
    <?php
    
    namespace App\Http\Middleware;
    
    use Closure;
    use Illuminate\Http\Request;
    use Symfony\Component\HttpFoundation\Response;
    
    class UpdateLastActive
    {
        public function handle(Request $request, Closure $next): Response
        {
            if ($request->user()) {
                $request->user()->update([
                    'last_active_at' => now(),
                ]);
            }
    
            return $next($request);
        }
    }
    ```

14. **Register middleware in `bootstrap/app.php`:**
    ```php
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);
        
        $middleware->alias([
            'update.last.active' => \App\Http\Middleware\UpdateLastActive::class,
        ]);
    })
    ```

15. **Apply middleware to protected routes:**
    ```php
    Route::middleware(['auth:sanctum', 'update.last.active'])->group(function () {
        // All protected routes
    });
    ```

16. **Test authentication with Postman/Insomnia:**
    - POST http://localhost:8000/api/v1/auth/login
    - Body: `{"email": "admin@tarqumi.com", "password": "password"}`
    - Save token from response
    - GET http://localhost:8000/api/v1/auth/user
    - Header: `Authorization: Bearer {token}`

**Acceptance Criteria:**
- [ ] Sanctum installed and configured
- [ ] CORS configured for frontend
- [ ] AuthController created with all methods
- [ ] LoginRequest validation working
- [ ] Login endpoint returns user and token
- [ ] Logout endpoint invalidates token
- [ ] User endpoint returns current user
- [ ] Refresh endpoint generates new token
- [ ] Last active middleware updates timestamp
- [ ] Inactive users cannot login
- [ ] Invalid credentials rejected
- [ ] All routes properly protected

**Testing:**
```bash
# Test with Artisan Tinker
php artisan tinker
>>> $user = User::first()
>>> $token = $user->createToken('test')->plainTextToken
>>> echo $token

# Test with curl
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@tarqumi.com","password":"password"}'
```

**Files Created:**
- `app/Http/Controllers/Api/V1/AuthController.php`
- `app/Http/Requests/LoginRequest.php`
- `app/Http/Middleware/UpdateLastActive.php`
- `routes/api.php` (updated)

**Security Considerations:**
- Passwords never exposed in responses
- Tokens stored securely
- CORS restricted to frontend URL
- Rate limiting on login endpoint
- Last active tracking for security

**Performance Considerations:**
- Token validation cached
- Last active updates batched
- Minimal database queries

**Notes:**
- Token never expires by default
- Use refresh endpoint for long sessions
- Logout deletes current token only
- Multiple devices supported

---

## Task 3.2: Implement Role-Based Access Control (RBAC)

**Priority:** 🔴 Critical  
**Estimated Time:** 3 hours  
**Dependencies:** Task 3.1  
**Assigned To:** Backend Developer

**Objective:**
Create comprehensive role-based access control system with middleware, policies, and permission checks.

**Detailed Steps:**

1. **Create RoleMiddleware:**
   ```bash
   php artisan make:middleware RoleMiddleware
   ```

2. **Implement RoleMiddleware:**
   ```php
   <?php
   
   namespace App\Http\Middleware;
   
   use Closure;
   use Illuminate\Http\Request;
   use Symfony\Component\HttpFoundation\Response;
   
   class RoleMiddleware
   {
       public function handle(Request $request, Closure $next, string ...$roles): Response
       {
           if (!$request->user()) {
               return response()->json([
                   'success' => false,
                   'message' => 'Unauthenticated',
               ], 401);
           }
   
           if (!in_array($request->user()->role, $roles)) {
               return response()->json([
                   'success' => false,
                   'message' => 'Unauthorized. Insufficient permissions.',
               ], 403);
           }
   
           return $next($request);
       }
   }
   ```

3. **Create FounderRoleMiddleware:**
   ```bash
   php artisan make:middleware FounderRoleMiddleware
   ```

4. **Implement FounderRoleMiddleware:**
   ```php
   <?php
   
   namespace App\Http\Middleware;
   
   use Closure;
   use Illuminate\Http\Request;
   use Symfony\Component\HttpFoundation\Response;
   
   class FounderRoleMiddleware
   {
       public function handle(Request $request, Closure $next, string ...$founderRoles): Response
       {
           $user = $request->user();
   
           if (!$user || $user->role !== 'founder') {
               return response()->json([
                   'success' => false,
                   'message' => 'Unauthorized. Founder role required.',
               ], 403);
           }
   
           if (!in_array($user->founder_role, $founderRoles)) {
               return response()->json([
                   'success' => false,
                   'message' => 'Unauthorized. Specific founder role required.',
               ], 403);
           }
   
           return $next($request);
       }
   }
   ```

5. **Register middleware aliases:**
   ```php
   ->withMiddleware(function (Middleware $middleware) {
       $middleware->alias([
           'role' => \App\Http\Middleware\RoleMiddleware::class,
           'founder.role' => \App\Http\Middleware\FounderRoleMiddleware::class,
           'update.last.active' => \App\Http\Middleware\UpdateLastActive::class,
       ]);
   })
   ```

6. **Create Permission trait:**
   ```bash
   mkdir -p app/Traits
   touch app/Traits/HasPermissions.php
   ```

7. **Implement HasPermissions trait:**
   ```php
   <?php
   
   namespace App\Traits;
   
   trait HasPermissions
   {
       public function canManageTeam(): bool
       {
           return in_array($this->role, ['super_admin', 'admin', 'hr']);
       }
   
       public function canCreateTeamMember(): bool
       {
           return in_array($this->role, ['super_admin', 'admin', 'hr']);
       }
   
       public function canEditTeamMember(): bool
       {
           return in_array($this->role, ['super_admin', 'admin', 'hr']);
       }
   
       public function canDeleteTeamMember(): bool
       {
           return in_array($this->role, ['super_admin', 'admin']);
       }
   
       public function canDeleteAdmin(): bool
       {
           return $this->role === 'super_admin';
       }
   
       public function canManageClients(): bool
       {
           return in_array($this->role, ['super_admin', 'admin']);
       }
   
       public function canViewClients(): bool
       {
           return in_array($this->role, ['super_admin', 'admin', 'founder']);
       }
   
       public function canManageProjects(): bool
       {
           return in_array($this->role, ['super_admin', 'admin']);
       }
   
       public function canViewProjects(): bool
       {
           return in_array($this->role, ['super_admin', 'admin', 'founder']);
       }
   
       public function canEditLandingPage(): bool
       {
           return $this->is_admin || 
                  ($this->is_founder && $this->founder_role === 'cto');
       }
   
       public function canManageBlog(): bool
       {
           return $this->canEditLandingPage();
       }
   
       public function canViewContactSubmissions(): bool
       {
           return in_array($this->role, ['super_admin', 'admin']);
       }
   
       public function canDeleteContactSubmissions(): bool
       {
           return in_array($this->role, ['super_admin', 'admin']);
       }
   }
   ```

8. **Add trait to User model:**
   ```php
   use App\Traits\HasPermissions;
   
   class User extends Authenticatable
   {
       use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasPermissions;
       
       // ... rest of the model
   }
   ```

9. **Create authorization helper:**
   ```bash
   touch app/Helpers/AuthHelper.php
   ```

10. **Implement AuthHelper:**
    ```php
    <?php
    
    namespace App\Helpers;
    
    use App\Models\User;
    use Illuminate\Support\Facades\Auth;
    
    class AuthHelper
    {
        public static function user(): ?User
        {
            return Auth::user();
        }
    
        public static function check(): bool
        {
            return Auth::check();
        }
    
        public static function isSuperAdmin(): bool
        {
            return self::check() && self::user()->role === 'super_admin';
        }
    
        public static function isAdmin(): bool
        {
            return self::check() && in_array(self::user()->role, ['super_admin', 'admin']);
        }
    
        public static function isFounder(): bool
        {
            return self::check() && self::user()->role === 'founder';
        }
    
        public static function isCTO(): bool
        {
            return self::isFounder() && self::user()->founder_role === 'cto';
        }
    
        public static function canEditLandingPage(): bool
        {
            return self::check() && self::user()->canEditLandingPage();
        }
    
        public static function hasRole(string ...$roles): bool
        {
            return self::check() && in_array(self::user()->role, $roles);
        }
    }
    ```

11. **Create permissions configuration:**
    ```bash
    touch config/permissions.php
    ```

12. **Implement permissions config:**
    ```php
    <?php
    
    return [
        'roles' => [
            'super_admin' => [
                'label' => 'Super Admin',
                'permissions' => ['*'], // All permissions
            ],
            'admin' => [
                'label' => 'Admin',
                'permissions' => [
                    'manage_team',
                    'manage_clients',
                    'manage_projects',
                    'edit_landing_page',
                    'manage_blog',
                    'view_contact_submissions',
                ],
            ],
            'founder' => [
                'label' => 'Founder',
                'permissions' => [
                    'view_clients',
                    'view_projects',
                ],
                'founder_roles' => [
                    'cto' => [
                        'label' => 'CTO',
                        'additional_permissions' => [
                            'edit_landing_page',
                            'manage_blog',
                        ],
                    ],
                    'ceo' => [
                        'label' => 'CEO',
                        'additional_permissions' => [],
                    ],
                    'cfo' => [
                        'label' => 'CFO',
                        'additional_permissions' => [],
                    ],
                ],
            ],
            'hr' => [
                'label' => 'HR',
                'permissions' => [
                    'manage_team',
                    'view_team',
                ],
            ],
            'employee' => [
                'label' => 'Employee',
                'permissions' => [
                    'view_own_profile',
                    'view_own_tasks',
                ],
            ],
        ],
    ];
    ```

13. **Create PermissionsController for frontend:**
    ```bash
    php artisan make:controller Api/V1/PermissionsController
    ```

14. **Implement PermissionsController:**
    ```php
    <?php
    
    namespace App\Http\Controllers\Api\V1;
    
    use App\Http\Controllers\Controller;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;
    
    class PermissionsController extends Controller
    {
        public function index(Request $request): JsonResponse
        {
            $user = $request->user();
    
            return response()->json([
                'success' => true,
                'data' => [
                    'role' => $user->role,
                    'founder_role' => $user->founder_role,
                    'permissions' => [
                        'can_manage_team' => $user->canManageTeam(),
                        'can_create_team_member' => $user->canCreateTeamMember(),
                        'can_edit_team_member' => $user->canEditTeamMember(),
                        'can_delete_team_member' => $user->canDeleteTeamMember(),
                        'can_delete_admin' => $user->canDeleteAdmin(),
                        'can_manage_clients' => $user->canManageClients(),
                        'can_view_clients' => $user->canViewClients(),
                        'can_manage_projects' => $user->canManageProjects(),
                        'can_view_projects' => $user->canViewProjects(),
                        'can_edit_landing_page' => $user->canEditLandingPage(),
                        'can_manage_blog' => $user->canManageBlog(),
                        'can_view_contact_submissions' => $user->canViewContactSubmissions(),
                        'can_delete_contact_submissions' => $user->canDeleteContactSubmissions(),
                    ],
                ],
            ]);
        }
    }
    ```

15. **Add permissions route:**
    ```php
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/permissions', [PermissionsController::class, 'index']);
    });
    ```

16. **Test RBAC:**
    ```bash
    php artisan tinker
    >>> $admin = User::where('role', 'super_admin')->first()
    >>> $admin->canManageTeam()
    >>> $admin->canDeleteAdmin()
    >>> $employee = User::where('role', 'employee')->first()
    >>> $employee->canManageClients()
    ```

**Acceptance Criteria:**
- [ ] RoleMiddleware created and working
- [ ] FounderRoleMiddleware created
- [ ] HasPermissions trait implemented
- [ ] All permission methods working
- [ ] AuthHelper utility created
- [ ] Permissions config defined
- [ ] PermissionsController returns user permissions
- [ ] Middleware properly restricts access
- [ ] Super admin has all permissions
- [ ] CTO can edit landing page
- [ ] CEO cannot edit landing page
- [ ] HR can manage team but not delete
- [ ] Employee has minimal permissions

**Testing:**
```bash
# Test with different users
php artisan tinker
>>> $superAdmin = User::where('role', 'super_admin')->first()
>>> $cto = User::where('founder_role', 'cto')->first()
>>> $ceo = User::where('founder_role', 'ceo')->first()
>>> $superAdmin->canEditLandingPage() // true
>>> $cto->canEditLandingPage() // true
>>> $ceo->canEditLandingPage() // false
```

**Files Created:**
- `app/Http/Middleware/RoleMiddleware.php`
- `app/Http/Middleware/FounderRoleMiddleware.php`
- `app/Traits/HasPermissions.php`
- `app/Helpers/AuthHelper.php`
- `config/permissions.php`
- `app/Http/Controllers/Api/V1/PermissionsController.php`

**Security Considerations:**
- All permissions checked on backend
- Frontend checks are for UX only
- Middleware prevents unauthorized access
- Policies enforce model-level permissions

**Performance Considerations:**
- Permission checks cached per request
- Minimal database queries
- Trait methods optimized

**Notes:**
- Always check permissions on backend
- Frontend uses permissions for UI only
- Super admin bypasses most checks
- Default client has special protection

---

