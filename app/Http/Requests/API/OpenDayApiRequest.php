<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class OpenDayApiRequest extends FormRequest
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
            'starting_cash' => 'required|integer',
        ];
    }

    protected function prepareForValidation()
    {

        $this->merge([
            'kasir_open'  => $this->user()->id,
        ]);
    }
}
