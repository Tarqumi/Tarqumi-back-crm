<?php

namespace App\Services;

use App\Models\ContactSubmission;
use App\Models\BlockedIp;
use App\Models\SpamPattern;
use App\Models\EmailRecipient;
use App\Models\EmailQueue;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ContactService
{
    public function submitContactForm(array $data): ContactSubmission
    {
        return DB::transaction(function () use ($data) {
            // Get IP and user agent
            $ipAddress = request()->ip();
            $userAgent = request()->userAgent();

            // Check if IP is blocked
            if (BlockedIp::isBlocked($ipAddress)) {
                throw new \Exception('Your IP address has been blocked due to suspicious activity.');
            }

            // Check for spam
            $spamScore = $this->calculateSpamScore($data, $ipAddress);
            $isSpam = $spamScore >= 5; // Threshold: 5 points

            // Create submission
            $submission = ContactSubmission::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'message' => $data['message'],
                'subject' => $data['subject'] ?? 'Contact Form Submission',
                'status' => $isSpam ? 'spam' : 'new',
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'submitted_at' => now(),
            ]);

            // If spam, increment spam count for IP
            if ($isSpam) {
                $this->handleSpamSubmission($ipAddress);
            } else {
                // Queue email notifications
                $this->queueEmailNotifications($submission);
            }

            return $submission;
        });
    }

    private function calculateSpamScore(array $data, string $ipAddress): int
    {
        $score = 0;
        $patterns = SpamPattern::active()->get();

        foreach ($patterns as $pattern) {
            switch ($pattern->type) {
                case 'keyword':
                    if (stripos($data['message'], $pattern->pattern) !== false) {
                        $score += $pattern->weight;
                    }
                    break;

                case 'email':
                    if (stripos($data['email'], $pattern->pattern) !== false) {
                        $score += $pattern->weight;
                    }
                    break;

                case 'url':
                    if (preg_match('/' . preg_quote($pattern->pattern, '/') . '/i', $data['message'])) {
                        $score += $pattern->weight;
                    }
                    break;

                case 'ip':
                    if ($ipAddress === $pattern->pattern) {
                        $score += $pattern->weight;
                    }
                    break;
            }
        }

        // Additional spam indicators
        $urlCount = preg_match_all('/https?:\/\//', $data['message']);
        if ($urlCount > 3) {
            $score += 2; // Multiple URLs
        }

        if (strlen($data['message']) < 10) {
            $score += 1; // Very short message
        }

        return $score;
    }

    private function handleSpamSubmission(string $ipAddress): void
    {
        $blockedIp = BlockedIp::firstOrNew(['ip_address' => $ipAddress]);
        $blockedIp->spam_count = ($blockedIp->spam_count ?? 0) + 1;
        $blockedIp->reason = 'spam';

        // Auto-block after 5 spam submissions
        if ($blockedIp->spam_count >= 5) {
            $blockedIp->blocked_at = now();
            $blockedIp->expires_at = now()->addDays(30); // Block for 30 days
        }

        $blockedIp->save();
    }

    private function queueEmailNotifications(ContactSubmission $submission): void
    {
        $recipients = EmailRecipient::active()->immediate()->get();

        foreach ($recipients as $recipient) {
            EmailQueue::create([
                'to_email' => $recipient->email,
                'to_name' => $recipient->name,
                'from_email' => config('mail.from.address'),
                'from_name' => config('mail.from.name'),
                'subject' => 'New Contact Form Submission',
                'body_html' => $this->generateEmailBody($submission),
                'body_text' => $this->generateEmailBodyText($submission),
                'status' => 'pending',
                'contact_submission_id' => $submission->id,
            ]);
        }
    }

    private function generateEmailBody(ContactSubmission $submission): string
    {
        return "
            <h2>New Contact Form Submission</h2>
            <p><strong>Name:</strong> {$submission->name}</p>
            <p><strong>Email:</strong> {$submission->email}</p>
            <p><strong>Phone:</strong> {$submission->phone}</p>
            <p><strong>Subject:</strong> {$submission->subject}</p>
            <p><strong>Message:</strong></p>
            <p>" . nl2br(htmlspecialchars($submission->message)) . "</p>
            <p><strong>Submitted at:</strong> {$submission->submitted_at->format('Y-m-d H:i:s')}</p>
            <p><strong>IP Address:</strong> {$submission->ip_address}</p>
        ";
    }

    private function generateEmailBodyText(ContactSubmission $submission): string
    {
        return "
New Contact Form Submission

Name: {$submission->name}
Email: {$submission->email}
Phone: {$submission->phone}
Subject: {$submission->subject}

Message:
{$submission->message}

Submitted at: {$submission->submitted_at->format('Y-m-d H:i:s')}
IP Address: {$submission->ip_address}
        ";
    }

    public function getSubmissions(array $filters): LengthAwarePaginator
    {
        $query = ContactSubmission::query();

        // Search
        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        // Filter by status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by date range
        if (!empty($filters['date_from'])) {
            $query->whereDate('submitted_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('submitted_at', '<=', $filters['date_to']);
        }

        // Exclude spam by default
        if (!isset($filters['include_spam']) || !$filters['include_spam']) {
            $query->notSpam();
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'submitted_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $filters['per_page'] ?? 20;
        
        return $query->with('reader')->paginate($perPage);
    }

    public function markAsSpam(ContactSubmission $submission): void
    {
        $submission->markAsSpam();
        
        // Increment spam count for IP
        if ($submission->ip_address) {
            $this->handleSpamSubmission($submission->ip_address);
        }
    }

    public function getStatistics(): array
    {
        return [
            'total' => ContactSubmission::count(),
            'new' => ContactSubmission::new()->count(),
            'read' => ContactSubmission::read()->count(),
            'replied' => ContactSubmission::where('status', 'replied')->count(),
            'spam' => ContactSubmission::spam()->count(),
            'today' => ContactSubmission::whereDate('submitted_at', today())->count(),
            'this_week' => ContactSubmission::whereBetween('submitted_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            'this_month' => ContactSubmission::whereMonth('submitted_at', now()->month)
                ->whereYear('submitted_at', now()->year)
                ->count(),
        ];
    }
}
