<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConsignmentStoreSection5Request extends FormRequest
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
            'titleSection5' => 'required|string|max:255',
            'titleEnSection5' => 'required|string|max:255',

            'cardsSection5' => 'required|array',
            'cardsSection5.*.id' => 'nullable|integer',
            'cardsSection5.*.title' => 'required|string|max:255',
            'cardsSection5.*.title_en' => 'required|string|max:255',
            'cardsSection5.*.description' => 'required',
            'cardsSection5.*.description_en' => 'required',
            'cardsSection5.*.image' => 'nullable|image|mimes:jpg,png,jpeg|max:500',
        ];
    }
}
