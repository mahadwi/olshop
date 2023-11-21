<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'coa_id',
        'coa_akumulasi_id',
        'coa_depresiasi_id',
        'umur',
        'metode_penyusutan',
    ];
}
