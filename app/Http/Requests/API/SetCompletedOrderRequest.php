<?php

namespace App\Http\Requests\API;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator as ValidationValidator;


class SetCompletedOrderRequest extends FormRequest
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
           
        ];
    }

    public function withValidator(ValidationValidator $validator)
    {
        $validator->after(function ($validator) {

            if ($this->order->status != 'On Going') {
                $validator->errors()->add(
                    'Order',
                    'Order Not Found'
                );
            }
        });
    }
}
