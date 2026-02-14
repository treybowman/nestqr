<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreListingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'address' => ['required', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:50'],
            'zip' => ['nullable', 'string', 'max:20'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'beds' => ['nullable', 'integer', 'min:0'],
            'baths' => ['nullable', 'numeric', 'min:0'],
            'sqft' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', Rule::in(['active', 'pending', 'sold', 'inactive'])],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'address.required' => 'A property address is required.',
            'address.max' => 'The address may not exceed 255 characters.',
            'price.numeric' => 'The price must be a valid number.',
            'price.min' => 'The price cannot be negative.',
            'beds.integer' => 'The number of beds must be a whole number.',
            'baths.numeric' => 'The number of baths must be a valid number.',
            'sqft.integer' => 'The square footage must be a whole number.',
            'description.max' => 'The description may not exceed 5,000 characters.',
            'status.in' => 'The status must be one of: active, pending, sold, or inactive.',
        ];
    }
}
