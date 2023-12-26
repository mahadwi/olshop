<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthenticationUpdateRequest extends FormRequest
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
            'title'  => 'required',
            'title_en'  => 'required',
            'description'  => 'required',
            'description_en'  => 'required',
            'no_hp'  => 'required|numeric',
            'link'  => 'required|url',
            'titleSection1' => 'nullable|string|max:255',
            'titleEnSection1' => 'nullable|string|max:255',
            'imageSection1' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'descriptionSection1' => 'nullable',
            'descriptionEnSection1' => 'nullable',
            'titleSection2' => 'nullable|string|max:255',
            'titleEnSection2' => 'nullable|string|max:255',
            'imageSection2' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'descriptionSection2' => 'nullable',
            'descriptionEnSection2' => 'nullable',
            'titleSection3' => 'nullable|string|max:255',
            'titleEnSection3' => 'nullable|string|max:255',
            'imageSection3' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'descriptionSection3' => 'nullable',
            'descriptionEnSection3' => 'nullable'
        ];
    }
}
