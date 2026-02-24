<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContactSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'subject',
        'status',
        'ip_address',
        'user_agent',
        'submitted_at',
        'read_at',
        'read_by',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'read_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }

    public function scopeSpam($query)
    {
        return $query->where('status', 'spam');
    }

    public function scopeNotSpam($query)
    {
        return $query->where('status', '!=', 'spam');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('submitted_at', 'desc');
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('message', 'like', "%{$search}%")
              ->orWhere('subject', 'like', "%{$search}%");
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function reader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'read_by');
    }

    public function emailQueue(): HasMany
    {
        return $this->hasMany(EmailQueue::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public function markAsRead(?int $userId = null): void
    {
        $this->update([
            'status' => 'read',
            'read_at' => now(),
            'read_by' => $userId,
        ]);
    }

    public function markAsSpam(): void
    {
        $this->update(['status' => 'spam']);
    }

    public function markAsReplied(): void
    {
        $this->update(['status' => 'replied']);
    }

    public function archive(): void
    {
        $this->update(['status' => 'archived']);
    }
}
