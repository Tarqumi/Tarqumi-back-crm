# Module 2: Database Design, Models & Migrations

## Overview
This module covers all database schema design, migrations, model creation, relationships, and seeders for the entire CRM system.

---

## Task 2.1: Create Users Table Migration with All Fields

**Priority:** 🔴 Critical  
**Estimated Time:** 2 hours  
**Dependencies:** Task 1.3  
**Assigned To:** Backend Developer

**Objective:**
Create a comprehensive users table migration with all required fields for team member management, including roles, status tracking, and soft deletes.

**Detailed Steps:**

1. **Create migration file:**
   ```bash
   php artisan make:migration create_comprehensive_users_table
   ```

2. **Update migration file `database/migrations/YYYY_MM_DD_create_comprehensive_users_table.php`:**
   ```php
   <?php
   
   use Illuminate\Database\Migrations\Migration;
   use Illuminate\Database\Schema\Blueprint;
   use Illuminate\Support\Facades\Schema;
   
   return new class extends Migration
   {
       public function up(): void
       {
           Schema::create('users', function (Blueprint $table) {
               $table->id();
               $table->string('name', 100);
               $table->string('email', 255)->unique();
               $table->timestamp('email_verified_at')->nullable();
               $table->string('password');
               $table->string('phone', 20)->nullable();
               $table->string('whatsapp', 20)->nullable();
               $table->string('department', 100)->nullable();
               $table->string('job_title', 100)->nullable();
               $table->string('profile_picture')->nullable();
               
               // Role management
               $table->enum('role', [
                   'super_admin',
                   'admin', 
                   'founder',
                   'hr',
                   'employee'
               ])->default('employee');
               
               $table->enum('founder_role', ['ceo', 'cto', 'cfo'])->nullable();
               
               // Status tracking
               $table->boolean('is_active')->default(true);
               $table->timestamp('last_login_at')->nullable();
               $table->timestamp('last_active_at')->nullable();
               $table->integer('inactive_days')->default(0);
               
               // Audit fields
               $table->rememberToken();
               $table->timestamps();
               $table->softDeletes();
               
               // Indexes for performance
               $table->index('email');
               $table->index('role');
               $table->index('is_active');
               $table->index('last_active_at');
               $table->index(['role', 'is_active']);
           });
       }
   
       public function down(): void
       {
           Schema::dropIfExists('users');
       }
   };
   ```

3. **Run the migration:**
   ```bash
   php artisan migrate
   ```

4. **Verify table structure:**
   ```bash
   php artisan db:show users
   ```

5. **Create User model `app/Models/User.php`:**
   ```php
   <?php
   
   namespace App\Models;
   
   use Illuminate\Database\Eloquent\Factories\HasFactory;
   use Illuminate\Database\Eloquent\SoftDeletes;
   use Illuminate\Foundation\Auth\User as Authenticatable;
   use Illuminate\Notifications\Notifiable;
   use Laravel\Sanctum\HasApiTokens;
   
   class User extends Authenticatable
   {
       use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
   
       protected $fillable = [
           'name',
           'email',
           'password',
           'phone',
           'whatsapp',
           'department',
           'job_title',
           'profile_picture',
           'role',
           'founder_role',
           'is_active',
           'last_login_at',
           'last_active_at',
           'inactive_days',
       ];
   
       protected $hidden = [
           'password',
           'remember_token',
       ];
   
       protected $casts = [
           'email_verified_at' => 'datetime',
           'last_login_at' => 'datetime',
           'last_active_at' => 'datetime',
           'is_active' => 'boolean',
           'password' => 'hashed',
       ];
   
       // Scopes
       public function scopeActive($query)
       {
           return $query->where('is_active', true);
       }
   
       public function scopeInactive($query)
       {
           return $query->where('is_active', false);
       }
   
       public function scopeByRole($query, string $role)
       {
           return $query->where('role', $role);
       }
   
       public function scopeAdmins($query)
       {
           return $query->whereIn('role', ['super_admin', 'admin']);
       }
   
       public function scopeFounders($query)
       {
           return $query->where('role', 'founder');
       }
   
       // Accessors
       public function getIsFounderAttribute(): bool
       {
           return $this->role === 'founder';
       }
   
       public function getIsSuperAdminAttribute(): bool
       {
           return $this->role === 'super_admin';
       }
   
       public function getIsAdminAttribute(): bool
       {
           return in_array($this->role, ['super_admin', 'admin']);
       }
   
       public function getCanEditLandingPageAttribute(): bool
       {
           return $this->is_admin || 
                  ($this->is_founder && $this->founder_role === 'cto');
       }
   
       public function getFullPhoneAttribute(): string
       {
           return $this->phone ?? 'N/A';
       }
   
       // Relationships
       public function managedProjects()
       {
           return $this->hasMany(Project::class, 'manager_id');
       }
   
       public function createdClients()
       {
           return $this->hasMany(Client::class, 'created_by');
       }
   
       public function createdProjects()
       {
           return $this->hasMany(Project::class, 'created_by');
       }
   }
   ```

