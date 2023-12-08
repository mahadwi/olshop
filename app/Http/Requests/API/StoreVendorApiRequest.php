<?php

namespace App\Http\Requests\API;

use App\Constants\VendorType;
use App\Models\Vendor;
use Illuminate\Foundation\Http\FormRequest;

class StoreVendorApiRequest extends FormRequest
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
            'phone' => 'required|string|min:9|max:16|unique:' . Vendor::class, 
            'email' => 'required|string|email|max:255|unique:' . Vendor::class,    
            'ktp'                   => 'required|integer',
            'bank'                  => 'required|string',
            'bank_account_holder'   => 'required|string',
            'bank_account_number'   => 'required|integer',     
            'address'               => 'required|string',     
        ];
    }

    public function prepareForValidation()
    {
        if ($this->user()) {
            $this->merge([
                'user_id' => $this->user()->id,
                'type'    => VendorType::TOKO,
            ]);
        }
    }
}
