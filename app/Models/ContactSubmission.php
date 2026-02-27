<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactSubmission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'ip_address',
        'user_agent',
        'status',
        'language',
        'email_sent_at',
    ];

    protected $casts = [
        'email_sent_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'new',
        'language' => 'en',
    ];

    // Scopes
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }

    public function scopeReplied($query)
    {
        return $query->where('status', 'replied');
    }

    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    public function scopeSpam($query)
    {
        return $query->where('status', 'spam');
    }

    public function scopeNotSpam($query)
    {
        return $query->where('status', '!=', 'spam');
    }

    // Accessors
    public function getIsNewAttribute(): bool
    {
        return $this->status === 'new';
    }

    public function getIsReadAttribute(): bool
    {
        return $this->status === 'read';
    }

    public function getMessagePreviewAttribute(): string
    {
        return substr($this->message, 0, 100) . (strlen($this->message) > 100 ? '...' : '');
    }
}
