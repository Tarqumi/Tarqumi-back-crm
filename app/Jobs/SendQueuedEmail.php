<?php

namespace App\Jobs;

use App\Models\EmailQueue;
use App\Models\EmailLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendQueuedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 60;
    public $backoff = [60, 300, 900]; // 1min, 5min, 15min

    public function __construct(
        public EmailQueue $emailQueue
    ) {}

    public function handle(): void
    {
        try {
            // Send email
            Mail::send([], [], function ($message) {
                $message->to($this->emailQueue->to_email, $this->emailQueue->to_name)
                    ->from($this->emailQueue->from_email, $this->emailQueue->from_name)
                    ->subject($this->emailQueue->subject)
                    ->html($this->emailQueue->body_html)
                    ->text($this->emailQueue->body_text);
            });

            // Mark as sent
            $this->emailQueue->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            // Log success
            EmailLog::create([
                'email_queue_id' => $this->emailQueue->id,
                'to_email' => $this->emailQueue->to_email,
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            Log::info("Email sent successfully", [
                'queue_id' => $this->emailQueue->id,
                'to' => $this->emailQueue->to_email,
            ]);

        } catch (\Exception $e) {
            // Mark as failed
            $this->emailQueue->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'failed_at' => now(),
            ]);

            // Log failure
            EmailLog::create([
                'email_queue_id' => $this->emailQueue->id,
                'to_email' => $this->emailQueue->to_email,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'failed_at' => now(),
            ]);

            Log::error("Email failed to send", [
                'queue_id' => $this->emailQueue->id,
                'to' => $this->emailQueue->to_email,
                'error' => $e->getMessage(),
            ]);

            // Re-throw for retry logic
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        // Called after all retries exhausted
        Log::critical("Email permanently failed after all retries", [
            'queue_id' => $this->emailQueue->id,
            'to' => $this->emailQueue->to_email,
            'error' => $exception->getMessage(),
        ]);
    }
}
