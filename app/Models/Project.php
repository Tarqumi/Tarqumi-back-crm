<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'budget' => 'decimal:2',
            'priority' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
            'estimated_hours' => 'decimal:2',
            'is_active' => 'boolean',
            'status' => ProjectStatus::class,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Boot — Auto-generate project code
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Project $project) {
            if (empty($project->code)) {
                $project->code = self::generateProjectCode();
            }
        });
    }

    /**
     * Generate unique project code: PROJ-YYYY-0001
     */
    private static function generateProjectCode(): string
    {
        $year = date('Y');
        $prefix = "PROJ-{$year}-";

        $lastProject = self::withTrashed()
            ->where('code', 'like', "{$prefix}%")
            ->orderBy('code', 'desc')
            ->first();

        if ($lastProject) {
            $lastNumber = (int) substr($lastProject->code, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

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

    public function scopeByPriority($query, int $minPriority)
    {
        return $query->where('priority', '>=', $minPriority);
    }

    public function scopeOverdue($query)
    {
        return $query->where('end_date', '<', now())
                     ->where('status', '!=', ProjectStatus::DEPLOYMENT->value);
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'client_project')
                    ->withPivot('is_primary')
                    ->withTimestamps();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getIsOverdueAttribute(): bool
    {
        return $this->end_date
            && $this->end_date->isPast()
            && $this->status !== ProjectStatus::DEPLOYMENT;
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status?->label() ?? 'Unknown';
    }

    public function getStatusPercentageAttribute(): int
    {
        return $this->status?->percentage() ?? 0;
    }

    /**
     * Get the primary client for this project.
     */
    public function getPrimaryClientAttribute(): ?Client
    {
        return $this->clients()
            ->wherePivot('is_primary', true)
            ->first();
    }
}
