<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageContent extends Model
{
    use HasFactory;

    protected $table = 'page_content';

    protected $fillable = [
        'page_slug',
        'section_key',
        'value_ar',
        'value_en',
        'image',
        'metadata',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeForPage($query, string $pageSlug)
    {
        return $query->where('page_slug', $pageSlug);
    }

    public function scopeForSection($query, string $pageSlug, string $sectionKey)
    {
        return $query->where('page_slug', $pageSlug)
                     ->where('section_key', $sectionKey);
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