6. **Create Role enum `app/Enums/UserRole.php`:**
   ```php
   <?php
   
   namespace App\Enums;
   
   enum UserRole: string
   {
       case SUPER_ADMIN = 'super_admin';
       case ADMIN = 'admin';
       case FOUNDER = 'founder';
       case HR = 'hr';
       case EMPLOYEE = 'employee';
   
       public function label(): string
       {
           return match($this) {
               self::SUPER_ADMIN => 'Super Admin',
               self::ADMIN => 'Admin',
               self::FOUNDER => 'Founder',
               self::HR => 'HR',
               self::EMPLOYEE => 'Employee',
           };
       }
   
       public function canEditLandingPage(): bool
       {
           return in_array($this, [self::SUPER_ADMIN, self::ADMIN]);
       }
   
       public function canManageTeam(): bool
       {
           return in_array($this, [self::SUPER_ADMIN, self::ADMIN, self::HR]);
       }
   
       public function canManageClients(): bool
       {
           return in_array($this, [self::SUPER_ADMIN, self::ADMIN]);
       }
   
       public function canManageProjects(): bool
       {
           return in_array($this, [self::SUPER_ADMIN, self::ADMIN]);
       }
   }
   ```

7. **Create FounderRole enum `app/Enums/FounderRole.php`:**
   ```php
   <?php
   
   namespace App\Enums;
   
   enum FounderRole: string
   {
       case CEO = 'ceo';
       case CTO = 'cto';
       case CFO = 'cfo';
   
       public function label(): string
       {
           return match($this) {
               self::CEO => 'CEO',
               self::CTO => 'CTO',
               self::CFO => 'CFO',
           };
       }
   
       public function canEditLandingPage(): bool
       {
           return $this === self::CTO;
       }
   }
   ```

8. **Create User factory `database/factories/UserFactory.php`:**
   ```php
   <?php
   
   namespace Database\Factories;
   
   use App\Models\User;
   use Illuminate\Database\Eloquent\Factories\Factory;
   use Illuminate\Support\Facades\Hash;
   use Illuminate\Support\Str;
   
   class UserFactory extends Factory
   {
       protected static ?string $password;
   
       public function definition(): array
       {
           return [
               'name' => fake()->name(),
               'email' => fake()->unique()->safeEmail(),
               'email_verified_at' => now(),
               'password' => static::$password ??= Hash::make('password'),
               'phone' => fake()->phoneNumber(),
               'whatsapp' => fake()->phoneNumber(),
               'department' => fake()->randomElement([
                   'Engineering', 'Sales', 'Marketing', 'HR', 'Finance'
               ]),
               'job_title' => fake()->jobTitle(),
               'role' => 'employee',
               'is_active' => true,
               'last_active_at' => now(),
               'remember_token' => Str::random(10),
           ];
       }
   
       public function superAdmin(): static
       {
           return $this->state(fn (array $attributes) => [
               'role' => 'super_admin',
           ]);
       }
   
       public function admin(): static
       {
           return $this->state(fn (array $attributes) => [
               'role' => 'admin',
           ]);
       }
   
       public function founder(string $founderRole = 'ceo'): static
       {
           return $this->state(fn (array $attributes) => [
               'role' => 'founder',
               'founder_role' => $founderRole,
           ]);
       }
   
       public function hr(): static
       {
           return $this->state(fn (array $attributes) => [
               'role' => 'hr',
           ]);
       }
   
       public function inactive(): static
       {
           return $this->state(fn (array $attributes) => [
               'is_active' => false,
               'inactive_days' => 30,
           ]);
       }
   }
   ```

