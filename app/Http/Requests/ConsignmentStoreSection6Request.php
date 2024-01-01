<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConsignmentStoreSection6Request extends FormRequest
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
            'titleSection6' => 'required|string|max:255',
            'titleEnSection6' => 'required|string|max:255',
            'descriptionSection6' => 'required',
            'descriptionEnSection6' => 'required',
        ];
    }
}
