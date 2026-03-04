<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'is_admin' => ['required', 'boolean'],
            'user_status_id' => ['required', 'exists:user_statuses,id'],
        ];
    }
}
