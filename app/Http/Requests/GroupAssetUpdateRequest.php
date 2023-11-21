<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupAssetUpdateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'coa_id' => 'required',
            'coa_akumulasi_id' => 'required',
            'coa_depresiasi_id' => 'required',
            'metode_penyusutan' => 'required',
            'umur' => 'integer',
        ];
    }
}
