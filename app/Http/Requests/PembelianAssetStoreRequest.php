<?php

namespace App\Http\Requests;

use App\Models\PembelianAsset;
use Illuminate\Foundation\Http\FormRequest;

class PembelianAssetStoreRequest extends FormRequest
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
            'nomor' => 'required|string|unique:' . PembelianAsset::class,
            'tanggal' => 'required|date_format:d-m-Y',
            'vendor_id' => 'required',
            'jatuh_tempo' => 'required|integer',
            'tanggal_jatuh_tempo' => 'required|date_format:d-m-Y',
            'asset_id' => 'required',
            'qty' => 'required|integer',
            'jenis_ppn' => 'required',
            'price' => 'required',
            'total' => 'required',
        ];
    }
}
