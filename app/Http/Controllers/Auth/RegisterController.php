<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Models\UserStatus;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    public function register(RegisterUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['is_admin'] = false;
        $data['user_status_id'] = UserStatus::where('name', 'active')->firstOrFail()->id;

        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        $user = User::create($data);

        // Send verification email manually using custom service
        $emailService = new \App\Services\EmailVerificationService();
        $emailService->sendVerificationEmail($user);

        return redirect()->route('login')->with('status', 'Registration successful. Please verify your email before logging in.');
    }
}
