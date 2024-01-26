<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVendorApiRequest extends FormRequest
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
            'name'                  => 'nullable|string|max:255|unique:vendors,name,' . $this->vendor->id,
            'phone'                 => 'nullable|string|min:9|max:16|unique:vendors,phone,' . $this->vendor->id,  
            'email'                 => 'nullable|string|email|max:255|unique:vendors,email,' . $this->vendor->id,  
            'ktp'                   => 'nullable|integer',
            'bank'                  => 'nullable|exists:bank_codes,code',  
            'bank_account_holder'   => 'nullable|string',
            'bank_account_number'   => 'nullable|integer',     
            'address'               => 'nullable|string',     
        ];
    }
}
