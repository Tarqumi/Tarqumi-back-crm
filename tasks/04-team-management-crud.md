# Module 4: Team Management CRUD Operations

## Overview
Complete CRUD operations for team member management including creation, listing with pagination/search/filter, editing, deletion with project reassignment, and 30-day auto-inactive functionality.

---

## Task 4.1: Create Team Member API Endpoint

**Priority:** 🔴 Critical  
**Estimated Time:** 3 hours  
**Dependencies:** Task 3.2  
**Assigned To:** Backend Developer

**Objective:**
Implement API endpoint for creating new team members with role assignment, validation, and email notification.

**Detailed Steps:**

1. **Create TeamController:**
   ```bash
   php artisan make:controller Api/V1/TeamController --resource
   ```

2. **Create StoreTeamMemberRequest:**
   ```bash
   php artisan make:request StoreTeamMemberRequest
   ```

3. **Implement StoreTeamMemberRequest:**
   ```php
   <?php
   
   namespace App\Http\Requests;
   
   use Illuminate\Foundation\Http\FormRequest;
   use Illuminate\Validation\Rule;
   
   class StoreTeamMemberRequest extends FormRequest
   {
       public function authorize(): bool
       {
           return $this->user()->canCreateTeamMember();
       }
   
       public function rules(): array
       {
           return [
               'name' => ['required', 'string', 'min:2', 'max:100'],
               'email' => ['required', 'email', 'max:255', 'unique:users,email'],
               'password' => ['required', 'string', 'min:8', 'confirmed'],
               'phone' => ['nullable', 'string', 'max:20'],
               'whatsapp' => ['nullable', 'string', 'max:20'],
               'department' => ['nullable', 'string', 'max:100'],
               'job_title' => ['nullable', 'string', 'max:100'],
               'role' => ['required', Rule::in(['super_admin', 'admin', 'founder', 'hr', 'employee'])],
               'founder_role' => ['nullable', 'required_if:role,founder', Rule::in(['ceo', 'cto', 'cfo'])],
               'is_active' => ['boolean'],
               'profile_picture' => ['nullable', 'image', 'max:5120'], // 5MB
           ];
       }
   
       public function messages(): array
       {
           return [
               'name.required' => 'Name is required',
               'name.min' => 'Name must be at least 2 characters',
               'name.max' => 'Name cannot exceed 100 characters',
               'email.required' => 'Email is required',
               'email.email' => 'Please enter a valid email address',
               'email.unique' => 'This email is already registered',
               'password.required' => 'Password is required',
               'password.min' => 'Password must be at least 8 characters',
               'password.confirmed' => 'Password confirmation does not match',
               'role.required' => 'Role is required',
               'role.in' => 'Invalid role selected',
               'founder_role.required_if' => 'Founder role is required when role is founder',
               'founder_role.in' => 'Invalid founder role selected',
               'profile_picture.image' => 'Profile picture must be an image',
               'profile_picture.max' => 'Profile picture cannot exceed 5MB',
           ];
       }
   
       protected function prepareForValidation(): void
       {
           // Normalize email to lowercase
           if ($this->has('email')) {
               $this->merge([
                   'email' => strtolower($this->email),
               ]);
           }
       }
   }
   ```

4. **Create TeamService:**
   ```bash
   mkdir -p app/Services
   touch app/Services/TeamService.php
   ```

5. **Implement TeamService:**
   ```php
   <?php
   
   namespace App\Services;
   
   use App\Models\User;
   use Illuminate\Http\UploadedFile;
   use Illuminate\Support\Facades\Hash;
   use Illuminate\Support\Facades\Storage;
   use Illuminate\Support\Str;
   
   class TeamService
   {
       public function createTeamMember(array $data): User
       {
           // Handle profile picture upload
           if (isset($data['profile_picture']) && $data['profile_picture'] instanceof UploadedFile) {
               $data['profile_picture'] = $this->uploadProfilePicture($data['profile_picture']);
           }
   
           // Hash password
           $data['password'] = Hash::make($data['password']);
   
           // Set default values
           $data['is_active'] = $data['is_active'] ?? true;
           $data['email_verified_at'] = now();
           $data['last_active_at'] = now();
   
           // Create user
           $user = User::create($data);
   
           // Send welcome email (implement later)
           // $this->sendWelcomeEmail($user, $originalPassword);
   
           return $user;
       }
   
       public function updateTeamMember(User $user, array $data): User
       {
           // Handle profile picture upload
           if (isset($data['profile_picture']) && $data['profile_picture'] instanceof UploadedFile) {
               // Delete old picture
               if ($user->profile_picture) {
                   Storage::disk('public')->delete($user->profile_picture);
               }
               $data['profile_picture'] = $this->uploadProfilePicture($data['profile_picture']);
           }
   
           // Hash password if provided
           if (isset($data['password'])) {
               $data['password'] = Hash::make($data['password']);
           } else {
               unset($data['password']);
           }
   
           // Update user
           $user->update($data);
   
           return $user->fresh();
       }
   
       public function deleteTeamMember(User $user): bool
       {
           // Check if user is project manager
           $managedProjects = $user->managedProjects()->count();
           
           if ($managedProjects > 0) {
               throw new \Exception("Cannot delete team member. They are managing {$managedProjects} projects. Please reassign projects first.");
           }
   
           // Soft delete
           return $user->delete();
       }
   
       public function reassignProjects(User $oldManager, User $newManager): int
       {
           $projects = $oldManager->managedProjects;
           
           foreach ($projects as $project) {
               $project->update(['manager_id' => $newManager->id]);
           }
   
           return $projects->count();
       }
   
       private function uploadProfilePicture(UploadedFile $file): string
       {
           $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
           $path = $file->storeAs('profile-pictures', $filename, 'public');
           
           return $path;
       }
   }
   ```

