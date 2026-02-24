<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title_ar',
        'title_en',
        'slug_ar',
        'slug_en',
        'excerpt_ar',
        'excerpt_en',
        'content_ar',
        'content_en',
        'featured_image',
        'meta_title_ar',
        'meta_title_en',
        'meta_description_ar',
        'meta_description_en',
        'meta_keywords_ar',
        'meta_keywords_en',
        'category_id',
        'author_id',
        'status',
        'published_at',
        'scheduled_at',
        'views_count',
        'reading_time',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'scheduled_at' => 'datetime',
            'views_count' => 'integer',
            'reading_time' => 'integer',
            'is_featured' => 'boolean',
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

        static::creating(function (BlogPost $post) {
            if (empty($post->slug_ar)) {
                $post->slug_ar = self::generateUniqueSlug($post->title_ar, 'ar');
            }
            if (empty($post->slug_en)) {
                $post->slug_en = self::generateUniqueSlug($post->title_en, 'en');
            }
            
            // Calculate reading time
            if (!empty($post->content_en)) {
                $post->reading_time = self::calculateReadingTime($post->content_en);
            }
        });

        static::updating(function (BlogPost $post) {
            if ($post->isDirty('title_ar')) {
                $post->slug_ar = self::generateUniqueSlug($post->title_ar, 'ar', $post->id);
            }
            if ($post->isDirty('title_en')) {
                $post->slug_en = self::generateUniqueSlug($post->title_en, 'en', $post->id);
            }
            
            // Recalculate reading time if content changed
            if ($post->isDirty('content_en')) {
                $post->reading_time = self::calculateReadingTime($post->content_en);
            }
        });
    }

    private static function generateUniqueSlug(string $title, string $lang, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $column = "slug_{$lang}";
        $count = 1;
        $originalSlug = $slug;

        $query = self::where($column, $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
            $query = self::where($column, $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }

        return $slug;
    }

    private static function calculateReadingTime(string $content): int
    {
        $wordCount = str_word_count(strip_tags($content));
        $minutes = ceil($wordCount / 200); // Average reading speed: 200 words per minute
        return max(1, $minutes);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, int $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByAuthor($query, int $authorId)
    {
        return $query->where('author_id', $authorId);
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title_ar', 'like', "%{$search}%")
              ->orWhere('title_en', 'like', "%{$search}%")
              ->orWhere('excerpt_ar', 'like', "%{$search}%")
              ->orWhere('excerpt_en', 'like', "%{$search}%");
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(BlogTag::class, 'blog_post_tag');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getIsPublishedAttribute(): bool
    {
        return $this->status === 'published' 
            && $this->published_at 
            && $this->published_at->isPast();
    }

    public function getIsScheduledAttribute(): bool
    {
        return $this->status === 'scheduled' 
            && $this->scheduled_at 
            && $this->scheduled_at->isFuture();
    }

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}
