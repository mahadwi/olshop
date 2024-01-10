<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenyusutanAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal', 
        'penyusutan',
        'nilai',
    ];

    public function pendaftaranAsset()
    {
        return $this->belongsTo(PendaftaranAsset::class);
    }
}