6. **Implement TeamController store method:**
   ```php
   <?php
   
   namespace App\Http\Controllers\Api\V1;
   
   use App\Http\Controllers\Controller;
   use App\Http\Requests\StoreTeamMemberRequest;
   use App\Http\Resources\UserResource;
   use App\Services\TeamService;
   use Illuminate\Http\JsonResponse;
   
   class TeamController extends Controller
   {
       public function __construct(
           private TeamService $teamService
       ) {}
   
       public function store(StoreTeamMemberRequest $request): JsonResponse
       {
           try {
               $user = $this->teamService->createTeamMember($request->validated());
   
               return response()->json([
                   'success' => true,
                   'data' => new UserResource($user),
                   'message' => 'Team member created successfully',
               ], 201);
           } catch (\Exception $e) {
               return response()->json([
                   'success' => false,
                   'message' => 'Failed to create team member: ' . $e->getMessage(),
               ], 500);
           }
       }
   }
   ```

7. **Create UserResource:**
   ```bash
   php artisan make:resource UserResource
   ```

8. **Implement UserResource:**
   ```php
   <?php
   
   namespace App\Http\Resources;
   
   use Illuminate\Http\Request;
   use Illuminate\Http\Resources\Json\JsonResource;
   
   class UserResource extends JsonResource
   {
       public function toArray(Request $request): array
       {
           return [
               'id' => $this->id,
               'name' => $this->name,
               'email' => $this->email,
               'phone' => $this->phone,
               'whatsapp' => $this->whatsapp,
               'department' => $this->department,
               'job_title' => $this->job_title,
               'profile_picture' => $this->profile_picture ? asset('storage/' . $this->profile_picture) : null,
               'role' => $this->role,
               'founder_role' => $this->founder_role,
               'is_active' => $this->is_active,
               'last_login_at' => $this->last_login_at?->toIso8601String(),
               'last_active_at' => $this->last_active_at?->toIso8601String(),
               'inactive_days' => $this->inactive_days,
               'created_at' => $this->created_at->toIso8601String(),
               'updated_at' => $this->updated_at->toIso8601String(),
               'managed_projects_count' => $this->whenLoaded('managedProjects', fn() => $this->managedProjects->count()),
           ];
       }
   }
   ```

9. **Add route:**
   ```php
   Route::middleware(['auth:sanctum', 'role:super_admin,admin,hr'])->group(function () {
       Route::post('/team', [TeamController::class, 'store']);
   });
   ```

10. **Create unit test:**
    ```bash
    php artisan make:test TeamManagementTest
    ```

11. **Implement test:**
    ```php
    <?php
    
    namespace Tests\Feature;
    
    use App\Models\User;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Tests\TestCase;
    
    class TeamManagementTest extends TestCase
    {
        use RefreshDatabase;
    
        public function test_admin_can_create_team_member(): void
        {
            $admin = User::factory()->admin()->create();
    
            $response = $this->actingAs($admin, 'sanctum')
                ->postJson('/api/v1/team', [
                    'name' => 'John Doe',
                    'email' => 'john@example.com',
                    'password' => 'password123',
                    'password_confirmation' => 'password123',
                    'role' => 'employee',
                    'is_active' => true,
                ]);
    
            $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'data' => ['id', 'name', 'email', 'role'],
                    'message',
                ]);
    
            $this->assertDatabaseHas('users', [
                'email' => 'john@example.com',
                'role' => 'employee',
            ]);
        }
    
        public function test_employee_cannot_create_team_member(): void
        {
            $employee = User::factory()->create(['role' => 'employee']);
    
            $response = $this->actingAs($employee, 'sanctum')
                ->postJson('/api/v1/team', [
                    'name' => 'John Doe',
                    'email' => 'john@example.com',
                    'password' => 'password123',
                    'password_confirmation' => 'password123',
                    'role' => 'employee',
                ]);
    
            $response->assertStatus(403);
        }
    
        public function test_duplicate_email_rejected(): void
        {
            $admin = User::factory()->admin()->create();
            User::factory()->create(['email' => 'existing@example.com']);
    
            $response = $this->actingAs($admin, 'sanctum')
                ->postJson('/api/v1/team', [
                    'name' => 'John Doe',
                    'email' => 'existing@example.com',
                    'password' => 'password123',
                    'password_confirmation' => 'password123',
                    'role' => 'employee',
                ]);
    
            $response->assertStatus(422)
                ->assertJsonValidationErrors(['email']);
        }
    }
    ```

12. **Run tests:**
    ```bash
    php artisan test --filter TeamManagementTest
    ```

**Acceptance Criteria:**
- [ ] StoreTeamMemberRequest validates all fields
- [ ] Email normalized to lowercase
- [ ] Password hashed securely
- [ ] Profile picture uploaded and stored
- [ ] TeamService handles business logic
- [ ] UserResource formats response
- [ ] Route protected by role middleware
- [ ] Only admin/HR can create members
- [ ] Duplicate emails rejected
- [ ] Founder role required when role is founder
- [ ] Tests pass successfully

