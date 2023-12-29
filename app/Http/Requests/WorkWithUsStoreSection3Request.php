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
            'work_with_us_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description' => ['required', new NonEmptyStringIgnoringTags()],
            'description_en' => ['required', new NonEmptyStringIgnoringTags()],
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:500',
        ];
    }
}