9. **Update AdminSeeder to use new structure:**
   ```php
   <?php
   
   namespace Database\Seeders;
   
   use App\Models\User;
   use Illuminate\Database\Seeder;
   use Illuminate\Support\Facades\Hash;
   
   class AdminSeeder extends Seeder
   {
       public function run(): void
       {
           // Create Super Admin
           User::create([
               'name' => 'Super Admin',
               'email' => 'admin@tarqumi.com',
               'password' => Hash::make('password'),
               'role' => 'super_admin',
               'is_active' => true,
               'email_verified_at' => now(),
               'last_active_at' => now(),
           ]);
   
           // Create CTO Founder
           User::create([
               'name' => 'CTO Founder',
               'email' => 'cto@tarqumi.com',
               'password' => Hash::make('password'),
               'role' => 'founder',
               'founder_role' => 'cto',
               'is_active' => true,
               'email_verified_at' => now(),
               'last_active_at' => now(),
           ]);
   
           // Create CEO Founder
           User::create([
               'name' => 'CEO Founder',
               'email' => 'ceo@tarqumi.com',
               'password' => Hash::make('password'),
               'role' => 'founder',
               'founder_role' => 'ceo',
               'is_active' => true,
               'email_verified_at' => now(),
               'last_active_at' => now(),
           ]);
       }
   }
   ```

10. **Run seeder:**
    ```bash
    php artisan db:seed --class=AdminSeeder
    ```

11. **Test model in Tinker:**
    ```bash
    php artisan tinker
    >>> User::count()
    >>> User::where('role', 'super_admin')->first()
    >>> User::active()->count()
    >>> User::admins()->get()
    >>> exit
    ```

**Acceptance Criteria:**
- [ ] Users table created with all fields
- [ ] Proper indexes on searchable columns
- [ ] Soft deletes enabled
- [ ] User model created with relationships
- [ ] Scopes defined for common queries
- [ ] Accessors for computed properties
- [ ] Role and FounderRole enums created
- [ ] User factory created with states
- [ ] AdminSeeder updated and working
- [ ] All test users seeded successfully
- [ ] Model queries work in Tinker

**Testing:**
```bash
# Run migration
php artisan migrate:fresh

# Seed database
php artisan db:seed

# Test in Tinker
php artisan tinker
>>> User::all()
>>> User::where('role', 'super_admin')->first()->can_edit_landing_page
>>> User::active()->count()
```

**Files Created:**
- `database/migrations/YYYY_MM_DD_create_comprehensive_users_table.php`
- `app/Models/User.php`
- `app/Enums/UserRole.php`
- `app/Enums/FounderRole.php`
- `database/factories/UserFactory.php`
- `database/seeders/AdminSeeder.php` (updated)

**Security Considerations:**
- Passwords hashed with bcrypt
- Soft deletes preserve audit trail
- Email field unique and indexed
- Role-based access control ready

**Performance Considerations:**
- Indexes on frequently queried columns
- Composite index on role and is_active
- Soft deletes don't impact active queries

**Notes:**
- Change default passwords after deployment
- Inactive days calculated by observer
- Last active updated by middleware
- Profile pictures stored in storage/app/public

---

## Task 2.2: Create Clients Table Migration

**Priority:** 🔴 Critical  
**Estimated Time:** 2 hours  
**Dependencies:** Task 2.1  
**Assigned To:** Backend Developer

**Objective:**
Create clients table with all required fields for client management, including contact information, status tracking, and relationships.

**Detailed Steps:**

1. **Create migration:**
   ```bash
   php artisan make:migration create_clients_table
   ```

