<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkWithUsStoreSection3Request extends FormRequest
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
        dd(
            request()->all()
        );
        return [
            'titleSection3' => 'required|string|max:255',
            'titleEnSection3' => 'required|string|max:255',
            'descriptionSection3' => 'required',
            'descriptionEnSection3' => 'required',
            'imageSection3' => 'nullable|image|mimes:jpg,png,jpeg|max:500',
        ];
    }
}
