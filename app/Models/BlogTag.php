<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class BlogTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'slug_ar',
        'slug_en',
    ];

    /*
    |--------------------------------------------------------------------------
    | Boot
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        static::creating(function (BlogTag $tag) {
            if (empty($tag->slug_ar)) {
                $tag->slug_ar = self::generateUniqueSlug($tag->name_ar, 'ar');
            }
            if (empty($tag->slug_en)) {
                $tag->slug_en = self::generateUniqueSlug($tag->name_en, 'en');
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
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(BlogPost::class, 'blog_post_tag');
    }
}
