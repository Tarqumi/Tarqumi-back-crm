<?php

namespace App\Services;

use App\Models\ContactSubmission;
use App\Jobs\SendContactEmail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ContactService
{
    public function submitContactForm(array $data, string $ipAddress, string $userAgent): ContactSubmission
    {
        // Check rate limiting
        $this->checkRateLimit($ipAddress);

        // Create submission
        $submission = ContactSubmission::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'subject' => $data['subject'] ?? null,
            'message' => $data['message'],
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'language' => $data['language'],
            'status' => 'new',
        ]);

        // Queue email
        SendContactEmail::dispatch($submission);

        // Log submission
        Log::info('Contact form submitted', [
            'submission_id' => $submission->id,
            'email' => $submission->email,
            'ip' => $ipAddress,
        ]);

        return $submission;
    }

    protected function checkRateLimit(string $ipAddress): void
    {
        $key = 'contact_form_rate_limit:' . $ipAddress;
        $attempts = Cache::get($key, 0);
        $limit = config('contact.rate_limit', 5);

        if ($attempts >= $limit) {
            abort(429, 'Too many contact form submissions. Please wait a few minutes and try again.');
        }

        Cache::put($key, $attempts + 1, now()->addMinutes(config('contact.rate_limit_minutes', 1)));
    }

    public function getSubmissions(array $filters = [], int $perPage = 25)
    {
        $query = ContactSubmission::query();

        // Apply filters
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['language'])) {
            $query->where('language', $filters['language']);
        }

        if (!empty($filters['subject'])) {
            $query->where('subject', $filters['subject']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function updateStatus(ContactSubmission $submission, string $status): ContactSubmission
    {
        $submission->update(['status' => $status]);

        Log::info('Contact submission status updated', [
            'submission_id' => $submission->id,
            'old_status' => $submission->getOriginal('status'),
            'new_status' => $status,
        ]);

        return $submission;
    }

    public function bulkUpdateStatus(array $ids, string $status): int
    {
        $count = ContactSubmission::whereIn('id', $ids)->update(['status' => $status]);

        Log::info('Bulk status update', [
            'count' => $count,
            'status' => $status,
        ]);

        return $count;
    }

    public function bulkDelete(array $ids): int
    {
        $count = ContactSubmission::whereIn('id', $ids)->delete();

        Log::info('Bulk delete submissions', [
            'count' => $count,
        ]);

        return $count;
    }
}
