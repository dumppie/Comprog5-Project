<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\EmailVerificationMail;

class EmailVerificationService
{
    /**
     * Generate a unique verification token
     */
    public function generateVerificationToken(): string
    {
        return Str::random(60);
    }

    /**
     * Send verification email to user
     */
    public function sendVerificationEmail($user): bool
    {
        try {
            // Debug: Log user info
            \Log::info('Sending verification email to user ID: ' . $user->id . ', Email: ' . $user->email);
            
            // Generate or regenerate verification token
            $user->email_verification_token = $this->generateVerificationToken();
            $user->email_verified_at = null;
            $user->save();

            // Debug: Log the generated token
            \Log::info('Generated token: ' . $user->email_verification_token);

            // Send verification email
            Mail::to($user->email)->send(new EmailVerificationMail($user));

            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to send verification email: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Verify email with token
     */
    public function verifyEmail(string $token): bool
    {
        $user = \App\Models\User::where('email_verification_token', $token)->first();

        if (!$user) {
            return false;
        }

        // Mark email as verified
        $user->email_verified_at = now();
        $user->email_verification_token = null;
        $user->save();

        return true;
    }

    /**
     * Check if user's email is verified
     */
    public function isEmailVerified($user): bool
    {
        return !is_null($user->email_verified_at);
    }

    /**
     * Resend verification email
     */
    public function resendVerificationEmail($user): bool
    {
        if ($this->isEmailVerified($user)) {
            return false;
        }

        return $this->sendVerificationEmail($user);
    }
}