**Testing:**
```bash
# Test with Postman
POST http://localhost:8000/api/v1/team
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  "name": "Test User",
  "email": "test@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "employee",
  "department": "Engineering",
  "is_active": true
}
```

**Files Created:**
- `app/Http/Controllers/Api/V1/TeamController.php`
- `app/Http/Requests/StoreTeamMemberRequest.php`
- `app/Services/TeamService.php`
- `app/Http/Resources/UserResource.php`
- `tests/Feature/TeamManagementTest.php`

**Security Considerations:**
- Authorization checked in request
- Password hashed before storage
- Email uniqueness enforced
- Profile pictures validated
- Role-based access control

**Performance Considerations:**
- Profile pictures optimized
- Minimal database queries
- Resource transformation efficient

**Notes:**
- Welcome email to be implemented
- Profile pictures stored in public disk
- Soft delete preserves data
- Tests ensure functionality

---

## Task 4.2: List Team Members with Pagination, Search, and Filters

**Priority:** 🔴 Critical  
**Estimated Time:** 4 hours  
**Dependencies:** Task 4.1  
**Assigned To:** Backend Developer

**Objective:**
Implement comprehensive team member listing with pagination, real-time search, multiple filters, and sorting options.

**Detailed Steps:**

1. **Create IndexTeamRequest:**
   ```bash
   php artisan make:request IndexTeamRequest
   ```

2. **Implement IndexTeamRequest:**
   ```php
   <?php
   
   namespace App\Http\Requests;
   
   use Illuminate\Foundation\Http\FormRequest;
   use Illuminate\Validation\Rule;
   
   class IndexTeamRequest extends FormRequest
   {
       public function authorize(): bool
       {
           return $this->user()->canManageTeam();
       }
   
       public function rules(): array
       {
           return [
               'page' => ['integer', 'min:1'],
               'per_page' => ['integer', Rule::in([10, 20, 50, 100])],
               'search' => ['nullable', 'string', 'max:255'],
               'role' => ['nullable', Rule::in(['super_admin', 'admin', 'founder', 'hr', 'employee'])],
               'status' => ['nullable', Rule::in(['active', 'inactive'])],
               'department' => ['nullable', 'string', 'max:100'],
               'sort_by' => ['nullable', Rule::in(['name', 'email', 'role', 'created_at', 'last_active_at'])],
               'sort_order' => ['nullable', Rule::in(['asc', 'desc'])],
           ];
       }
   }
   ```

3. **Add index method to TeamService:**
   ```php
   public function getTeamMembers(array $filters): \Illuminate\Contracts\Pagination\LengthAwarePaginator
   {
       $query = User::query();
   
       // Search
       if (!empty($filters['search'])) {
           $search = $filters['search'];
           $query->where(function ($q) use ($search) {
               $q->where('name', 'like', "%{$search}%")
                 ->orWhere('email', 'like', "%{$search}%")
                 ->orWhere('phone', 'like', "%{$search}%")
                 ->orWhere('department', 'like', "%{$search}%");
           });
       }
   
       // Filter by role
       if (!empty($filters['role'])) {
           $query->where('role', $filters['role']);
       }
   
       // Filter by status
       if (!empty($filters['status'])) {
           $query->where('is_active', $filters['status'] === 'active');
       }
   
       // Filter by department
       if (!empty($filters['department'])) {
           $query->where('department', $filters['department']);
       }
   
       // Sorting
       $sortBy = $filters['sort_by'] ?? 'name';
       $sortOrder = $filters['sort_order'] ?? 'asc';
       $query->orderBy($sortBy, $sortOrder);
   
       // Pagination
       $perPage = $filters['per_page'] ?? 20;
       
       return $query->with('managedProjects')->paginate($perPage);
   }
   
   public function getDepartments(): array
   {
       return User::whereNotNull('department')
           ->distinct()
           ->pluck('department')
           ->toArray();
   }
   ```

4. **Implement index method in TeamController:**
   ```php
   public function index(IndexTeamRequest $request): JsonResponse
   {
       $filters = $request->validated();
       $users = $this->teamService->getTeamMembers($filters);
   
       return response()->json([
           'success' => true,
           'data' => UserResource::collection($users),
           'meta' => [
               'current_page' => $users->currentPage(),
               'last_page' => $users->lastPage(),
               'per_page' => $users->perPage(),
               'total' => $users->total(),
               'from' => $users->firstItem(),
               'to' => $users->lastItem(),
           ],
       ]);
   }
   ```

5. **Add departments endpoint:**
   ```php
   public function departments(): JsonResponse
   {
       $departments = $this->teamService->getDepartments();
   
       return response()->json([
           'success' => true,
           'data' => $departments,
       ]);
   }
   ```

6. **Add routes:**
   ```php
   Route::middleware(['auth:sanctum', 'role:super_admin,admin,hr'])->group(function () {
       Route::get('/team', [TeamController::class, 'index']);
       Route::get('/team/departments', [TeamController::class, 'departments']);
       Route::post('/team', [TeamController::class, 'store']);
   });
   ```

7. **Create UserCollection resource:**
   ```bash
   php artisan make:resource UserCollection
   ```

8. **Implement UserCollection:**
   ```php
   <?php
   
   namespace App\Http\Resources;
   
   use Illuminate\Http\Request;
   use Illuminate\Http\Resources\Json\ResourceCollection;
   
   class UserCollection extends ResourceCollection
   {
       public function toArray(Request $request): array
       {
           return [
               'data' => $this->collection,
               'meta' => [
                   'total' => $this->total(),
                   'count' => $this->count(),
                   'per_page' => $this->perPage(),
                   'current_page' => $this->currentPage(),
                   'total_pages' => $this->lastPage(),
               ],
           ];
       }
   }
   ```

