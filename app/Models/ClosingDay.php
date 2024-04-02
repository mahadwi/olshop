<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    protected $appends = ['date', 'open_formatted', 'close_formatted'];

    public function getDateAttribute()
    {
        return Carbon::parse($this->open)->format('d-m-Y');
    }

    public function getOpenFormattedAttribute()
    {
        return Carbon::parse($this->open)->format('d-m-Y H:i');
    }

    public function getCloseFormattedAttribute()
    {
        return Carbon::parse($this->close)->format('d-m-Y H:i');
    }

    public function kasirOpen()
    {
        return $this->belongsTo(User::class, 'kasir_open');
    }

    public function kasirClose()
    {
        return $this->belongsTo(User::class, 'kasir_close');
    }


}