2. **Update migration file:**
   ```php
   <?php
   
   use Illuminate\Database\Migrations\Migration;
   use Illuminate\Database\Schema\Blueprint;
   use Illuminate\Support\Facades\Schema;
   
   return new class extends Migration
   {
       public function up(): void
       {
           Schema::create('clients', function (Blueprint $table) {
               $table->id();
               $table->string('name', 100);
               $table->string('company_name', 150)->nullable();
               $table->string('email', 255)->unique();
               $table->string('phone', 20)->nullable();
               $table->string('whatsapp', 20)->nullable();
               $table->text('address')->nullable();
               $table->string('website', 255)->nullable();
               $table->string('industry', 100)->nullable();
               $table->text('notes')->nullable();
               
               // Status
               $table->boolean('is_active')->default(true);
               $table->boolean('is_default')->default(false);
               
               // Audit fields
               $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
               $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
               $table->timestamps();
               $table->softDeletes();
               
               // Indexes
               $table->index('email');
               $table->index('name');
               $table->index('company_name');
               $table->index('is_active');
               $table->index('is_default');
               $table->index('created_at');
           });
       }
   
       public function down(): void
       {
           Schema::dropIfExists('clients');
       }
   };
   ```

3. **Run migration:**
   ```bash
   php artisan migrate
   ```

4. **Create Client model `app/Models/Client.php`:**
   ```php
   <?php
   
   namespace App\Models;
   
   use Illuminate\Database\Eloquent\Factories\HasFactory;
   use Illuminate\Database\Eloquent\Model;
   use Illuminate\Database\Eloquent\SoftDeletes;
   
   class Client extends Model
   {
       use HasFactory, SoftDeletes;
   
       protected $fillable = [
           'name',
           'company_name',
           'email',
           'phone',
           'whatsapp',
           'address',
           'website',
           'industry',
           'notes',
           'is_active',
           'is_default',
           'created_by',
           'updated_by',
       ];
   
       protected $casts = [
           'is_active' => 'boolean',
           'is_default' => 'boolean',
       ];
   
       // Scopes
       public function scopeActive($query)
       {
           return $query->where('is_active', true);
       }
   
       public function scopeInactive($query)
       {
           return $query->where('is_active', false);
       }
   
       public function scopeDefault($query)
       {
           return $query->where('is_default', true);
       }
   
       public function scopeSearch($query, string $search)
       {
           return $query->where(function ($q) use ($search) {
               $q->where('name', 'like', "%{$search}%")
                 ->orWhere('company_name', 'like', "%{$search}%")
                 ->orWhere('email', 'like', "%{$search}%");
           });
       }
   
       // Relationships
       public function projects()
       {
           return $this->belongsToMany(Project::class, 'client_project');
       }
   
       public function creator()
       {
           return $this->belongsTo(User::class, 'created_by');
       }
   
       public function updater()
       {
           return $this->belongsTo(User::class, 'updated_by');
       }
   
       // Accessors
       public function getDisplayNameAttribute(): string
       {
           return $this->company_name ?? $this->name;
       }
   
       public function getProjectsCountAttribute(): int
       {
           return $this->projects()->count();
       }
   
       // Methods
       public function canBeDeleted(): bool
       {
           return !$this->is_default;
       }
   }
   ```

5. **Create Client factory:**
   ```php
   <?php
   
   namespace Database\Factories;
   
   use App\Models\Client;
   use Illuminate\Database\Eloquent\Factories\Factory;
   
   class ClientFactory extends Factory
   {
       public function definition(): array
       {
           return [
               'name' => fake()->name(),
               'company_name' => fake()->company(),
               'email' => fake()->unique()->companyEmail(),
               'phone' => fake()->phoneNumber(),
               'whatsapp' => fake()->phoneNumber(),
               'address' => fake()->address(),
               'website' => fake()->url(),
               'industry' => fake()->randomElement([
                   'Technology', 'Finance', 'Healthcare', 
                   'Retail', 'Manufacturing', 'Education'
               ]),
               'notes' => fake()->paragraph(),
               'is_active' => true,
               'is_default' => false,
           ];
       }
   
       public function default(): static
       {
           return $this->state(fn (array $attributes) => [
               'name' => 'Tarqumi',
               'company_name' => 'Tarqumi',
               'email' => 'info@tarqumi.com',
               'is_default' => true,
           ]);
       }
   
       public function inactive(): static
       {
           return $this->state(fn (array $attributes) => [
               'is_active' => false,
           ]);
       }
   }
   ```

