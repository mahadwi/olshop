<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventUpdateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'description_en' => 'required|string',
            'place' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'time_start' => 'required',
            'time_end' => 'required',
            'maps' => 'required',
            'detail_maps' => 'required',
            'banner' => 'nullable|image|mimes:jpg,png,jpeg|max:500',
            'cover' => 'nullable|image|mimes:jpg,png,jpeg|max:500',
            'details.*' => 'required',
        ];
    }
}
