<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AboutUsUpdateRequest extends FormRequest
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
            'description'  => 'required',
            'description_en'  => 'required',
            'address'  => 'required',
            'detail_address'  => 'required',
            'maps'  => 'required',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:500',
        ];
    }
}
