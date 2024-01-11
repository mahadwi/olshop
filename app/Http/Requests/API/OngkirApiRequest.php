<?php

namespace App\Http\Requests\API;

use App\Constants\Courier;
use Illuminate\Foundation\Http\FormRequest;

class OngkirApiRequest extends FormRequest
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
            'destination' => 'required|integer',
            'weight' => 'required',
            'courier' => 'required|string|in:'.$courier,
        ];
    }
}
