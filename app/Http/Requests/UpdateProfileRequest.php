<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->user();
        
        return [
            // Personal info (optional)
            'first_name' => ['sometimes', 'required', 'string', 'max:255'],
            'middle_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'last_name' => ['sometimes', 'required', 'string', 'max:255'],
            'contact_number' => ['sometimes', 'nullable', 'string', 'max:50'],
            'address' => ['sometimes', 'nullable', 'string'],
            
            // Email (optional but requires current password if changing)
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            
            // Photo (optional)
            'profile_photo' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            
            // Password (optional but requires current password)
            'password' => [
                'sometimes',
                'nullable',
                'string',
                'confirmed',
                Password::min(8)->letters()->mixedCase()->numbers(),
            ],
            
            // Current password (conditional)
            'current_password' => [
                'required_if:email,!=,' . $user->email,
                'required_if:password,!null',
                'current_password',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required_if' => 'Current password is required to change your email or password.',
            'current_password.current_password' => 'Current password is incorrect.',
            'password.min' => 'Password must be at least 8 characters with 1 uppercase, 1 lowercase, and 1 number.',
            'password.confirmed' => 'Password confirmation does not match.',
            'email.unique' => 'This email address is already taken.',
        ];
    }
}
