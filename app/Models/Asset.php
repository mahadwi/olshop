<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'code',
        'group_asset_id',        
    ];

    public function groupAsset()
    {
        return $this->belongsTo(GroupAsset::class);
    }

    public function pembelianAsset()
    {
        return $this->hasOne(PembelianAsset::class);
    }
}
