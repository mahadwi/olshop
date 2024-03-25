<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderPosApiRequest extends FormRequest
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
            'customer_name'         => 'required|string',
            'customer_email'         => 'required|email',
            'voucher'               => 'nullable|exists:vouchers,code',            
            'discount'              => 'nullable|integer',
            'pay'                   => 'nullable|integer',
            'return'                => 'nullable|integer',
            'note'                  => 'nullable|string',
            'jumlah'                => 'required|integer',
            'pembulatan'            => 'required|integer',
            'total'                 => 'required|integer',
            'is_cash'               => 'required|boolean',
            'details'               => 'required|array',
            'details.*.product_id'  => 'required|exists:products,id',  
            'details.*.qty'         => 'required|min:1|integer',  
            'details.*.price'       => 'required|integer',  
            'details.*.total'       => 'required|integer',  
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'user_id'   => $this->getCustomer()->id,
            'kasir_id'  => $this->user()->id,
            'is_pos'    => true,
        ]);
    }

    private function getCustomer()
    {
        $customer = User::firstOrNew([
            'email' => $this->customer_email
        ]);

        $customer->name = $this->customer_name;
        $customer->save();

        return $customer;
    }
}
