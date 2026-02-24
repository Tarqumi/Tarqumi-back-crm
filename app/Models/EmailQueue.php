<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailQueue extends Model
{
    use HasFactory;

    protected $table = 'email_queue';

    protected $fillable = [
        'to_email',
        'to_name',
        'from_email',
        'from_name',
        'subject',
        'body_html',
        'body_text',
        'attachments',
        'status',
        'attempts',
        'max_attempts',
        'scheduled_at',
        'sent_at',
        'failed_at',
        'error_message',
        'contact_submission_id',
    ];

    protected function casts(): array
    {
        return [
            'attachments' => 'array',
            'scheduled_at' => 'datetime',
            'sent_at' => 'datetime',
            'failed_at' => 'datetime',
            'attempts' => 'integer',
            'max_attempts' => 'integer',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeReadyToSend($query)
    {
        return $query->where('status', 'pending')
                     ->where(function ($q) {
                         $q->whereNull('scheduled_at')
                           ->orWhere('scheduled_at', '<=', now());
                     })
                     ->where('attempts', '<', 'max_attempts');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function contactSubmission(): BelongsTo
    {
        return $this->belongsTo(ContactSubmission::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public function markAsSent(): void
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    public function markAsFailed(string $errorMessage): void
    {
        $this->update([
            'status' => 'failed',
            'failed_at' => now(),
            'error_message' => $errorMessage,
        ]);
    }

    public function incrementAttempts(): void
    {
        $this->increment('attempts');
    }

    public function canRetry(): bool
    {
        return $this->attempts < $this->max_attempts;
    }
}
