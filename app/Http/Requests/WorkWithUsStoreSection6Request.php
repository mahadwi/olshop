<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkWithUsStoreSection6Request extends FormRequest
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

            'cardsSection6' => 'required|array',
            'cardsSection6.*.id' => 'nullable|integer',
            'cardsSection6.*.title' => 'required|string|max:255',
            'cardsSection6.*.title_en' => 'required|string|max:255',
            'cardsSection6.*.description' => 'required',
            'cardsSection6.*.description_en' => 'required',
            'cardsSection6.*.image' => 'nullable|image|mimes:jpg,png,jpeg|max:500',
        ];
    }

    public function attributes()
    {
        return [
            'cardsSection6.*.title' => 'Title',
            'cardsSection6.*.title_en' => 'Title In English',
            'cardsSection6.*.description' => 'Description',
            'cardsSection6.*.description_en' => 'Description In English',
            'cardsSection6.*.image' => 'Image',
        ];
    }

    // public function prepareForValidation()
    // {
    //     $this->merge([
    //         'cardsSection2.*.description' => $this->cardSection2?->description == "<p><br></p>" ? "" : $this->cardSection2?->description
    //     ]);
    // }
}