6. **Create DefaultClientSeeder:**
   ```php
   <?php
   
   namespace Database\Seeders;
   
   use App\Models\Client;
   use Illuminate\Database\Seeder;
   
   class DefaultClientSeeder extends Seeder
   {
       public function run(): void
       {
           Client::create([
               'name' => 'Tarqumi',
               'company_name' => 'Tarqumi',
               'email' => 'info@tarqumi.com',
               'phone' => '+966 XX XXX XXXX',
               'website' => 'https://tarqumi.com',
               'industry' => 'Technology',
               'notes' => 'Default client for internal projects',
               'is_active' => true,
               'is_default' => true,
           ]);
       }
   }
   ```

7. **Update DatabaseSeeder:**
   ```php
   public function run(): void
   {
       $this->call([
           AdminSeeder::class,
           DefaultClientSeeder::class,
       ]);
   }
   ```

8. **Run seeder:**
   ```bash
   php artisan db:seed --class=DefaultClientSeeder
   ```

9. **Create Client policy:**
   ```bash
   php artisan make:policy ClientPolicy --model=Client
   ```

10. **Update ClientPolicy:**
    ```php
    <?php
    
    namespace App\Policies;
    
    use App\Models\Client;
    use App\Models\User;
    
    class ClientPolicy
    {
        public function viewAny(User $user): bool
        {
            return in_array($user->role, ['super_admin', 'admin', 'founder']);
        }
    
        public function view(User $user, Client $client): bool
        {
            return in_array($user->role, ['super_admin', 'admin', 'founder']);
        }
    
        public function create(User $user): bool
        {
            return in_array($user->role, ['super_admin', 'admin']);
        }
    
        public function update(User $user, Client $client): bool
        {
            if ($client->is_default) {
                return false; // Default client cannot be fully edited
            }
            return in_array($user->role, ['super_admin', 'admin']);
        }
    
        public function delete(User $user, Client $client): bool
        {
            if ($client->is_default) {
                return false; // Default client cannot be deleted
            }
            return in_array($user->role, ['super_admin', 'admin']);
        }
    
        public function restore(User $user, Client $client): bool
        {
            return in_array($user->role, ['super_admin', 'admin']);
        }
    
        public function forceDelete(User $user, Client $client): bool
        {
            return $user->role === 'super_admin' && !$client->is_default;
        }
    }
    ```

**Acceptance Criteria:**
- [ ] Clients table created with all fields
- [ ] Proper indexes on searchable columns
- [ ] Soft deletes enabled
- [ ] Client model with relationships
- [ ] Scopes for common queries
- [ ] Client factory with states
- [ ] Default client seeder created
- [ ] Default "Tarqumi" client seeded
- [ ] Client policy created
- [ ] Default client protected from deletion

**Testing:**
```bash
# Test migration
php artisan migrate:fresh --seed

# Test in Tinker
php artisan tinker
>>> Client::count()
>>> Client::default()->first()
>>> Client::active()->get()
>>> $client = Client::first()
>>> $client->canBeDeleted()
```

**Files Created:**
- `database/migrations/YYYY_MM_DD_create_clients_table.php`
- `app/Models/Client.php`
- `database/factories/ClientFactory.php`
- `database/seeders/DefaultClientSeeder.php`
- `app/Policies/ClientPolicy.php`

**Security Considerations:**
- Default client cannot be deleted
- Email field unique
- Soft deletes preserve history
- Policy enforces permissions

**Performance Considerations:**
- Indexes on name, email, company
- Soft delete queries optimized
- Relationships eager loadable

**Notes:**
- Default client always available
- Projects keep client reference after deletion
- Audit fields track who created/updated

---



## Task 2.3: Create Projects Table Migration with Multiple Clients Support

**Priority:** 🔴 Critical  
**Estimated Time:** 3 hours  
**Dependencies:** Task 2.2  
**Assigned To:** Backend Developer

