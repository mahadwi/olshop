<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserApiRequest extends FormRequest
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
            'userName' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|min:9|max:16',
            'gender' => ['nullable', 'string', 'in:male,female'],
            'email' => 'required|email',
            'birth_date' => 'nullable|date_format:Y-m-d',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:500',
        ];
    }
}
