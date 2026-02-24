<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value_ar',
        'value_en',
        'type',
        'description',
    ];

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeByKey($query, string $key)
    {
        return $query->where('key', $key);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getValueAttribute()
    {
        // Return parsed value based on type
        return match($this->type) {
            'boolean' => (bool) $this->value_en,
            'json' => json_decode($this->value_en, true),
            default => $this->value_en,
        };
    }
}
