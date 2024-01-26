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
            'name'                  => 'required|string|max:255|unique:vendors,name,' . $this->vendor->id,
            'phone'                 => 'required|string|min:9|max:16|unique:vendors,phone,' . $this->vendor->id,  
            'email'                 => 'required|string|email|max:255|unique:vendors,email,' . $this->vendor->id,  
            'ktp'                   => 'required|integer',
            'bank'                  => 'required|exists:bank_codes,code',  
            'bank_account_holder'   => 'required|string',
            'bank_account_number'   => 'required|integer',     
            'address'               => 'required|string',     
        ];
    }
}
