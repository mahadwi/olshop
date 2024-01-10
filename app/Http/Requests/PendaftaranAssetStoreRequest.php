<?php

namespace App\Http\Requests;

use App\Models\PendaftaranAsset;
use Illuminate\Foundation\Http\FormRequest;

class PendaftaranAssetStoreRequest extends FormRequest
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
            'nomor' => 'required|string|unique:' . PendaftaranAsset::class,
            'tanggal' => 'required|date_format:d-m-Y',
            "pembelian_asset_id" => "required",
            "group_asset_id" => "required",
            "asset_id" => "required",
            "metode_penyusutan" => "required",
            "tarif_penyusutan" => "required",
            "umur" => "required",
            "nilai_perolehan" => "required",
            "dataPenyusutan" => "required|array",
        ];
    }
}
