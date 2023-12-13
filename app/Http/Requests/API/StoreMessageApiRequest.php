<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageApiRequest extends FormRequest
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
            'firstName' => 'required|string|max:255',
            'lastName' => 'string|max:255',
            'email' => 'required|string|email',
            'handphone' => 'string|min:9|max:16',
            'subject' => 'required|string',
            'message' => 'required|string',
        ];
    }

    public function prepareForValidation()
    {
        if ($this->user()) {
            $this->merge(['user_id' => $this->user()->id]);
        }
    }
}
