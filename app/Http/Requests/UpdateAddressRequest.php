<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Set this to true. Your route middleware (e.g., 'auth') 
        // already ensures the user is logged in before reaching the controller.
        return true; 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'address_line_1' => ['required', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'city'           => ['required', 'string', 'max:100'],
            'state'          => ['required', 'string', 'max:100'],
            'postal_code'    => ['required', 'string', 'max:20'], // Max 20 covers most international formats
            'country'        => ['required', 'string', 'max:100'],
        ];
    }

    /**
     * Optional: Get custom messages for validator errors.
     * Use this if you want to override default Laravel validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'address_line_1.required' => 'A primary street address is required.',
            'postal_code.required'    => 'Please provide a ZIP or postal code.',
        ];
    }
}