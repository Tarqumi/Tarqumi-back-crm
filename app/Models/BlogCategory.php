<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class BlogCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'slug_ar',
        'slug_en',
        'description_ar',
        'description_en',
        'parent_id',
        'order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'order' => 'integer',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Boot
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        static::creating(function (BlogCategory $category) {
            if (empty($category->slug_ar)) {
                $category->slug_ar = self::generateUniqueSlug($category->name_ar, 'ar');
            }
            if (empty($category->slug_en)) {
                $category->slug_en = self::generateUniqueSlug($category->name_en, 'en');
            }
        });

        static::updating(function (BlogCategory $category) {
            if ($category->isDirty('name_ar') && empty($category->slug_ar)) {
                $category->slug_ar = self::generateUniqueSlug($category->name_ar, 'ar');
            }
            if ($category->isDirty('name_en') && empty($category->slug_en)) {
                $category->slug_en = self::generateUniqueSlug($category->name_en, 'en');
            }
        });
    }

    private static function generateUniqueSlug(string $name, string $lang): string
    {
        $slug = Str::slug($name);
        $column = "slug_{$lang}";
        $count = 1;
        $originalSlug = $slug;

        while (self::where($column, $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
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

    public function scopeParent($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function parent(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(BlogCategory::class, 'parent_id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(BlogPost::class, 'category_id');
    }
}