**Objective:**
Create projects table with support for multiple clients per project, 6 SDLC phases, budget tracking, and comprehensive project management fields.

**Detailed Steps:**

1. **Create migration:**
   ```bash
   php artisan make:migration create_projects_table
   ```

2. **Update migration file:**
   ```php
   <?php
   
   use Illuminate\Database\Migrations\Migration;
   use Illuminate\Database\Schema\Blueprint;
   use Illuminate\Support\Facades\Schema;
   
   return new class extends Migration
   {
       public function up(): void
       {
           Schema::create('projects', function (Blueprint $table) {
               $table->id();
               $table->string('code', 50)->unique(); // PROJ-2024-0001
               $table->string('name', 150);
               $table->text('description')->nullable();
               
               // Project Manager
               $table->foreignId('manager_id')->constrained('users')->onDelete('restrict');
               
               // Budget
               $table->decimal('budget', 15, 2)->default(0);
               $table->string('currency', 3)->default('SAR'); // USD, EUR, SAR, AED, EGP
               
               // Priority (1-10 scale)
               $table->tinyInteger('priority')->default(5);
               
               // Dates
               $table->date('start_date');
               $table->date('end_date')->nullable();
               $table->integer('estimated_hours')->nullable();
               
               // Status (6 SDLC Phases)
               $table->enum('status', [
                   'planning',
                   'analysis', 
                   'design',
                   'implementation',
                   'testing',
                   'deployment'
               ])->default('planning');
               
               // Active Status
               $table->boolean('is_active')->default(true);
               
               // Audit fields
               $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
               $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
               $table->timestamps();
               $table->softDeletes();
               
               // Indexes
               $table->index('code');
               $table->index('name');
               $table->index('manager_id');
               $table->index('status');
               $table->index('is_active');
               $table->index('start_date');
               $table->index('end_date');
               $table->index(['status', 'is_active']);
               $table->index('created_at');
           });
       }
   
       public function down(): void
       {
           Schema::dropIfExists('projects');
       }
   };
   ```

3. **Create client_project pivot table:**
   ```bash
   php artisan make:migration create_client_project_table
   ```

4. **Update pivot migration:**
   ```php
   <?php
   
   use Illuminate\Database\Migrations\Migration;
   use Illuminate\Database\Schema\Blueprint;
   use Illuminate\Support\Facades\Schema;
   
   return new class extends Migration
   {
       public function up(): void
       {
           Schema::create('client_project', function (Blueprint $table) {
               $table->id();
               $table->foreignId('client_id')->constrained()->onDelete('cascade');
               $table->foreignId('project_id')->constrained()->onDelete('cascade');
               $table->boolean('is_primary')->default(false);
               $table->timestamps();
               
               // Unique constraint
               $table->unique(['client_id', 'project_id']);
               
               // Indexes
               $table->index('client_id');
               $table->index('project_id');
           });
       }
   
       public function down(): void
       {
           Schema::dropIfExists('client_project');
       }
   };
   ```

5. **Run migrations:**
   ```bash
   php artisan migrate
   ```

6. **Create Project model:**
   ```bash
   php artisan make:model Project
   ```