9. **Add tests for listing:**
   ```php
   public function test_admin_can_list_team_members(): void
   {
       $admin = User::factory()->admin()->create();
       User::factory()->count(25)->create();
   
       $response = $this->actingAs($admin, 'sanctum')
           ->getJson('/api/v1/team');
   
       $response->assertStatus(200)
           ->assertJsonStructure([
               'success',
               'data' => [
                   '*' => ['id', 'name', 'email', 'role'],
               ],
               'meta' => ['current_page', 'last_page', 'total'],
           ]);
   }
   
   public function test_search_filters_team_members(): void
   {
       $admin = User::factory()->admin()->create();
       User::factory()->create(['name' => 'John Doe', 'email' => 'john@example.com']);
       User::factory()->create(['name' => 'Jane Smith', 'email' => 'jane@example.com']);
   
       $response = $this->actingAs($admin, 'sanctum')
           ->getJson('/api/v1/team?search=john');
   
       $response->assertStatus(200)
           ->assertJsonCount(1, 'data');
   }
   
   public function test_role_filter_works(): void
   {
       $admin = User::factory()->admin()->create();
       User::factory()->count(5)->create(['role' => 'employee']);
       User::factory()->count(3)->create(['role' => 'hr']);
   
       $response = $this->actingAs($admin, 'sanctum')
           ->getJson('/api/v1/team?role=employee');
   
       $response->assertStatus(200)
           ->assertJsonCount(5, 'data');
   }
   ```

10. **Run tests:**
    ```bash
    php artisan test --filter TeamManagementTest
    ```

**Acceptance Criteria:**
- [ ] Pagination works with configurable per_page
- [ ] Search filters by name, email, phone, department
- [ ] Role filter works correctly
- [ ] Status filter (active/inactive) works
- [ ] Department filter works
- [ ] Sorting by multiple fields works
- [ ] Sort order (asc/desc) works
- [ ] Departments endpoint returns unique list
- [ ] Meta data includes pagination info
- [ ] Tests pass for all scenarios

**Testing:**
```bash
# Test listing
GET http://localhost:8000/api/v1/team
Authorization: Bearer {token}

# Test with filters
GET http://localhost:8000/api/v1/team?search=john&role=employee&status=active&per_page=10&sort_by=name&sort_order=asc

# Test departments
GET http://localhost:8000/api/v1/team/departments
```

**Files Created:**
- `app/Http/Requests/IndexTeamRequest.php`
- `app/Http/Resources/UserCollection.php`
- Updated `app/Services/TeamService.php`
- Updated `app/Http/Controllers/Api/V1/TeamController.php`

**Security Considerations:**
- Authorization checked in request
- Query parameters validated
- SQL injection prevented by Eloquent
- Search properly escaped

**Performance Considerations:**
- Indexes on searchable columns
- Eager loading relationships
- Pagination prevents memory issues
- Efficient query building

**Notes:**
- Default 20 items per page
- Search is case-insensitive
- Filters can be combined
- Sorting defaults to name ASC

---



## Task 4.3: Update Team Member API Endpoint

**Priority:** 🔴 Critical  
**Estimated Time:** 3 hours  
**Dependencies:** Task 4.1, Task 4.2  
**Assigned To:** Backend Developer

**Objective:**
Implement API endpoint for updating team member information with proper validation, authorization, and audit logging.

**Detailed Steps:**

1. **Create UpdateTeamMemberRequest:**
   ```bash
   php artisan make:request UpdateTeamMemberRequest
   ```

2. **Implement UpdateTeamMemberRequest:**
   ```php
   <?php
   
   namespace App\Http\Requests;
   
   use Illuminate\Foundation\Http\FormRequest;
   use Illuminate\Validation\Rule;
   
   class UpdateTeamMemberRequest extends FormRequest
   {
       public function authorize(): bool
       {
           $user = $this->route('user');
           
           // Only Super Admin can edit Super Admin accounts
           if ($user->role === 'super_admin' && !$this->user()->isSuperAdmin()) {
               return false;
           }
           
           // Admin cannot escalate own privileges
           if ($user->id === $this->user()->id && $this->role === 'super_admin') {
               return false;
           }
           
           return $this->user()->canEditTeamMember();
       }
   
       public function rules(): array
       {
           $userId = $this->route('user')->id;
           
           return [
               'name' => ['sometimes', 'string', 'min:2', 'max:100'],
               'email' => ['sometimes', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
               'password' => ['sometimes', 'string', 'min:8', 'confirmed'],
               'phone' => ['nullable', 'string', 'max:20'],
               'whatsapp' => ['nullable', 'string', 'max:20'],
               'department' => ['nullable', 'string', 'max:100'],
               'job_title' => ['nullable', 'string', 'max:100'],
               'role' => ['sometimes', Rule::in(['super_admin', 'admin', 'founder', 'hr', 'employee'])],
               'founder_role' => ['nullable', 'required_if:role,founder', Rule::in(['ceo', 'cto', 'cfo'])],
               'is_active' => ['boolean'],
               'profile_picture' => ['nullable', 'image', 'max:5120'],
           ];
       }
   }
   ```

