<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PembelianAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor', 
        'tanggal', 
        'vendor_id',
        'jatuh_tempo', 
        'tanggal_jatuh_tempo', 
        'asset_id',
        'qty',
        'jenis_ppn',
        'price',
        'total',
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date:d-m-Y',
        'tanggal_jatuh_tempo' => 'date:d-m-Y',
    ];

    public function setTanggalAttribute($value)
    {
        $this->attributes['tanggal'] = Carbon::parse($value)->format('Y-m-d');
    }

    public function setTanggalJatuhTempoAttribute($value)
    {
        $this->attributes['tanggal_jatuh_tempo'] = Carbon::parse($value)->format('Y-m-d');
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
    
}
