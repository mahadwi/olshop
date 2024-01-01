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

            'cardsSection2' => 'required|array',
            'cardsSection2.*.id' => 'nullable|integer',
            'cardsSection2.*.title' => 'required|string|max:255',
            'cardsSection2.*.title_en' => 'required|string|max:255',
            'cardsSection2.*.description' => 'required',
            'cardsSection2.*.description_en' => 'required',
            'cardsSection2.*.image' => 'nullable|image|mimes:jpg,png,jpeg|max:500',
        ];
    }

    public function attributes()
    {
        return [
            'cardsSection2.*.title' => 'Title',
            'cardsSection2.*.title_en' => 'Title In English',
            'cardsSection2.*.description' => 'Description',
            'cardsSection2.*.description_en' => 'Description In English',
            'cardsSection2.*.image' => 'Image',
        ];
    }

    // public function prepareForValidation()
    // {
    //     $this->merge([
    //         'cardsSection2.*.description' => $this->cardSection2?->description == "<p><br></p>" ? "" : $this->cardSection2?->description
    //     ]);
    // }
}
