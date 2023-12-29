<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConsignmentStoreSection4Request extends FormRequest
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
            'titleSection4' => 'required|string|max:255',
            'titleEnSection4' => 'required|string|max:255',

            'cardsSection4' => 'required|array',
            'cardsSection4.*.id' => 'nullable|integer',
            'cardsSection4.*.title' => 'required|string|max:255',
            'cardsSection4.*.title_en' => 'required|string|max:255',
            'cardsSection4.*.description' => 'required',
            'cardsSection4.*.description_en' => 'required',
            'cardsSection4.*.image' => 'nullable|image|mimes:jpg,png,jpeg|max:500',
        ];
    }
}
