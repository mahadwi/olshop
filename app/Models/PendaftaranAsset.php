<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\PenjualanAsset;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PendaftaranAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor', 
        'tanggal', 
        "pembelian_asset_id",
        "group_asset_id",
        "asset_id",
        "metode_penyusutan",
        "tarif_penyusutan",
        "umur",
        "nilai_perolehan",
    ];

    protected $casts = [
        'tanggal' => 'date:d-m-Y',
    ];

    public function setTanggalAttribute($value)
    {
        $this->attributes['tanggal'] = Carbon::parse($value)->format('Y-m-d');
    }

    public function pembelianAsset()
    {
        return $this->belongsTo(PembelianAsset::class);
    }

    public function penjualanAsset()
    {
        return $this->hasOne(PenjualanAsset::class);
    }

    public function groupAsset()
    {
        return $this->belongsTo(GroupAsset::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function penyusutanAsset()
    {
        return $this->hasMany(PenyusutanAsset::class);
    }
}
