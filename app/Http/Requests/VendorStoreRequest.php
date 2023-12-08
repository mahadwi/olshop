<?php

namespace App\Http\Requests;

use App\Constants\VendorType;
use Illuminate\Foundation\Http\FormRequest;

class VendorStoreRequest extends FormRequest
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
            'name'                 => ['required', 'string'],
            'email'                 => ['required', 'email'],
            'phone'                 => ['required', 'string'],
            'address'               => ['required', 'string'],
            'ktp'                   => ['required', 'integer'],
            'bank'                  => ['required', 'string'],
            'bank_account_holder'   => ['required', 'string'],
            'bank_account_number'   => ['required', 'integer'],     
        ];
    }

    public function prepareForValidation()
    {
        $this->merge(['type' => VendorType::ASET]);
    }
}
