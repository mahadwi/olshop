<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tarif_penyusutan',
        'coa_id',
        'coa_akumulasi_id',
        'coa_depresiasi_id',
        'umur',
        'metode_penyusutan',
    ];

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
}
