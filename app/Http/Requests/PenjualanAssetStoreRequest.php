<?php

namespace App\Http\Requests;

use App\Models\PenjualanAsset;
use Illuminate\Foundation\Http\FormRequest;

class PenjualanAssetStoreRequest extends FormRequest
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
            'nomor' => 'required|string|unique:' . PenjualanAsset::class,
            'tanggal' => 'required|date_format:d-m-Y',
            "customer_id" => "required",
            "pendaftaran_asset_id" => "required",
            "nilai_jual" => "required",
        ];
    }
}
