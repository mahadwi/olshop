<?php

namespace App\Http\Requests;

use App\Services\Ongkir\OngkirService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator as ValidationValidator;

class OrderUpdateRequest extends FormRequest
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
            

            if($this->has('resi')){
                $cekResi = (new OngkirService)->cekResi($this->order->courier, $this->resi);
                if($cekResi['status'] != 200){
                    $validator->errors()->add(
                        'resi',
                        $cekResi['message']
                    );
                }
            }
            
        });
    }
}
