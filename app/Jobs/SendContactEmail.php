<?php

namespace App\Jobs;

use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendContactEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    public $backoff = [60, 300, 900, 3600, 21600]; // 1min, 5min, 15min, 1hr, 6hr

    public function __construct(
        public ContactSubmission $submission
    ) {}

    public function handle(): void
    {
        try {
            // Get recipient email from config
            $recipientEmail = config('contact.email', 'admin@tarqumi.com');

            Mail::send('emails.contact-submission', [
                'submission' => $this->submission,
            ], function ($message) use ($recipientEmail) {
                $message->to($recipientEmail)
                    ->subject("[Tarqumi Contact Form] {$this->submission->subject} - from {$this->submission->name}");
            });

            // Update submission
            $this->submission->update([
                'email_sent_at' => now(),
            ]);

            Log::info('Contact email sent successfully', [
                'submission_id' => $this->submission->id,
                'recipient' => $recipientEmail,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send contact email', [
                'submission_id' => $this->submission->id,
                'error' => $e->getMessage(),
                'attempt' => $this->attempts(),
            ]);

            throw $e; // Re-throw to trigger retry
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Contact email permanently failed', [
            'submission_id' => $this->submission->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
