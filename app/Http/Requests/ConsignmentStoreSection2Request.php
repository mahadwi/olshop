<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConsignmentStoreSection2Request extends FormRequest
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
            'titleSection2' => 'required|string|max:255',
            'titleEnSection2' => 'required|string|max:255',
            'descriptionSection2' => 'required',
            'descriptionEnSection2' => 'required',
            'imageSection2' => 'nullable|image|mimes:jpg,png,jpeg|max:500',
            'linkSection2' => 'required',
        ];
    }
}
