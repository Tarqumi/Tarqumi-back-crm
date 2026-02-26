<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\EmailQueue;
use App\Models\User;
use App\Jobs\SendQueuedEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * Send password reset link to user's email
     */
    public function forgot(ForgotPasswordRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => __('auth.email_not_found'),
            ], 404);
        }

        // Generate password reset token
        $token = Password::createToken($user);

        // Create reset URL (frontend URL)
        $resetUrl = config('app.frontend_url') . "/reset-password?token={$token}&email=" . urlencode($user->email);

        // Prepare email content
        $emailBody = "
            <h2>" . __('auth.reset_password_title') . "</h2>
            <p>" . __('auth.reset_password_greeting', ['name' => $user->name]) . "</p>
            <p>" . __('auth.reset_password_message') . "</p>
            <p><a href='{$resetUrl}' style='background-color: #000; color: #fff; padding: 10px 20px; text-decoration: none; display: inline-block;'>" . __('auth.reset_password_button') . "</a></p>
            <p>" . __('auth.reset_password_expiry') . "</p>
            <p>" . __('auth.reset_password_ignore') . "</p>
            <p>" . __('auth.reset_password_link') . ": {$resetUrl}</p>
        ";

        $emailText = __('auth.reset_password_title') . "\n\n" .
                     __('auth.reset_password_greeting', ['name' => $user->name]) . "\n\n" .
                     __('auth.reset_password_message') . "\n\n" .
                     __('auth.reset_password_link') . ": {$resetUrl}\n\n" .
                     __('auth.reset_password_expiry') . "\n\n" .
                     __('auth.reset_password_ignore');

        // Queue the email
        $emailQueue = EmailQueue::create([
            'to_email' => $user->email,
            'to_name' => $user->name,
            'from_email' => config('mail.from.address'),
            'from_name' => config('mail.from.name'),
            'subject' => __('auth.reset_password_subject'),
            'body_html' => $emailBody,
            'body_text' => $emailText,
            'status' => 'pending',
        ]);

        // Dispatch job to send email
        SendQueuedEmail::dispatch($emailQueue);

        return response()->json([
            'success' => true,
            'message' => __('auth.reset_link_sent'),
        ]);
    }

    /**
     * Reset user's password using token
     */
    public function reset(ResetPasswordRequest $request): JsonResponse
    {
        // Verify the token
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'success' => true,
                'message' => __('auth.password_reset_success'),
            ]);
        }

        // Handle different error cases
        $errorMessage = match ($status) {
            Password::INVALID_TOKEN => __('auth.invalid_token'),
            Password::INVALID_USER => __('auth.email_not_found'),
            default => __('auth.password_reset_failed'),
        };

        return response()->json([
            'success' => false,
            'message' => $errorMessage,
        ], 400);
    }
}