3. **Implement update method in TeamController:**
   ```php
   public function update(UpdateTeamMemberRequest $request, User $user): JsonResponse
   {
       try {
           $updatedUser = $this->teamService->updateTeamMember($user, $request->validated());
   
           return response()->json([
               'success' => true,
               'data' => new UserResource($updatedUser),
               'message' => 'Team member updated successfully',
           ]);
       } catch (\Exception $e) {
           return response()->json([
               'success' => false,
               'message' => 'Failed to update team member: ' . $e->getMessage(),
           ], 500);
       }
   }
   ```

4. **Add route:**
   ```php
   Route::middleware(['auth:sanctum', 'role:super_admin,admin,hr'])->group(function () {
       Route::put('/team/{user}', [TeamController::class, 'update']);
       Route::patch('/team/{user}', [TeamController::class, 'update']);
   });
   ```

5. **Add tests:**
   ```php
   public function test_admin_can_update_team_member(): void
   {
       $admin = User::factory()->admin()->create();
       $employee = User::factory()->create(['role' => 'employee']);
   
       $response = $this->actingAs($admin, 'sanctum')
           ->putJson("/api/v1/team/{$employee->id}", [
               'name' => 'Updated Name',
               'department' => 'Engineering',
           ]);
   
       $response->assertStatus(200);
       $this->assertDatabaseHas('users', [
               'id' => $employee->id,
               'name' => 'Updated Name',
               'department' => 'Engineering',
           ]);
   }
   
   public function test_admin_cannot_edit_super_admin(): void
   {
       $admin = User::factory()->admin()->create();
       $superAdmin = User::factory()->superAdmin()->create();
   
       $response = $this->actingAs($admin, 'sanctum')
           ->putJson("/api/v1/team/{$superAdmin->id}", [
               'name' => 'Hacked Name',
           ]);
   
       $response->assertStatus(403);
   }
   
   public function test_admin_cannot_escalate_own_privileges(): void
   {
       $admin = User::factory()->admin()->create();
   
       $response = $this->actingAs($admin, 'sanctum')
           ->putJson("/api/v1/team/{$admin->id}", [
               'role' => 'super_admin',
           ]);
   
       $response->assertStatus(403);
   }
   ```

**Acceptance Criteria:**
- [ ] UpdateTeamMemberRequest validates all fields
- [ ] Email uniqueness validated (excluding current user)
- [ ] Only Super Admin can edit Super Admin accounts
- [ ] Admin cannot escalate own privileges
- [ ] Role changes trigger permission updates
- [ ] Profile picture upload and replacement works
- [ ] Changes logged in audit log
- [ ] Tests pass successfully

**Testing:**
```bash
# Test with Postman
PUT http://localhost:8000/api/v1/team/{id}
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  "name": "Updated Name",
  "department": "Engineering",
  "is_active": true
}
```

**Files Modified:**
- `app/Http/Requests/UpdateTeamMemberRequest.php`
- `app/Http/Controllers/Api/V1/TeamController.php`
- `tests/Feature/TeamManagementTest.php`

**Security Considerations:**
- Authorization checked per user being edited
- Role escalation prevented
- Super Admin accounts protected
- All changes logged

**Performance Considerations:**
- Minimal database queries
- Profile picture optimization
- Efficient validation

**Notes:**
- Partial updates supported (PATCH)
- Password optional on update
- Profile picture replacement deletes old file

---

## Task 4.4: Delete Team Member with Project Reassignment

**Priority:** 🟠 High  
**Estimated Time:** 4 hours  
**Dependencies:** Task 4.3  
**Assigned To:** Backend Developer

**Objective:**
Implement team member deletion with mandatory project reassignment for Project Managers and proper soft delete functionality.

**Detailed Steps:**

1. **Create DeleteTeamMemberRequest:**
   ```bash
   php artisan make:request DeleteTeamMemberRequest
   ```

2. **Implement DeleteTeamMemberRequest:**
   ```php
   <?php
   
   namespace App\Http\Requests;
   
   use Illuminate\Foundation\Http\FormRequest;
   
   class DeleteTeamMemberRequest extends FormRequest
   {
       public function authorize(): bool
       {
           $user = $this->route('user');
           
           // Only Super Admin can delete Super Admin accounts
           if ($user->role === 'super_admin' && !$this->user()->isSuperAdmin()) {
               return false;
           }
           
           // Cannot delete last Super Admin
           if ($user->role === 'super_admin') {
               $superAdminCount = \App\Models\User::where('role', 'super_admin')->count();
               if ($superAdminCount <= 1) {
                   return false;
               }
           }
           
           return $this->user()->canDeleteTeamMember();
       }
   
       public function rules(): array
       {
           return [
               'new_manager_id' => ['nullable', 'exists:users,id'],
           ];
       }
   }
   ```

3. **Implement destroy method in TeamController:**
   ```php
   public function destroy(DeleteTeamMemberRequest $request, User $user): JsonResponse
   {
       try {
           // Check if user is managing projects
           $managedProjects = $user->managedProjects()->count();
           
           if ($managedProjects > 0) {
               if (!$request->has('new_manager_id')) {
                   return response()->json([
                       'success' => false,
                       'message' => "Cannot delete team member. They are managing {$managedProjects} projects. Please provide new_manager_id for reassignment.",
                       'managed_projects_count' => $managedProjects,
                   ], 422);
               }
               
               $newManager = User::findOrFail($request->new_manager_id);
               $reassignedCount = $this->teamService->reassignProjects($user, $newManager);
           }
           
           $this->teamService->deleteTeamMember($user);
   
           return response()->json([
               'success' => true,
               'message' => 'Team member deleted successfully',
               'reassigned_projects' => $reassignedCount ?? 0,
           ]);
       } catch (\Exception $e) {
           return response()->json([
               'success' => false,
               'message' => 'Failed to delete team member: ' . $e->getMessage(),
           ], 500);
       }
   }
   ```

