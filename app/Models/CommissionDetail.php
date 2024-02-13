<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'commission_id',
        'min',
        'max',
        'percent',
    ];
}
