<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(): View|RedirectResponse
    {
        // Redirect logged-in users to home page
        if (Auth::check()) {
            return redirect()->route('home');
        }
        
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();
        $user = Auth::user();

        // FR3.1: Only verified users may log in; send to verify page so they can resend if link expired
        if (!$user->hasVerifiedEmail()) {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->route('verification.notice')->withErrors([
                'email' => 'You must verify your email before logging in. Use the button below to resend the verification link.',
            ]);
        }

        if (!$user->isActive()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors([
                'email' => 'Your account is inactive. Contact support.',
            ])->withInput($request->only('email'));
        }

        // Redirect admins to admin dashboard, others to home
        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        return redirect()->intended(route('home', absolute: false));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