4. **Add route:**
   ```php
   Route::middleware(['auth:sanctum', 'role:super_admin,admin'])->group(function () {
       Route::delete('/team/{user}', [TeamController::class, 'destroy']);
   });
   ```

5. **Add tests:**
   ```php
   public function test_cannot_delete_team_member_with_projects_without_reassignment(): void
   {
       $admin = User::factory()->admin()->create();
       $pm = User::factory()->create(['role' => 'admin']);
       $project = Project::factory()->create(['manager_id' => $pm->id]);
   
       $response = $this->actingAs($admin, 'sanctum')
           ->deleteJson("/api/v1/team/{$pm->id}");
   
       $response->assertStatus(422)
           ->assertJson(['managed_projects_count' => 1]);
   }
   
   public function test_can_delete_team_member_with_project_reassignment(): void
   {
       $admin = User::factory()->admin()->create();
       $pm = User::factory()->create(['role' => 'admin']);
       $newPm = User::factory()->create(['role' => 'admin']);
       $project = Project::factory()->create(['manager_id' => $pm->id]);
   
       $response = $this->actingAs($admin, 'sanctum')
           ->deleteJson("/api/v1/team/{$pm->id}", [
               'new_manager_id' => $newPm->id,
           ]);
   
       $response->assertStatus(200)
           ->assertJson(['reassigned_projects' => 1]);
       
       $this->assertSoftDeleted('users', ['id' => $pm->id]);
       $this->assertDatabaseHas('projects', [
           'id' => $project->id,
           'manager_id' => $newPm->id,
       ]);
   }
   
   public function test_cannot_delete_last_super_admin(): void
   {
       $superAdmin = User::factory()->superAdmin()->create();
   
       $response = $this->actingAs($superAdmin, 'sanctum')
           ->deleteJson("/api/v1/team/{$superAdmin->id}");
   
       $response->assertStatus(403);
   }
   ```

**Acceptance Criteria:**
- [ ] Cannot delete team member managing projects without reassignment
- [ ] Project reassignment works correctly
- [ ] Soft delete preserves data
- [ ] Cannot delete last Super Admin
- [ ] Only Super Admin can delete Super Admin accounts
- [ ] Deletion logged in audit log
- [ ] Tests pass successfully

**Testing:**
```bash
# Test deletion with reassignment
DELETE http://localhost:8000/api/v1/team/{id}
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  "new_manager_id": 5
}
```

**Files Created:**
- `app/Http/Requests/DeleteTeamMemberRequest.php`

**Files Modified:**
- `app/Http/Controllers/Api/V1/TeamController.php`
- `app/Services/TeamService.php`
- `tests/Feature/TeamManagementTest.php`

**Security Considerations:**
- Authorization checked
- Last Super Admin protected
- Soft delete prevents data loss
- All deletions logged

**Performance Considerations:**
- Efficient project reassignment
- Minimal database queries

**Notes:**
- Soft delete retains data for 90 days
- Projects reassigned before deletion
- New PM notified via email

---

## Task 4.5: Implement 30-Day Auto-Inactive Functionality

**Priority:** 🟠 High  
**Estimated Time:** 3 hours  
**Dependencies:** Task 4.1  
**Assigned To:** Backend Developer

**Objective:**
Implement automated job to mark team members as inactive after 30 days of no login activity, with warning emails and admin notifications.

**Detailed Steps:**

1. **Create AutoInactiveCommand:**
   ```bash
   php artisan make:command AutoInactiveTeamMembers
   ```

2. **Implement AutoInactiveCommand:**
   ```php
   <?php
   
   namespace App\Console\Commands;
   
   use App\Models\User;
   use App\Notifications\InactivityWarningNotification;
   use App\Notifications\AccountDeactivatedNotification;
   use Illuminate\Console\Command;
   use Carbon\Carbon;
   
   class AutoInactiveTeamMembers extends Command
   {
       protected $signature = 'team:auto-inactive';
       protected $description = 'Mark team members as inactive after 30 days of no login';
   
       public function handle()
       {
           $this->info('Checking for inactive team members...');
           
           // Send warning emails (25 days)
           $warningThreshold = Carbon::now()->subDays(25);
           $usersToWarn = User::where('is_active', true)
               ->where('last_login_at', '<=', $warningThreshold)
               ->whereNotIn('role', ['super_admin', 'admin']) // Exempt admins
               ->whereDoesntHave('inactivityWarnings', function ($query) {
                   $query->where('created_at', '>=', Carbon::now()->subDays(6));
               })
               ->get();
           
           foreach ($usersToWarn as $user) {
               $user->notify(new InactivityWarningNotification());
               $this->info("Warning sent to: {$user->email}");
           }
           
           // Mark as inactive (30 days)
           $inactiveThreshold = Carbon::now()->subDays(30);
           $usersToDeactivate = User::where('is_active', true)
               ->where('last_login_at', '<=', $inactiveThreshold)
               ->whereNotIn('role', ['super_admin', 'admin']) // Exempt admins
               ->get();
           
           $deactivatedCount = 0;
           foreach ($usersToDeactivate as $user) {
               $user->update(['is_active' => false]);
               $user->notify(new AccountDeactivatedNotification());
               $this->info("Deactivated: {$user->email}");
               $deactivatedCount++;
           }
           
           // Notify admins
           if ($deactivatedCount > 0) {
               $admins = User::whereIn('role', ['super_admin', 'admin'])->get();
               foreach ($admins as $admin) {
                   // Send weekly report
               }
           }
           
           $this->info("Completed. {$deactivatedCount} users deactivated.");
           
           return 0;
       }
   }
   ```

