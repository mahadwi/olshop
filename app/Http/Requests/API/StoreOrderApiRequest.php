<?php

namespace App\Http\Requests\API;

use App\Constants\Courier;
use App\Constants\OrderState;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderApiRequest extends FormRequest
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
        $courier = Courier::getValues();
        $courier = implode(',', $courier);

        return [
            'voucher'               => 'nullable|exists:vouchers,code',            
            'address_id'            => 'required|exists:addresses,id',
            'courier'               => 'required|string|in:'.$courier,
            'ongkir'                => 'required|integer',
            'discount'              => 'nullable|integer',
            'total'                 => 'required|integer',
            'details'               => 'required|array',
            'details.*.product_id'  => 'required|exists:products,id',  
            'details.*.qty'         => 'required|min:1|integer',  
            'details.*.price'       => 'required|integer',  
            'details.*.total'       => 'required|integer',  
        ];
    }

    public function prepareForValidation()
    {
        if ($this->user()) {
            $this->merge([
                'user_id' => $this->user()->id,
                'status'  => OrderState::ONPROCESS
            ]);
        }
    }
}
