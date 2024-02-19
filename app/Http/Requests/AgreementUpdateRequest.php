<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgreementUpdateRequest extends FormRequest
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
        $rules =  [
            'name'  => 'required',
            'name_en'  => 'required',
            'file_type' => 'required|string',
            'file' => 'nullable|max:500',
            'is_active'  => 'required',
            // 'file' => 'nullable|mimes:docx|max:500',
        ];        
        
        return $rules;
    }
}