7. **Update Project model:**
   ```php
   <?php
   
   namespace App\Models;
   
   use Illuminate\Database\Eloquent\Factories\HasFactory;
   use Illuminate\Database\Eloquent\Model;
   use Illuminate\Database\Eloquent\SoftDeletes;
   
   class Project extends Model
   {
       use HasFactory, SoftDeletes;
   
       protected $fillable = [
           'code',
           'name',
           'description',
           'manager_id',
           'budget',
           'currency',
           'priority',
           'start_date',
           'end_date',
           'estimated_hours',
           'status',
           'is_active',
           'created_by',
           'updated_by',
       ];
   
       protected $casts = [
           'budget' => 'decimal:2',
           'priority' => 'integer',
           'start_date' => 'date',
           'end_date' => 'date',
           'is_active' => 'boolean',
       ];
   
       // Boot method for auto-generating project code
       protected static function boot()
       {
           parent::boot();
           
           static::creating(function ($project) {
               if (empty($project->code)) {
                   $year = date('Y');
                   $lastProject = static::whereYear('created_at', $year)
                       ->orderBy('id', 'desc')
                       ->first();
                   
                   $number = $lastProject ? intval(substr($lastProject->code, -4)) + 1 : 1;
                   $project->code = sprintf('PROJ-%s-%04d', $year, $number);
               }
           });
       }
   
       // Scopes
       public function scopeActive($query)
       {
           return $query->where('is_active', true);
       }
   
       public function scopeInactive($query)
       {
           return $query->where('is_active', false);
       }
   
       public function scopeByStatus($query, string $status)
       {
           return $query->where('status', $status);
       }
   
       public function scopeByPriority($query, int $min, int $max = 10)
       {
           return $query->whereBetween('priority', [$min, $max]);
       }
   
       public function scopeOverdue($query)
       {
           return $query->where('end_date', '<', now())
               ->whereNotIn('status', ['deployment']);
       }
   
       // Relationships
       public function clients()
       {
           return $this->belongsToMany(Client::class, 'client_project')
               ->withPivot('is_primary')
               ->withTimestamps();
       }
   
       public function primaryClient()
       {
           return $this->belongsToMany(Client::class, 'client_project')
               ->wherePivot('is_primary', true)
               ->withTimestamps();
       }
   
       public function manager()
       {
           return $this->belongsTo(User::class, 'manager_id');
       }
   
       public function creator()
       {
           return $this->belongsTo(User::class, 'created_by');
       }
   
       public function updater()
       {
           return $this->belongsTo(User::class, 'updated_by');
       }
   
       // Accessors
       public function getIsOverdueAttribute(): bool
       {
           return $this->end_date && 
                  $this->end_date->isPast() && 
                  $this->status !== 'deployment';
       }
   
       public function getDaysRemainingAttribute(): ?int
       {
           if (!$this->end_date) {
               return null;
           }
           
           return now()->diffInDays($this->end_date, false);
       }
   
       public function getProgressPercentageAttribute(): int
       {
           $statusProgress = [
               'planning' => 10,
               'analysis' => 25,
               'design' => 40,
               'implementation' => 60,
               'testing' => 80,
               'deployment' => 100,
           ];
           
           return $statusProgress[$this->status] ?? 0;
       }
   }
   ```

8. **Create ProjectStatus enum:**
   ```bash
   mkdir -p app/Enums
   touch app/Enums/ProjectStatus.php
   ```

9. **Implement ProjectStatus enum:**
   ```php
   <?php
   
   namespace App\Enums;
   
   enum ProjectStatus: string
   {
       case PLANNING = 'planning';
       case ANALYSIS = 'analysis';
       case DESIGN = 'design';
       case IMPLEMENTATION = 'implementation';
       case TESTING = 'testing';
       case DEPLOYMENT = 'deployment';
   
       public function label(): string
       {
           return match($this) {
               self::PLANNING => 'Planning',
               self::ANALYSIS => 'Analysis',
               self::DESIGN => 'Design',
               self::IMPLEMENTATION => 'Implementation',
               self::TESTING => 'Testing',
               self::DEPLOYMENT => 'Deployment',
           };
       }
   
       public function color(): string
       {
           return match($this) {
               self::PLANNING => 'blue',
               self::ANALYSIS => 'purple',
               self::DESIGN => 'indigo',
               self::IMPLEMENTATION => 'orange',
               self::TESTING => 'yellow',
               self::DEPLOYMENT => 'green',
           };
       }
   
       public function progress(): int
       {
           return match($this) {
               self::PLANNING => 10,
               self::ANALYSIS => 25,
               self::DESIGN => 40,
               self::IMPLEMENTATION => 60,
               self::TESTING => 80,
               self::DEPLOYMENT => 100,
           };
       }
   }
   ```

10. **Create Project factory:**
    ```bash
    php artisan make:factory ProjectFactory
    ```

