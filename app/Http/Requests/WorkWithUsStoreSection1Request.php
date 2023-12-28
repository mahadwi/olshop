<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkWithUsStoreSection1Request extends FormRequest
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
            'titleSection1' => 'required|string|max:255',
            'titleEnSection1' => 'required|string|max:255',
            'descriptionSection1' => 'required',
            'descriptionEnSection1' => 'required',
            'imageSection1' => 'nullable|image|mimes:jpg,png,jpeg|max:500',
            'linkSection1' => 'required|url',
        ];
    }
}
