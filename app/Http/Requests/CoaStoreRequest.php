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
            'code' => 'required|string|max:5',
            'name' => 'required|string|max:255',
            'normal_balance' => 'required',
            'status' => 'required',
            'group_coa_id' => 'required',
            'saldo_awal' => 'required|integer',
        ];
    }
}
