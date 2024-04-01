<?php

namespace App\Http\Requests\API;

use App\Models\ClosingDay;
use Illuminate\Validation\Validator as ValidationValidator;

use Illuminate\Foundation\Http\FormRequest;

class CloseDayRequest extends FormRequest
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
            //
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'kasir_close'  => $this->user()->id,
            'close'        => date('Y-m-d H:i')
        ]);
    }

    public function withValidator(ValidationValidator $validator)
    {
        $validator->after(function ($validator) {

            //open day validation
            $openDay = ClosingDay::whereDate('open', date('Y-m-d'))->first();

            // $this->merge([
            //     'base_price' =>  $priceSource->price,
            //     'min_price' => $priceSource->min_price
            // ]);

            $msg = 'Open Day Is Not Yet Set. ';
            if (!$openDay) {
                $validator->errors()->add(
                    'open',
                    $msg
                );
            }
        });
    }
}
