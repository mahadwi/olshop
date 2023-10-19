<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorUpdateRequest extends FormRequest
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
            'no_hp'                 => ['required', 'string'],
            'ktp'                   => ['required', 'integer'],
            'bank'                  => ['required', 'string'],
            'bank_account_holder'   => ['required', 'string'],
            'bank_account_number'   => ['required', 'integer'],     
            'address'               => ['required', 'string'],
        ];
    }
}
