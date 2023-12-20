<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class BookingApiRequest extends FormRequest
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
            'event_detail_id'   => 'required|exists:event_details,id',
            'qty'               => 'required|integer',
            'total'            => 'required|integer',
            'voucher'           => 'nullable|exists:vouchers,code'            
        ];
    }

    public function prepareForValidation()
    {
        if ($this->user()) {
            $this->merge(['user_id' => $this->user()->id]);
        }
    }
}
