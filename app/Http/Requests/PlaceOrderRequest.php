<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlaceOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && !$this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'shipping_name'    => ['required', 'string', 'max:255'],
            'shipping_address' => ['required', 'string'],
            'shipping_contact' => ['required', 'string', 'max:50'],
            'payment_method_id' => ['required', 'integer', 'exists:payment_methods,id'],
        ];
    }
}
