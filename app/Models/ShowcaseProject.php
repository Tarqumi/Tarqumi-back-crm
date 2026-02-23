<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShowcaseProject extends Model
{
    protected $fillable = [
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'url',
        'image',
        'is_live',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_live' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('created_at', 'desc');
    }
}
