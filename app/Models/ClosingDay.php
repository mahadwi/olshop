<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClosingDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'open',
        'close',
        'starting_cash',
        'cash_in',
        'cash_out',
        'total_cash',
        'total_return',
        'kasir_open',
        'kasir_close',
    ];
}
