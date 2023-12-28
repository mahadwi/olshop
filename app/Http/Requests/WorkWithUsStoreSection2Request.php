<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkWithUsStoreSection2Request extends FormRequest
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

            'forms' => 'required|array',
            'forms.*.subTitleSection2' => 'required|string|max:255',
            'forms.*.subTitleEnSection2' => 'required|string|max:255',
            'forms.*.descriptionSection2' => 'required',
            'forms.*.descriptionEnSection2' => 'required',
            'forms.*.imageSection2' => 'nullable|image|mimes:jpg,png,jpeg|max:500',
        ];
    }
}
