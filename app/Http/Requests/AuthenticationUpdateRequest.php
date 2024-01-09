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
            'title' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description' => 'required',
            'description_en' => 'required',
            'no_hp' => 'required|numeric',
            'link' => 'required|url',
            'titleSection1' => 'required|string|max:255',
            'titleEnSection1' => 'required|string|max:255',
            'imageSection1' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'descriptionSection1' => 'required',
            'descriptionEnSection1' => 'required',
            'titleSection2' => 'required|string|max:255',
            'titleEnSection2' => 'required|string|max:255',
            'imageSection2' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'descriptionSection2' => 'required',
            'descriptionEnSection2' => 'required',
            'titleSection3' => 'required|string|max:255',
            'titleEnSection3' => 'required|string|max:255',
            'imageSection3' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'descriptionSection3' => 'required',
            'descriptionEnSection3' => 'required'
        ];
    }

    public function prepareForValidation()
    {
            $this->merge([
                'descriptionSection1' => $this->descriptionSection1 == "<p><br></p>" ? "" : $this->descriptionSection1,
                'descriptionSection2' => $this->descriptionSection2 == "<p><br></p>" ? "" : $this->descriptionSection2,
                'descriptionSection3' => $this->descriptionSection3 == "<p><br></p>" ? "" : $this->descriptionSection3,
                'descriptionEnSection1' => $this->descriptionEnSection1 == "<p><br></p>" ? "" : $this->descriptionEnSection1,
                'descriptionEnSection2' => $this->descriptionEnSection2 == "<p><br></p>" ? "" : $this->descriptionEnSection2,
                'descriptionEnSection3' => $this->descriptionEnSection3 == "<p><br></p>" ? "" : $this->descriptionEnSection3,
            ]);
    }
}
