<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coa extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'normal_balance',
        'is_saldo_awal',
        'group_coa_id',
        'status',
    ];

    public function groupCoa()
    {
        return $this->belongsTo(GroupCoa::class);
    }
}
