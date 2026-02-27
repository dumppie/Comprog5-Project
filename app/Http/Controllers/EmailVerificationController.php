<?php

namespace App\Http\Controllers;

use App\Services\EmailVerificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class EmailVerificationController extends Controller
{
    protected $emailVerificationService;

    public function __construct(EmailVerificationService $emailVerificationService)
    {
        $this->emailVerificationService = $emailVerificationService;
    }

    /**
     * Send verification email
     */
    public function send(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        if ($this->emailVerificationService->isEmailVerified($user)) {
            return response()->json(['message' => 'Email already verified'], 200);
        }

        $sent = $this->emailVerificationService->sendVerificationEmail($user);

        if ($sent) {
            return response()->json([
                'message' => 'Verification email sent successfully',
                'email' => $user->email
            ], 200);
        }

        return response()->json(['message' => 'Failed to send verification email'], 500);
    }

    /**
     * Verify email with token
     */
    public function verify(string $token)
    {
        $verified = $this->emailVerificationService->verifyEmail($token);

        if ($verified) {
            return redirect()->route('verification.success')
                ->with('success', 'Your email has been successfully verified!');
        }

        return redirect()->route('verification.failed')
            ->with('error', 'Invalid or expired verification token.');
    }

    /**
     * Resend verification email
     */
    public function resend(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $sent = $this->emailVerificationService->resendVerificationEmail($user);

        if ($sent) {
            return response()->json([
                'message' => 'Verification email resent successfully',
                'email' => $user->email
            ], 200);
        }

        if ($this->emailVerificationService->isEmailVerified($user)) {
            return response()->json(['message' => 'Email already verified'], 200);
        }

        return response()->json(['message' => 'Failed to resend verification email'], 500);
    }

    /**
     * Show verification success page
     */
    public function success()
    {
        return view('auth.verification-success');
    }

    /**
     * Show verification failed page
     */
    public function failed()
    {
        return view('auth.verification-failed');
    }

    /**
     * Check verification status
     */
    public function status(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        return response()->json([
            'verified' => $this->emailVerificationService->isEmailVerified($user),
            'email' => $user->email,
            'verified_at' => $user->email_verified_at
        ]);
    }
}
