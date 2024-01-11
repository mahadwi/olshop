<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenjualanAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor', 
        'tanggal', 
        "pendaftaran_asset_id",
        "customer_id",
        "nilai_jual",
        "keterangan",
    ];

    protected $casts = [
        'tanggal' => 'date:d-m-Y',
    ];

    public function setTanggalAttribute($value)
    {
        $this->attributes['tanggal'] = Carbon::parse($value)->format('Y-m-d');
    }

    public function pendaftaranAsset()
    {
        return $this->belongsTo(PendafaranAsset::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