3. **Schedule the command in Kernel.php:**
   ```php
   protected function schedule(Schedule $schedule)
   {
       $schedule->command('team:auto-inactive')
           ->dailyAt('02:00')
           ->timezone('Asia/Riyadh');
   }
   ```

4. **Create InactivityWarningNotification:**
   ```bash
   php artisan make:notification InactivityWarningNotification
   ```

5. **Implement InactivityWarningNotification:**
   ```php
   <?php
   
   namespace App\Notifications;
   
   use Illuminate\Bus\Queueable;
   use Illuminate\Notifications\Messages\MailMessage;
   use Illuminate\Notifications\Notification;
   
   class InactivityWarningNotification extends Notification
   {
       use Queueable;
   
       public function via($notifiable)
       {
           return ['mail'];
       }
   
       public function toMail($notifiable)
       {
           $stayActiveUrl = route('stay-active', ['token' => $notifiable->generateStayActiveToken()]);
           
           return (new MailMessage)
               ->subject('Account Inactivity Warning - Tarqumi CRM')
               ->greeting("Hello {$notifiable->name},")
               ->line('Your Tarqumi CRM account will be deactivated in 5 days due to inactivity.')
               ->line('You have not logged in for 25 days.')
               ->action('Stay Active', $stayActiveUrl)
               ->line('If you do not take action, your account will be automatically deactivated.')
               ->line('You can reactivate your account by contacting your administrator.');
       }
   }
   ```

6. **Add inactive_days accessor to User model:**
   ```php
   public function getInactiveDaysAttribute(): int
   {
       if (!$this->last_login_at) {
           return 0;
       }
       
       return Carbon::parse($this->last_login_at)->diffInDays(Carbon::now());
   }
   ```

7. **Add tests:**
   ```php
   public function test_user_marked_inactive_after_30_days(): void
   {
       $user = User::factory()->create([
           'role' => 'employee',
           'is_active' => true,
           'last_login_at' => Carbon::now()->subDays(31),
       ]);
   
       $this->artisan('team:auto-inactive');
   
       $user->refresh();
       $this->assertFalse($user->is_active);
   }
   
   public function test_admin_exempt_from_auto_inactive(): void
   {
       $admin = User::factory()->admin()->create([
           'is_active' => true,
           'last_login_at' => Carbon::now()->subDays(60),
       ]);
   
       $this->artisan('team:auto-inactive');
   
       $admin->refresh();
       $this->assertTrue($admin->is_active);
   }
   
   public function test_warning_sent_at_25_days(): void
   {
       Notification::fake();
       
       $user = User::factory()->create([
           'role' => 'employee',
           'is_active' => true,
           'last_login_at' => Carbon::now()->subDays(26),
       ]);
   
       $this->artisan('team:auto-inactive');
   
       Notification::assertSentTo($user, InactivityWarningNotification::class);
   }
   ```

**Acceptance Criteria:**
- [ ] Command runs daily at 2:00 AM
- [ ] Warning emails sent at 25 days
- [ ] Users marked inactive at 30 days
- [ ] Super Admin and Admin exempt
- [ ] Inactive users cannot login
- [ ] Admin receives weekly report
- [ ] Tests pass successfully

**Testing:**
```bash
# Run command manually
php artisan team:auto-inactive

# Test with specific date
php artisan tinker
> $user = User::find(1);
> $user->last_login_at = Carbon::now()->subDays(31);
> $user->save();
> exit
php artisan team:auto-inactive
```

**Files Created:**
- `app/Console/Commands/AutoInactiveTeamMembers.php`
- `app/Notifications/InactivityWarningNotification.php`
- `app/Notifications/AccountDeactivatedNotification.php`

**Files Modified:**
- `app/Console/Kernel.php`
- `app/Models/User.php`
- `tests/Feature/TeamManagementTest.php`

**Security Considerations:**
- Stay-active token is single-use
- Token expires after 24 hours
- Admins exempt from auto-inactive
- All status changes logged

**Performance Considerations:**
- Efficient query with indexes
- Batch processing for large teams
- Email queue for notifications

**Notes:**
- Configurable thresholds in .env
- Weekly admin reports
- Reactivation requires admin action

---

## Task 4.6: Implement Bulk Operations for Team Members

**Priority:** 🟡 Medium  
**Estimated Time:** 3 hours  
**Dependencies:** Task 4.2, Task 4.3  
**Assigned To:** Backend Developer

**Objective:**
Implement bulk operations for team members including role changes, status updates, and deletion with proper validation and authorization.

**Detailed Steps:**

1. **Create BulkTeamOperationsRequest:**
   ```bash
   php artisan make:request BulkTeamOperationsRequest
   ```

