<?php

namespace App\Http\Requests;

use App\Rules\NonEmptyStringIgnoringTags;
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
        return [
            'titleSection3' => 'required|string|max:255',
            'titleEnSection3' => 'required|string|max:255',

            'cardsSection3' => 'required|array',
            'cardsSection3.*.id' => 'nullable|integer',
            'cardsSection3.*.title' => 'required|string|max:255',
            'cardsSection3.*.title_en' => 'required|string|max:255',
            'cardsSection3.*.description' => 'required',
            'cardsSection3.*.description_en' => 'required',
            'cardsSection3.*.image' => 'nullable|image|mimes:jpg,png,jpeg|max:500',
        ];
    }

    public function attributes()
    {
        return [
            'cardsSection3.*.title' => 'Title',
            'cardsSection3.*.title_en' => 'Title In English',
            'cardsSection3.*.description' => 'Description',
            'cardsSection3.*.description_en' => 'Description In English',
            'cardsSection3.*.image' => 'Image',
        ];
    }
}
