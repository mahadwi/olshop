<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthenticationStoreSection3Request extends FormRequest
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
            'titleSection3' => 'required|string|max:255',
            'titleEnSection3' => 'required|string|max:255',
            'descriptionSection3' => 'required',
            'descriptionEnSection3' => 'required',
            'imageSection3' => 'nullable|image|mimes:jpg,png,jpeg|max:500',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'descriptionSection3' => $this->descriptionSection3 == "<p><br></p>" ? "" : $this->descriptionSection3,
            'descriptionEnSection3' => $this->descriptionEnSection3 == "<p><br></p>" ? "" : $this->descriptionEnSection3
        ]);
    }
}