2. **Implement BulkTeamOperationsRequest:**
   ```php
   <?php
   
   namespace App\Http\Requests;
   
   use Illuminate\Foundation\Http\FormRequest;
   use Illuminate\Validation\Rule;
   
   class BulkTeamOperationsRequest extends FormRequest
   {
       public function authorize(): bool
       {
           return $this->user()->canManageTeam();
       }
   
       public function rules(): array
       {
           return [
               'user_ids' => ['required', 'array', 'min:1'],
               'user_ids.*' => ['exists:users,id'],
               'action' => ['required', Rule::in(['change_role', 'change_status', 'delete'])],
               'role' => ['required_if:action,change_role', Rule::in(['admin', 'founder', 'hr', 'employee'])],
               'is_active' => ['required_if:action,change_status', 'boolean'],
               'new_manager_id' => ['nullable', 'exists:users,id'],
           ];
       }
   }
   ```

3. **Add bulkOperation method to TeamService:**
   ```php
   public function bulkChangeRole(array $userIds, string $role): array
   {
       $users = User::whereIn('id', $userIds)
           ->where('role', '!=', 'super_admin') // Exclude Super Admins
           ->get();
       
       $updated = [];
       $skipped = [];
       
       foreach ($users as $user) {
           if ($user->role === $role) {
               $skipped[] = $user->id;
               continue;
           }
           
           $user->update(['role' => $role]);
           $updated[] = $user->id;
       }
       
       return [
           'updated' => $updated,
           'skipped' => $skipped,
       ];
   }
   
   public function bulkChangeStatus(array $userIds, bool $isActive): array
   {
       $users = User::whereIn('id', $userIds)
           ->where('role', '!=', 'super_admin') // Exclude Super Admins
           ->get();
       
       $updated = [];
       $skipped = [];
       
       foreach ($users as $user) {
           if ($user->is_active === $isActive) {
               $skipped[] = $user->id;
               continue;
           }
           
           $user->update(['is_active' => $isActive]);
           $updated[] = $user->id;
       }
       
       return [
           'updated' => $updated,
           'skipped' => $skipped,
       ];
   }
   ```

4. **Implement bulkOperation method in TeamController:**
   ```php
   public function bulkOperation(BulkTeamOperationsRequest $request): JsonResponse
   {
       try {
           $result = match($request->action) {
               'change_role' => $this->teamService->bulkChangeRole(
                   $request->user_ids,
                   $request->role
               ),
               'change_status' => $this->teamService->bulkChangeStatus(
                   $request->user_ids,
                   $request->is_active
               ),
               'delete' => $this->teamService->bulkDelete(
                   $request->user_ids,
                   $request->new_manager_id
               ),
           };
   
           return response()->json([
               'success' => true,
               'data' => $result,
               'message' => 'Bulk operation completed successfully',
           ]);
       } catch (\Exception $e) {
           return response()->json([
               'success' => false,
               'message' => 'Bulk operation failed: ' . $e->getMessage(),
           ], 500);
       }
   }
   ```

5. **Add route:**
   ```php
   Route::middleware(['auth:sanctum', 'role:super_admin,admin'])->group(function () {
       Route::post('/team/bulk-operation', [TeamController::class, 'bulkOperation']);
   });
   ```

6. **Add tests:**
   ```php
   public function test_bulk_role_change(): void
   {
       $admin = User::factory()->admin()->create();
       $employees = User::factory()->count(5)->create(['role' => 'employee']);
       
       $response = $this->actingAs($admin, 'sanctum')
           ->postJson('/api/v1/team/bulk-operation', [
               'user_ids' => $employees->pluck('id')->toArray(),
               'action' => 'change_role',
               'role' => 'hr',
           ]);
       
       $response->assertStatus(200)
           ->assertJsonPath('data.updated', fn($updated) => count($updated) === 5);
       
       foreach ($employees as $employee) {
           $this->assertDatabaseHas('users', [
               'id' => $employee->id,
               'role' => 'hr',
           ]);
       }
   }
   
   public function test_bulk_operation_excludes_super_admins(): void
   {
       $admin = User::factory()->admin()->create();
       $superAdmin = User::factory()->superAdmin()->create();
       $employee = User::factory()->create(['role' => 'employee']);
       
       $response = $this->actingAs($admin, 'sanctum')
           ->postJson('/api/v1/team/bulk-operation', [
               'user_ids' => [$superAdmin->id, $employee->id],
               'action' => 'change_role',
               'role' => 'hr',
           ]);
       
       $response->assertStatus(200);
       
       $superAdmin->refresh();
       $this->assertEquals('super_admin', $superAdmin->role);
       
       $employee->refresh();
       $this->assertEquals('hr', $employee->role);
   }
   ```

**Acceptance Criteria:**
- [ ] Bulk role change works
- [ ] Bulk status change works
- [ ] Super Admins excluded from bulk operations
- [ ] Skipped users reported
- [ ] All operations logged
- [ ] Tests pass successfully

**Testing:**
```bash
# Test bulk role change
POST http://localhost:8000/api/v1/team/bulk-operation
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  "user_ids": [2, 3, 4, 5],
  "action": "change_role",
  "role": "hr"
}
```

**Files Created:**
- `app/Http/Requests/BulkTeamOperationsRequest.php`

**Files Modified:**
- `app/Services/TeamService.php`
- `app/Http/Controllers/Api/V1/TeamController.php`
- `tests/Feature/TeamManagementTest.php`

**Security Considerations:**
- Authorization checked
- Super Admins protected
- All operations logged
- Validation per user

**Performance Considerations:**
- Batch processing
- Efficient queries
- Progress tracking

**Notes:**
- Operations are atomic per user
- Failed operations don't rollback successful ones
- Detailed result reporting

