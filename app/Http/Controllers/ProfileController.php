<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('users.profile', ['user' => auth()->user()]);
    }

    public function update(UpdateProfileRequest $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();
        
        // Determine what type of update this is
        $isEmailUpdate = $request->has('email') && $request->email !== $user->email;
        $isPasswordUpdate = $request->filled('password');
        $isPhotoUpdate = $request->hasFile('profile_photo');
        $isPersonalInfoUpdate = $request->hasAny(['first_name', 'middle_name', 'last_name', 'contact_number', 'address']);

        try {
            // Handle email update (requires password verification)
            if ($isEmailUpdate) {
                if (!$request->filled('current_password') || !Hash::check($request->current_password, $user->password)) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Current password is required to change email.',
                            'errors' => ['current_password' => ['Current password is incorrect.']]
                        ], 422);
                    }
                    return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
                }
                
                $user->email = $data['email'];
                $user->email_verified_at = null; // Require re-verification
                $user->email_verification_token = null;
                
                // Send verification email
                $emailService = new \App\Services\EmailVerificationService();
                $emailService->sendVerificationEmail($user);
            }

            // Handle password update
            if ($isPasswordUpdate) {
                if (!Hash::check($data['current_password'], $user->password)) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Current password is incorrect.',
                            'errors' => ['current_password' => ['Current password is incorrect.']]
                        ], 422);
                    }
                    return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
                }
                
                $user->password = Hash::make($data['password']);
            }

            // Handle personal info update (no password required)
            if ($isPersonalInfoUpdate) {
                $user->first_name = $data['first_name'] ?? $user->first_name;
                $user->middle_name = $data['middle_name'] ?? $user->middle_name;
                $user->last_name = $data['last_name'] ?? $user->last_name;
                $user->contact_number = $data['contact_number'] ?? $user->contact_number;
                $user->address = $data['address'] ?? $user->address;
            }

            // Handle photo update (no password required)
            if ($isPhotoUpdate) {
                if ($user->profile_photo) {
                    Storage::disk('public')->delete($user->profile_photo);
                }
                $photoPath = $request->file('profile_photo')->store('profile-photos', 'public');
                $user->profile_photo = $photoPath;
            }

            $user->save();

            // Return appropriate response based on request type
            if ($request->expectsJson()) {
                $message = match(true) {
                    $isEmailUpdate => 'Email updated successfully. Please check your new email for verification.',
                    $isPasswordUpdate => 'Password updated successfully.',
                    $isPhotoUpdate => 'Profile photo updated successfully.',
                    $isPersonalInfoUpdate => 'Personal information updated successfully.',
                    default => 'Profile updated successfully.'
                };

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'redirect' => route('profile.edit')
                ]);
            }

            // For regular form submissions, redirect back with success message
            $message = match(true) {
                $isEmailUpdate => 'Email updated successfully. Please check your new email for verification.',
                $isPasswordUpdate => 'Password updated successfully.',
                $isPhotoUpdate => 'Profile photo updated successfully.',
                $isPersonalInfoUpdate => 'Personal information updated successfully.',
                default => 'Profile updated successfully.'
            };

            return redirect()->route('profile.edit')->with('status', $message);

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update profile: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('profile.edit')->with('error', 'Failed to update profile. Please try again.')->withInput();
        }
    }
}
