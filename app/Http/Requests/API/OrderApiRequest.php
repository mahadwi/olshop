<?php

namespace App\Http\Requests\API;

use App\Constants\OrderState;
use App\Constants\PaymentState;
use Illuminate\Foundation\Http\FormRequest;

class OrderApiRequest extends FormRequest
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
        $status = OrderState::getValues();
        $status = implode(',', $status);

        $paymentStatus = PaymentState::getValues();
        $paymentStatus = implode(',', $paymentStatus);        

        return [
            'status'         => 'nullable|in:'.$status,          
            'payment_status' => 'nullable|in:'.$paymentStatus,          
        ];
    }
}
