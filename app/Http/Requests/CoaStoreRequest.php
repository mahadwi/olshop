<?php

namespace App\Http\Requests;

use App\Models\Coa;
use Illuminate\Foundation\Http\FormRequest;

class CoaStoreRequest extends FormRequest
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
            'code' => 'required|string|max:7',
            'name' => 'required|string|max:255',
            'normal_balance' => 'nullable',
            'status' => 'required',
            'group_coa_id' => 'required',
            'is_saldo_awal' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'is_saldo_awal.required' => 'Saldo Awal is required',
        ];
    }
}
