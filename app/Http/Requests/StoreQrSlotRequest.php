<?php

namespace App\Http\Requests;

use App\Models\Icon;
use Illuminate\Foundation\Http\FormRequest;

class StoreQrSlotRequest extends FormRequest
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
            'icon_id' => ['required', 'exists:icons,id'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(\Illuminate\Validation\Validator $validator): void
    {
        $validator->after(function (\Illuminate\Validation\Validator $validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $icon = Icon::find($this->input('icon_id'));
            $user = $this->user();

            if ($icon && $user && !$user->canAccessIcon($icon)) {
                $validator->errors()->add(
                    'icon_id',
                    "The selected icon requires a Pro plan or higher. Please upgrade your plan to use this icon."
                );
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'icon_id.required' => 'Please select an icon for your QR code.',
            'icon_id.exists' => 'The selected icon does not exist.',
        ];
    }
}