11. **Implement Project factory:**
    ```php
    <?php
    
    namespace Database\Factories;
    
    use App\Models\Project;
    use App\Models\User;
    use Illuminate\Database\Eloquent\Factories\Factory;
    
    class ProjectFactory extends Factory
    {
        public function definition(): array
        {
            return [
                'name' => fake()->sentence(3),
                'description' => fake()->paragraph(),
                'manager_id' => User::factory(),
                'budget' => fake()->randomFloat(2, 10000, 500000),
                'currency' => fake()->randomElement(['USD', 'EUR', 'SAR', 'AED', 'EGP']),
                'priority' => fake()->numberBetween(1, 10),
                'start_date' => fake()->dateTimeBetween('-6 months', 'now'),
                'end_date' => fake()->dateTimeBetween('now', '+6 months'),
                'estimated_hours' => fake()->numberBetween(100, 2000),
                'status' => fake()->randomElement([
                    'planning', 'analysis', 'design', 
                    'implementation', 'testing', 'deployment'
                ]),
                'is_active' => true,
            ];
        }
    
        public function inactive(): static
        {
            return $this->state(fn (array $attributes) => [
                'is_active' => false,
            ]);
        }
    
        public function overdue(): static
        {
            return $this->state(fn (array $attributes) => [
                'end_date' => fake()->dateTimeBetween('-3 months', '-1 day'),
                'status' => 'implementation',
            ]);
        }
    }
    ```

12. **Create ProjectSeeder:**
    ```bash
    php artisan make:seeder ProjectSeeder
    ```

13. **Implement ProjectSeeder:**
    ```php
    <?php
    
    namespace Database\Seeders;
    
    use App\Models\Client;
    use App\Models\Project;
    use App\Models\User;
    use Illuminate\Database\Seeder;
    
    class ProjectSeeder extends Seeder
    {
        public function run(): void
        {
            $defaultClient = Client::where('is_default', true)->first();
            $admin = User::where('role', 'super_admin')->first();
            
            // Create sample projects
            $projects = Project::factory()->count(10)->create([
                'manager_id' => $admin->id,
                'created_by' => $admin->id,
            ]);
            
            // Attach default client to all projects
            foreach ($projects as $project) {
                $project->clients()->attach($defaultClient->id, ['is_primary' => true]);
            }
        }
    }
    ```

14. **Update DatabaseSeeder:**
    ```php
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            DefaultClientSeeder::class,
            ProjectSeeder::class,
        ]);
    }
    ```

15. **Run seeders:**
    ```bash
    php artisan db:seed
    ```

**Acceptance Criteria:**
- [ ] Projects table created with all fields
- [ ] Client-project pivot table created
- [ ] Project code auto-generated (PROJ-YYYY-####)
- [ ] Multiple clients can be attached to project
- [ ] Primary client designation works
- [ ] 6 SDLC status phases implemented
- [ ] Priority scale 1-10 implemented
- [ ] Budget with currency support
- [ ] Project model with relationships
- [ ] Scopes for common queries
- [ ] Accessors for computed properties
- [ ] ProjectStatus enum created
- [ ] Project factory created
- [ ] Sample projects seeded
- [ ] All tests pass

**Testing:**
```bash
# Test in Tinker
php artisan tinker
>>> Project::count()
>>> $project = Project::first()
>>> $project->clients
>>> $project->manager
>>> $project->is_overdue
>>> $project->days_remaining
>>> $project->progress_percentage
```

**Files Created:**
- `database/migrations/YYYY_MM_DD_create_projects_table.php`
- `database/migrations/YYYY_MM_DD_create_client_project_table.php`
- `app/Models/Project.php`
- `app/Enums/ProjectStatus.php`
- `database/factories/ProjectFactory.php`
- `database/seeders/ProjectSeeder.php`

**Security Considerations:**
- Project manager cannot be deleted if managing projects
- Soft deletes preserve project history
- Audit fields track who created/updated
- Budget values encrypted at rest

**Performance Considerations:**
- Indexes on frequently queried columns
- Composite indexes for common query patterns
- Eager loading prevents N+1 queries
- Project code generation optimized

**Notes:**
- Default client "Tarqumi" attached to all projects
- Projects can have multiple clients
- Primary client designation for main client
- Status progress calculated automatically
- Overdue projects flagged automatically

---

